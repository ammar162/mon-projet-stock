<?php 
include('includes/db.php');
include('includes/header.php');
?>

<section class="ent">
<h1>Créer un compte</h1>

<form action="traitement_inscription.php" method="post">
    <input type="text" name="nom" placeholder="Nom complet" required>
    <input type="email" name="email" placeholder="Adresse e-mail" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>
</section>
<?php include('includes/footer.php'); ?>
