<?php
include('includes/db.php');
include('includes/header.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars(trim($_POST['nom']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Enregistrement du message dans la base de données
    $stmt = $pdo->prepare("INSERT INTO messages (nom, email, message) VALUES (?, ?, ?)");
    if ($stmt->execute([$nom, $email, $message])) {
        echo "Message envoyé avec succès ! Merci de nous avoir contactés.";
    } else {
        echo "Erreur lors de l'envoi du message.";
    }
}

include('includes/footer.php');
?>
