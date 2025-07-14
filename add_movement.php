<?php
include 'includes/header.php';
include 'includes/db.php';

$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("
        INSERT INTO movements (product_id, user_id, type, quantite, raison)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_POST['product_id'],
        $_SESSION['user']['id'],
        $_POST['type'],
        $_POST['quantite'],
        $_POST['raison']
    ]);

    // Mise à jour du stock
    if ($_POST['type'] === 'entrée') {
        $pdo->prepare("UPDATE products SET quantite = quantite + ? WHERE id = ?")
            ->execute([$_POST['quantite'], $_POST['product_id']]);
    } else {
        $pdo->prepare("UPDATE products SET quantite = quantite - ? WHERE id = ?")
            ->execute([$_POST['quantite'], $_POST['product_id']]);
    }

    header('Location: movements.php');
    exit;
}
?>

<h2>Ajouter un mouvement</h2>
<form action="" method="post">
    <select name="product_id" required>
        <option value="">-- Choisir un produit --</option>
        <?php foreach ($products as $p): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nom']) ?></option>
        <?php endforeach; ?>
    </select><br>
    <select name="type" required>
        <option value="entrée">Entrée</option>
        <option value="sortie">Sortie</option>
    </select><br>
    <input type="number" name="quantite" placeholder="Quantité" required><br>
    <input type="text" name="raison" placeholder="Raison" required><br>
    <button type="submit">Ajouter</button>
</form>

<?php include 'includes/footer.php'; ?>
