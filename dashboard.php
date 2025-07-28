<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<?php
include 'includes/auth.php';
include 'includes/header.php';
include 'includes/db.php';

// Compter tous les produits
$count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// Produits en rupture de stock
$rupture = $pdo->query("SELECT COUNT(*) FROM products WHERE quantite = 0")->fetchColumn();

// Produits avec stock faible
$faible = $pdo->query("SELECT COUNT(*) FROM products WHERE quantite < seuil_alerte AND quantite > 0")->fetchColumn();

// Derniers mouvements de stock, avec image produit
$stmt = $pdo->query("
    SELECT m.*, p.nom AS produit_nom, p.image AS produit_image
    FROM stock_movements m 
    JOIN products p ON m.product_id = p.id 
    ORDER BY m.movement_date DESC 
    LIMIT 5
");
$mouvements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Dashboard</h2>
<ul>
    <li>Total produits : <?= $count ?></li>
    <li>Produits en rupture : <?= $rupture ?></li>
    <li>Produits avec stock faible : <?= $faible ?></li>
</ul>

<h3>Derniers mouvements</h3>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>Image</th>
        <th>Produit</th>
        <th>Type</th>
        <th>Quantité</th>
        <th>Date</th>
    </tr>
    <?php foreach ($mouvements as $mv): ?>
        <tr>
            <td>
                <?php if (!empty($mv['produit_image']) && file_exists($mv['produit_image'])): ?>
                    <img src="<?= htmlspecialchars($mv['produit_image']) ?>" alt="Image produit" style="width:50px; height:auto; border-radius:4px;">
                <?php else: ?>
                    <span style="color: grey;">Aucune</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($mv['produit_nom']) ?></td>
            <td><?= htmlspecialchars($mv['movement_type']) ?></td>
            <td><?= htmlspecialchars($mv['quantity']) ?></td>
            <td><?= htmlspecialchars($mv['movement_date']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include 'includes/footer.php'; ?>
