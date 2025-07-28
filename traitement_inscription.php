<?php
include('includes/db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password_raw = trim($_POST['password']);

    // Vérification de champs
    if (empty($nom) || empty($email) || empty($password_raw)) {
        echo "Tous les champs sont obligatoires.";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Adresse email invalide.";
        exit;
    }

    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        echo "Cet email est déjà utilisé.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        if ($stmt->execute([$nom, $email, $password])) {
            echo "✅ Inscription réussie ! <a href='login.php'>Connectez-vous</a>";
        } else {
            echo "❌ Une erreur est survenue. Veuillez réessayer.";
        }
    }
}
?>
