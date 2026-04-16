<?php
session_start();
require "config.php";

$error = ""; // Bach n-jm3o l-akhata' hna

if($_SERVER['REQUEST_METHOD'] === "POST"){
    $name = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $pass_confirmation = $_POST['password_confirmation'];

    if(empty($name) || empty($email) || empty($password) || empty($pass_confirmation)){
        $error = "Veuillez remplir tous les champs.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Email pas valide !";
    } elseif(strlen($password) < 8){
        $error = "Le mot de passe doit avoir au moins 8 caractères.";
    } elseif($password !== $pass_confirmation){
        $error = "La confirmation ne correspond pas.";
    } elseif(!preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)){
        $error = "Le mot de passe doit contenir une majuscule et un chiffre.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (name, email, password) VALUES (:nom, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom'      => $name,
            ':email'    => $email,
            ':password' => $hashedPassword
        ]);
        header("Location: login.php?msg=success");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('assets/festival.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        h3 { margin-bottom: 25px; color: #333; text-align: center; }
        .error-msg { color: #e74c3c; margin-bottom: 15px; text-align: center; font-size: 14px; font-weight: bold; }
        label { display: block; margin-bottom: 5px; color: #555; font-size: 14px; text-align: left; }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
        }
        input:focus { border-color: #3498db; }
        button {
            width: 100%;
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover { background: #2980b9; }
        .footer-link { margin-top: 20px; text-align: center; font-size: 14px; }
        .footer-link a { color: #3498db; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

<div class="auth-card">
    <h3>Create a New <span style="color: #e74c3c;">Account</span></h3>
    
    <?php if($error): ?>
        <div class="error-msg"><?= $error ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label>Name</label>
        <input type="text" name="nom" placeholder="Votre nom" required>
        
        <label>Email</label>
        <input type="email" name="email" placeholder="votre@email.com" required>
        
        <label>Password</label>
        <input type="password" name="password" placeholder="••••••••" required>
        
        <label>Confirm Password</label>
        <input type="password" name="password_confirmation" placeholder="••••••••" required>
        
        <button type="submit">Sign Up</button>
    </form>
    
    <div class="footer-link">
        Déjà inscrit ? <a href="login.php">Se connecter</a>
    </div>
</div>

</body>
</html>