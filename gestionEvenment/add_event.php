<?php
// Inclusion du fichier de configuration pour la connexion à la base de données
require "config.php";

// Démarrage de la session pour gérer les variables de session si nécessaire
session_start();

// Initialisation des messages d'erreur et de succès
$error = "";
$success = "";

// Vérification si le formulaire a été soumis via le bouton 'add'
if (isset($_POST['add'])) {
    // Récupération des données envoyées par le formulaire
    $title = $_POST['title'];
    $date_event = $_POST['date_event'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $nbPlaces = $_POST['nbPlaces'];

    // 1. Vérification si tous les champs sont remplis
    if (empty($title) || empty($date_event) || empty($price) || empty($location) || empty($nbPlaces)) {
        $error = "Veuillez remplir tous les champs.";
    } 
    // 2. Vérification si le prix et le nombre de places sont bien des valeurs numériques
    elseif (!is_numeric($price) || !is_numeric($nbPlaces)) {
        $error = "Le prix et le nombre de places doivent être des nombres.";
    } 
    else {
        // 3. Préparation de la requête SQL pour insérer l'événement (Utilisation de marqueurs '?' pour la sécurité)
        $sql = $pdo->prepare("INSERT INTO events (title, date_event, price, location, nbPlaces) VALUES (?, ?, ?, ?, ?)");
        
        // Exécution de la requête avec les données récupérées
        $result = $sql->execute([$title, $date_event, $price, $location, $nbPlaces]);

        // Vérification si l'insertion a réussi
        if ($result) {
            $success = "Événement ajouté avec succès !";
            // Redirection optionnelle vers la page d'administration après 2 secondes
            // header("refresh:2;url=admin.php");
        } else {
            $error = "Erreur lors de l'ajout à la base de données.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Événement</title>
    <style>
        /* Styles globaux pour la mise en page */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background: #f4f7f6; 
            font-family: 'Segoe UI', sans-serif; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
        }
        /* Style de la carte contenant le formulaire */
        .form-card { 
            background: white; 
            padding: 40px; 
            border-radius: 20px; 
            box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
            width: 100%; 
            max-width: 500px; 
        }
        h2 { text-align: center; color: #2c3e50; margin-bottom: 30px; }
        h2 span { color: #3498db; }
        /* Style des messages d'alerte (Erreur/Succès) */
        .msg { padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .error { background: #fadbd8; color: #e74c3c; border: 1px solid #e74c3c; }
        .success { background: #d4edda; color: #155724; border: 1px solid #155724; }
        
        /* Style des champs de saisie */
        label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ddd; border-radius: 8px; outline: none; }
        /* Style du bouton de validation */
        button { 
            width: 100%; 
            padding: 15px; 
            background: #3498db; 
            color: white; 
            border: none; 
            border-radius: 8px; 
            font-size: 18px; 
            font-weight: bold; 
            cursor: pointer; 
            transition: 0.3s; 
        }
        button:hover { background: #2980b9; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #95a5a6; text-decoration: none; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>Nouveau <span>Événement</span></h2>

        <?php 
        // Affichage du message d'erreur si la variable $error n'est pas vide
        if($error != "") { 
            echo '<div class="msg error">' . $error . '</div>'; 
        } 

        // Affichage du message de succès si la variable $success n'est pas vide
        if($success != "") { 
            echo '<div class="msg success">' . $success . '</div>'; 
        } 
        ?>

        <form method="post" action="">
            <label>Titre de l'événement</label>
            <input type="text" name="title" placeholder="Ex: Festival de Jazz">

            <label>Date</label>
            <input type="date" name="date_event">

            <label>Prix (DH)</label>
            <input type="text" name="price" placeholder="0.00">

            <label>Lieu (Location)</label>
            <input type="text" name="location" placeholder="Ex: Casablanca">

            <label>Nombre de places</label>
            <input type="text" name="nbPlaces" placeholder="Ex: 100">

            <button type="submit" name="add">Ajouter l'événement</button>
        </form>

        <a href="admin.php" class="back-link">⬅ Retour à l'administration</a>
    </div>

</body>
</html>