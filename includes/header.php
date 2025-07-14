<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Stock App</title>
    <link rel="stylesheet" href="css/css2.css">
</head>
<body>

    <header>
    <div class="logo">
        <img src="is-tech.jpeg" alt="is-tech">
    </div>
    <nav>
        <a href="index.php">Accueil</a>
        <a href="products.php">Produits</a>
        <a href="movements.php">Mouvements</a>
        <a href="inscription.php">Inscription</a>
        <a href="contact.php">Contact</a>

        <?php if (!isset($_SESSION['user'])) : ?>
            <a href="login.php">Connexion</a>
        <?php else : ?>
            <a href="logout.php">Déconnexion</a>
        <?php endif; ?>
    </nav>
</header>
<main>
