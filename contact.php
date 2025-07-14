<?php 
include('includes/db.php');
include('includes/header.php');
?>

<section class="cnt">
    <h1>Contactez-nous</h1>
    <form action="envoyer_message.php" method="post">
        <input type="text" name="nom" placeholder="Votre nom" required>
        <input type="email" name="email" placeholder="Votre e-mail" required>
        <textarea name="message" rows="5" placeholder="Votre message..." required></textarea>
        <button type="submit">Envoyer</button>
    </form>
</section>

<?php include('includes/footer.php'); ?>
