<?php
require("config.php");
session_start();

// 1. Kan-akhdo l-ID mn l-URL
if(!isset($_GET['user_id'])){
    header("Location: index.php");
    exit();
}
$userId = $_GET['user_id'];

// 2. Query kat-jbed l-m3lomat
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
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('assets/festival.jpg') no-repeat center center fixed; 
            background-size: cover;
            font-family: 'Segoe UI', sans-serif; color: white;
            padding: 50px 20px;
        }

        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            color: #333;
            max-width: 900px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        h2 { margin-bottom: 20px; color: #2c3e50; text-align: center; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th { background-color: #3498db; color: white; border-radius: 5px 5px 0 0; }

        tr:hover { background-color: #f1f1f1; }

        .btn-back {
            display: inline-block;
            text-decoration: none;
            background: #3498db;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }

        .btn-back:hover { background: #2980b9; }

        .no-res { text-align: center; padding: 20px; color: #666; }
    </style>
</head>
<body>

<div class="dashboard-card">
    <h2>Mes Réservations</h2>

    <?php if(count($all_res) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>N° Réservation</th>
                    <th>Événement</th>
                    <th>Nom Client</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($all_res as $res): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($res['id']) ?></td>
                        <td><strong><?= htmlspecialchars($res['title']) ?></strong></td>
                        <td><?= htmlspecialchars($res['name']) ?></td>
                        <td><?= htmlspecialchars($res['email']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-res">Vous n'avez pas encore de réservations.</p>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href='index.php' class="btn-back">⬅ Retour à l'accueil</a>
    </div>
</div>

</body>
</html>