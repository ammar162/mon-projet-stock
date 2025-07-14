<?php
include 'includes/header.php';
include 'includes/db.php';

// Compter produits
$count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

// Produits en rupture
$rupture = $pdo->query("SELECT COUNT(*) FROM products WHERE quantite = 0")->fetchColumn();

// Produits stock faible
$faible = $pdo->query("SELECT COUNT(*) FROM products WHERE quantite < seuil_alerte AND quantite > 0")->fetchColumn();

// Derniers mouvements
$stmt = $pdo->query("SELECT m.*, p.nom as produit_nom FROM movements m JOIN products p ON m.product_id = p.id ORDER BY m.created_at DESC LIMIT 5");
$mouvements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Dashboard</h2>
<ul>
    <li>Total produits : <?= $count ?></li>
    <li>Produits en rupture : <?= $rupture ?></li>
    <li>Produits stock faible : <?= $faible ?></li>
</ul>

<h3>Derniers mouvements</h3>
<table border="1">
    <tr>
        <th>Produit</th>
        <th>Type</th>
        <th>Quantité</th>
        <th>Raison</th>
        <th>Date</th>
    </tr>
    <?php foreach ($mouvements as $mv): ?>
        <tr>
            <td><?= htmlspecialchars($mv['produit_nom']) ?></td>
            <td><?= htmlspecialchars($mv['type']) ?></td>
            <td><?= htmlspecialchars($mv['quantite']) ?></td>
            <td><?= htmlspecialchars($mv['raison']) ?></td>
            <td><?= htmlspecialchars($mv['created_at']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include 'includes/footer.php'; ?>
