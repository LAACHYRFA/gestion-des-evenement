<?php
// Inclusion du fichier de configuration pour la connexion à la base de données
require "config.php";

// Démarrage de la session pour identifier l'utilisateur connecté
session_start();

// 1. Vérification de l'accès : Si l'utilisateur n'est pas connecté, redirection vers login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

// Vérification si l'ID de l'événement est passé dans l'URL
if(!isset($_GET['event_id'])){
    die("Événement introuvable");
}

// Récupération de l'ID depuis l'URL (méthode GET)
$id = $_GET['event_id'];

// 2. Récupération des données de l'événement depuis la base de données
$sql = $pdo->prepare("SELECT * FROM events WHERE id = ?");
$sql->execute([$id]);
$event = $sql->fetch(PDO::FETCH_ASSOC);

// Si l'ID ne correspond à aucun événement existant
if(!$event){
    die("Événement n'existe pas");
}

// 3. Traitement de la réservation après clic sur le bouton "Confirmer"
if(isset($_POST['ok'])){
    $user_id = $_SESSION['user_id'];

    // On vérifie s'il reste au moins une place disponible
    if($event['nbPlaces'] > 0){
        
        // A. Calcul et mise à jour (diminution) du nombre de places
        $places_rest = $event['nbPlaces'] - 1;
        $update = $pdo->prepare("UPDATE events SET nbPlaces = ? WHERE id = ?");
        $update->execute([$places_rest, $id]);

        // B. Insertion de la nouvelle réservation dans la table dédiée
        $insert = $pdo->prepare("INSERT INTO reservations (user_id, event_id) VALUES (?, ?)");
        $insert->execute([$user_id, $id]);

        // C. Redirection vers la même page avec un message de succès
        header("Location: booking.php?event_id=".$id."&success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réserver - <?php echo $event['title']; ?></title>
    <style>
        /* Styles CSS pour l'apparence de la carte de réservation */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('assets/festival.jpg') no-repeat center center fixed; 
            background-size: cover; font-family: 'Segoe UI', sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; color: white;
        }
        .booking-card { background: rgba(255, 255, 255, 0.95); color: #333; padding: 40px; border-radius: 25px; width: 100%; max-width: 450px; text-align: center; }
        .success-banner { background: #2ecc71; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px; font-weight: bold; }
        h3 { color: #e74c3c; font-size: 28px; margin-bottom: 10px; }
        .info { margin: 15px 0; font-size: 18px; color: #555; }
        .price-circle { background: #3498db; color: white; width: 120px; height: 120px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 20px auto; font-size: 22px; font-weight: bold; }
        .btn-confirm { background: #2ecc71; color: white; border: none; padding: 15px 30px; border-radius: 12px; font-size: 18px; font-weight: bold; cursor: pointer; width: 100%; }
        .sold-out { background: #e74c3c; color: white; padding: 12px; border-radius: 10px; font-weight: bold; display: block; }
        .back-link { display: block; margin-top: 20px; color: #3498db; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="booking-card">
    <?php 
    // Affichage du message de succès si la réservation est validée
    if(isset($_GET['success'])) { 
        echo '<div class="success-banner">🎉 Réservation confirmée !</div>';
    } 
    ?>

    <h3><?php echo $event['title']; ?></h3>
    <p class="info">🗓️ <?php echo $event['date_event']; ?></p>
    <p class="info">📍 <?php echo $event['location']; ?></p>

    <div class="price-circle">
        <?php echo $event['price']; ?> DH
    </div>

    <?php 
    // Vérification de la disponibilité pour afficher soit le bouton, soit le message "Sold Out"
    if($event['nbPlaces'] > 0) { 
    ?>
        <p style="font-weight: bold; color: #2ecc71;">Disponibilité: <?php echo $event['nbPlaces']; ?> places</p>
        <br>
        <form method="post">
            <button type="submit" name="ok" class="btn-confirm">Confirmer la réservation</button>
        </form>
    <?php 
    } else { 
    ?>
        <span class="sold-out">🚫 TOUT EST VENDU (SOLD OUT)</span>
    <?php 
    } 
    ?>

    <a href="index.php" class="back-link">⬅ Retour aux événements</a>
</div>

</body>
</html>