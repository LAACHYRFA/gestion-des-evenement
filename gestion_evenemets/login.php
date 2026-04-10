<?php
require "config.php";
session_start();

$error = ""; 

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {
        $error = "Veuillez remplir tous les champs";
    } else {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                header('Location: index.php');
                exit;
            } else {
                $error = "Mot de passe incorrect";
            }
        } else {
            $error = "Email incorrect";
        }
    } 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Festival Events</title>
    <style>
        /* 1. Reset Global */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                        url('assets/festival.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        /* 2. L-Card li kat-bayyen l-form */
        .auth-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h3 { margin-bottom: 25px; color: #333; font-size: 24px; }
        h3 span { color: #3498db; }

        .error-msg {
            color: #e74c3c;
            background: #fadbd8;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: bold;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #555;
            font-weight: 600;
            font-size: 14px;
        }

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
            padding: 12px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover { background: #2980b9; transform: translateY(-2px); }

        .auth-footer { margin-top: 20px; color: white; }
        .auth-footer a { color: #f1c40f; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>

    <div class="auth-card">
        <h3>Welcome</h3>
        
        <?php if($error !== ""): ?>
            <div class="error-msg"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <label>Email</label>
            <input type="email" name="email" placeholder="votre@email.com" required>
            
            <label>Password</label>
            <input type="password" name="password" placeholder="••••••••" required>
            
            <button type="submit">Login Now</button>
        </form>
    </div>

    <p class="auth-footer">
        Pas encore inscrit ? <a href="singUp.php">Créez un compte</a>
    </p>

</body>
</html>