<?php
require "config.php";
session_start();

$error = "";
$success = "";

if (isset($_POST['add'])) {
    $title = $_POST['title'];
    $date_event = $_POST['date_event'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $nbPlaces = $_POST['nbPlaces'];

    // 1. Check empty fields
    if (empty($title) || empty($date_event) || empty($price) || empty($location) || empty($nbPlaces)) {
        $error = "Veuillez remplir tous les champs.";
    } 
    // 2. Check numeric (Location khassha tkon text, mashi numeric!)
    elseif (!is_numeric($price) || !is_numeric($nbPlaces)) {
        $error = "Le prix et le nombre de places doivent être des nombres.";
    } 
    else {
        // 3. Insertion
        $sql = $pdo->prepare("INSERT INTO events (title, date_event, price, location, nbPlaces) VALUES (?, ?, ?, ?, ?)");
        $result = $sql->execute([$title, $date_event, $price, $location, $nbPlaces]);

        if ($result) {
            $success = "Événement ajouté avec succès !";
            // Optional: Redirect to admin after 2 seconds
            header("refresh:2;url=admin.php");
        } else {
            $error = "Erreur lors de l'ajout.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Événement</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            background: #f4f7f6; 
            font-family: 'Segoe UI', sans-serif; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

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

        .msg { padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold; }
        .error { background: #fadbd8; color: #e74c3c; }
        .success { background: #d4edda; color: #155724; }

        label { display: block; margin-bottom: 5px; color: #666; font-size: 14px; }
        
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
        }

        input:focus { border-color: #3498db; }

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

        button:hover { background: #2980b9; transform: translateY(-2px); }

        .back-link { display: block; text-align: center; margin-top: 20px; color: #95a5a6; text-decoration: none; }
    </style>
</head>
<body>

    <div class="form-card">
        <h2>Ajouter <span>Événement</span></h2>

        <?php if($error): ?> <div class="msg error"><?= $error ?></div> <?php endif; ?>
        <?php if($success): ?> <div class="msg success"><?= $success ?></div> <?php endif; ?>

        <form method="post">
            <label>Titre de l'événement</label>
            <input type="text" name="title" placeholder="Ex: Festival de Jazz" required>

            <label>Date</label>
            <input type="date" name="date_event" required>

            <label>Prix (DH)</label>
            <input type="number" name="price" placeholder="0.00" step="0.01" required>

            <label>Lieu (Location)</label>
            <input type="text" name="location" placeholder="Ex: Casablanca" required>

            <label>Nombre de places</label>
            <input type="number" name="nbPlaces" placeholder="Ex: 100" required>

            <button type="submit" name="add">Ajouter l'événement</button>
        </form>

        <a href="admin.php" class="back-link">⬅ Retour à l'administration</a>
    </div>

</body>
</html>