<?php
include('includes/db.php');
$message = '';
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password_raw = trim($_POST['password']);

    if (empty($nom) || empty($email) || empty($password_raw)) {
        $message = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Adresse email invalide.";
    } else {
        $password = password_hash($password_raw, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->rowCount() > 0) {
            $message = "Cet email est déjà utilisé.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$nom, $email, $password])) {
                $message = "Inscription réussie ! <a href='login.php' class='link'>Connectez-vous</a>";
                $success = true;
            } else {
                $message = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Résultat de l'inscription</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f7fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .box {
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            max-width: 500px;
            text-align: center;
        }

        .message {
            font-size: 18px;
            margin: 15px 0;
            color: #212529;
        }

        .message.success {
            color: #198754;
        }

        .message.error {
            color: #dc3545;
        }

        .link {
            color: #0d6efd;
            text-decoration: none;
            font-weight: 500;
        }

        .link:hover {
            color: #0a58ca;
        }

        .icon {
            font-size: 48px;
        }
    </style>
</head>
<body>

<div class="box">
    <?php if ($message): ?>
        <div class="icon"><?= $success ? '✅' : '❌' ?></div>
        <div class="message <?= $success ? 'success' : 'error' ?>">
            <?= $message ?>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
