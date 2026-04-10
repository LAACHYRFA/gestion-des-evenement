<?php
require "config.php";
session_start();

// 1. Initialisation dyal l-events
$events = [];
$search_query = "";

// 2. Logic dyal l-Search
if(isset($_POST['ok']) && !empty($_POST['Search'])){
    $search_query = $_POST['Search'];
    $stmt = $pdo->prepare("SELECT * FROM events WHERE title LIKE ? ORDER BY date_event DESC");
    $stmt->execute(["%$search_query%"]);
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Ila madihach Search, jbed kolchi
    $sql = $pdo->query("SELECT * FROM events ORDER BY date_event DESC");
    $events = $sql->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Festival</title>
    <style>
        /* Global Reset */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            background: #f4f7f6; 
            font-family: 'Segoe UI', sans-serif; 
            color: #333;
        }

        /* Top Header */
        header {
            background: #2c3e50;
            color: white;
            padding: 20px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header h1 span { color: #3498db; }

        .btn-add {
            background: #2ecc71;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }

        /* Search Section */
        .search-bar {
            background: white;
            padding: 30px;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        input[name="Search"] {
            width: 350px;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            outline: none;
        }

        button[name="ok"] {
            padding: 12px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        /* Events Layout */
        .admin-container {
            padding: 40px 5%;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .event-card {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border-top: 5px solid #3498db;
        }

        .event-card h3 { color: #2c3e50; margin-bottom: 10px; }
        .event-card p { margin-bottom: 5px; color: #666; font-size: 14px; }
        
        .status-ok { color: #2ecc71; font-weight: bold; }
        .status-sold { color: #e74c3c; font-weight: bold; }

        .no-results {
            text-align: center;
            grid-column: 1 / -1;
            padding: 50px;
            color: #e74c3c;
        }
    </style>
</head>
<body>

    <header>
        <h1>Admin<span>Panel</span></h1>
        <a href="add_event.php" class="btn-add">+ Ajouter un Événement</a>
    </header>

    <div class="search-bar">
        <form method="post">
            <input type="text" name="Search" placeholder="Rechercher un événement..." value="<?= htmlspecialchars($search_query) ?>">
            <button type="submit" name="ok">Rechercher</button>
        </form>
    </div>

    <div class="admin-container">
        <?php if(count($events) > 0): ?>
            <?php foreach($events as $event): ?>
                <div class="event-card">
                    <h3><?= htmlspecialchars($event['title']) ?></h3>
                    <p>🗓️ <?= $event['date_event'] ?></p>
                    <p>💰 <?= $event['price'] ?> DH</p>
                    <p>📍 <?= htmlspecialchars($event['location']) ?></p>
                    
                    <?php if($event['nbPlaces'] > 0): ?>
                        <p class="status-ok">🎟️ Places: <?= $event['nbPlaces'] ?></p>
                    <?php else: ?>
                        <p class="status-sold">🚫 SOLD OUT</p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-results">
                <h3>Aucun événement trouvé pour "<?= htmlspecialchars($search_query) ?>"</h3>
            </div>
        <?php endif; ?>
    </div>

</body>
</html>