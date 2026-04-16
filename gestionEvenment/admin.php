<?php
// Inclusion du fichier de configuration pour la connexion à la base de données (PDO)
require "config.php";

// Démarrage de la session utilisateur
session_start();

// Initialisation du tableau des événements et de la variable de recherche
$events = [];
$search = "";

// Vérification si le formulaire de recherche a été validé et n'est pas vide
if(isset($_POST['ok']) && !empty($_POST['Search'])){
    // Récupération de la saisie utilisateur
    $search = $_POST['Search']; 
    
    // Préparation de la requête SQL avec un filtre sur le titre (Utilisation de LIKE pour une recherche partielle)
    $stmt = $pdo->prepare("SELECT * FROM events WHERE title LIKE ? ORDER BY date_event DESC");
    
    // Exécution de la requête en ajoutant les jokers % pour chercher partout dans le texte
    $stmt->execute(["%$search%"]);
    
    // Récupération de tous les résultats correspondants sous forme de tableau associatif
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si aucune recherche n'est effectuée, on récupère la totalité des événements
    // Classés du plus récent au plus ancien (ORDER BY DESC)
    $sql = $pdo->query("SELECT * FROM events ORDER BY date_event DESC");
    $events = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel - Festival</title>
    <style>
        /* Styles CSS pour la mise en page de l'interface admin */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #f4f7f6; font-family: 'Segoe UI', sans-serif; color: #333; }
        header { background: #2c3e50; color: white; padding: 20px 5%; display: flex; justify-content: space-between; align-items: center; }
        header h1 span { color: #3498db; }
        .btn-add { background: #2ecc71; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: bold; }
        .search-bar { background: white; padding: 30px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        input[name="Search"] { width: 350px; padding: 12px; border: 2px solid #ddd; border-radius: 8px; }
        button[name="ok"] { padding: 12px 20px; background: #3498db; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; }
        .admin-container { padding: 40px 5%; display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .event-card { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 5px solid #3498db; }
        .status-ok { color: #2ecc71; font-weight: bold; }
        .status-sold { color: #e74c3c; font-weight: bold; }
    </style>
</head>
<body>

    <header>
        <h1>Admin<span>Panel</span></h1>
        <a href="add_event.php" class="btn-add">+ Ajouter</a>
    </header>

    <?php
// Affichage de la barre de recherche via PHP
echo '<div class="search-bar">
        <form method="post">
            <input type="text" name="Search" placeholder="Rechercher..." value="' . $search . '">
            <button type="submit" name="ok">Rechercher</button>
        </form>
      </div>';

// Début du conteneur principal pour les cartes d'événements
echo '<div class="admin-container">';

// Vérification si le tableau d'événements contient des données
if (count($events) > 0) {
    // Boucle pour parcourir chaque événement et générer une carte HTML
    foreach ($events as $event) {
        echo '<div class="event-card">
                <h3>' . $event['title'] . '</h3>
                <p>🗓️ ' . $event['date_event'] . '</p>
                <p>💰 ' . $event['price'] . ' DH</p>
                <p>📍 ' . $event['location'] . '</p>';

        // Vérification de la disponibilité des places
        if ($event['nbPlaces'] > 0) {
            echo '<p class="status-ok">🎟️ Places: ' . $event['nbPlaces'] . '</p>';
        } else {
            // Affichage d'un statut "Sold Out" si les places sont à zéro
            echo '<p class="status-sold">🚫 SOLD OUT</p>';
        }

        echo '</div>'; // Fermeture de la carte
    }
} else {
    // Message affiché si aucun événement ne correspond à la recherche ou si la base est vide
    echo '<div style="grid-column: 1/-1; text-align: center; color: red;">
            <h3>Cet événement n\'est pas disponible.</h3>
          </div>';
}

echo '</div>'; // Fermeture du conteneur principal
?>

</body>
</html>