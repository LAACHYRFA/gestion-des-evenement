<?php
require("config.php");
session_start();

// 1. Nakhou l-ID (ghadi nkhaliwha direct bach tji sahla)
if(!isset($_GET['user_id'])){
    header("Location: index.php");
    exit();
}
$userId = $_GET['user_id'];

// 2. Query simple
$sql = "SELECT users.name, users.email, events.title, reservations.id 
        FROM reservations 
        INNER JOIN users ON reservations.user_id = users.id
        INNER JOIN events ON reservations.event_id = events.id
        WHERE reservations.user_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$all_res = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Réservations</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #222; color: white; padding: 50px; }
        .dashboard-card { background: white; color: #333; max-width: 800px; margin: auto; padding: 20px; border-radius: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background: #3498db; color: white; }
        .btn-back { display: inline-block; margin-top: 20px; color: #3498db; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="dashboard-card">
    <h2>Mes Réservations</h2>

    <?php 
  
if (count($all_res) > 0) {
    
    echo "<table>
           
                <tr>
                    <th>N°</th>
                    <th>Événement</th>
                    <th>Nom</th>
                    <th>Email</th>
                </tr>";

    foreach ($all_res as $res) {
        echo "<tr>
                <td> " . $res['id'] . "</td>
                <td><strong>" . $res['title'] . "</strong></td>
                <td>" . $res['name'] . "</td>
                <td>" . $res['email'] . "</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p>ya pas de reservation.</p>";
}
?>

<div style="text-align: center;">
    <a href="index.php" class="btn-back">⬅ Retour à l'accueil</a>
</div>