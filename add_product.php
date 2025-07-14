<?php include 'includes/header.php'; ?>

<h2>Ajouter un produit</h2>
<form action="add_product.php" method="post">
    <input type="text" name="nom" placeholder="Nom" required><br>
    <textarea name="description" placeholder="Description"></textarea><br>
    <input type="number" step="0.01" name="prix" placeholder="Prix" required><br>
    <input type="number" name="quantite" placeholder="Quantité" required><br>
    <input type="number" name="seuil_alerte" placeholder="Seuil d'alerte" required><br>
    <button type="submit">Enregistrer</button>
</form>

<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO products (nom, description, prix, quantite, seuil_alerte) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([
        $_POST['nom'],
        $_POST['description'],
        $_POST['prix'],
        $_POST['quantite'],
        $_POST['seuil_alerte']
    ]);
    header('Location: products.php');
    exit;
}
?>

<?php include 'includes/footer.php'; ?>
