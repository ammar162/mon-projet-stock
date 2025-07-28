<?php
include 'includes/header.php';
include 'includes/db.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE products SET nom=?, description=?, prix=?, quantite=?, seuil_alerte=? WHERE id=?");
    $stmt->execute([
        $_POST['nom'],
        $_POST['description'],
        $_POST['prix'],
        $_POST['quantite'],
        $_POST['seuil_alerte'],
        $id
    ]);
    header('Location: products.php');
    exit;
}
?>

<h2>Modifier le produit</h2>
<div class="form">
<form action="" method="post">
    <input type="text" name="nom" value="<?= htmlspecialchars($product['nom']) ?>" required><br>
    <textarea name="description"><?= htmlspecialchars($product['description']) ?></textarea><br>
    <input type="number" step="0.01" name="prix" value="<?= $product['prix'] ?>" required><br>
    <input type="number" name="quantite" value="<?= $product['quantite'] ?>" required><br>
    <input type="number" name="seuil_alerte" value="<?= $product['seuil_alerte'] ?>" required><br>
    <button type="submit">Enregistrer</button>
</form>
</div>
<?php include 'includes/footer.php'; ?>
