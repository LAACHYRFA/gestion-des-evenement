<?php
require 'config.php';
session_start();

// 1. Nejbdou m3lomat l-user (ila m-login)
$user = null;
if(isset($_SESSION['user_id'])){
    $sql = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $sql->execute([$_SESSION['user_id']]);
    $user = $sql->fetch(PDO::FETCH_ASSOC);
}

// 2. Nejbdou l-events
$sql = $pdo->query("SELECT * FROM events");
$events = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Festival Events</title>
    <style>
        /* Hadchi kamel CSS li 3jbni m-khllih kifma houwa */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('assets/festival.jpg'); 
            background-size: cover; background-attachment: fixed;
            font-family: 'Segoe UI', sans-serif; color: white; 
        }
        header { display: flex; justify-content: space-between; padding: 20px 5%; background: rgba(0,0,0,0.5); }
        .logo h2 { color: #3498db; }
        .container { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px; padding: 40px; }
        .event-card { background: white; color: #333; border-radius: 15px; padding: 20px; text-align: center; }
        .price { color: #2ecc71; font-weight: bold; font-size: 20px; }
        .btn { padding: 10px 20px; border-radius: 8px; text-decoration: none; display: inline-block; margin-top: 10px; }
        .btn-blue { background: #3498db; color: white; }
        .btn-red { background: #e74c3c; color: white; }
    </style>
</head>
<body>

    <header>
        <div class="logo"><h2>Fest<span>Events</span></h2></div>
        <div>
            <?php
            if($user){
                echo "<span>Bonjour " . $user['name'] . " </span>";
               echo "<a href='dashboard.php' class='btn btn-blue'>Mes Réservations</a>";
                echo "<a href='logout.php' style='color:red; margin-left:10px;'>Logout</a>";
            } else {
                echo "<a href='login.php' class='btn btn-blue'>Connexion</a>";
            }
            ?>
        </div>
    </header>

    <div class="container">
        <?php
        // Rj3na l-PHP s-sahl dyalna
        foreach($events as $row){
            echo "<div class='event-card'>";
                echo "<h3>" . $row['title'] . "</h3>";
                echo "<p>📍 " . $row['location'] . "</p>";
                echo "<p>🗓️ " . $row['date_event'] . "</p>";
                echo "<p class='price'>" . $row['price'] . " DH</p>";
                echo "<p>🎟️ " . $row['nbPlaces'] . " places</p>";

                if(isset($_SESSION['user_id'])){
                    echo "<a href='booking.php?event_id=" . $row['id'] . "' class='btn btn-red'>Réserver</a>";
                } else {
                    echo "<a href='login.php' class='btn btn-blue'>Login pour réserver</a>";
                }
            echo "</div>";
        }
        ?>
    </div>

</body>
</html>
