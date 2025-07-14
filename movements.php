<?php
include 'includes/header.php';
include 'includes/db.php';

$stmt = $pdo->query("
    SELECT m.*, p.nom as produit_nom
    FROM movements m
    JOIN products p ON m.product_id = p.id
    ORDER BY m.created_at DESC
");
$mouvements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Mouvements de stock</h2>
<a href="add_movement.php">Ajouter un mouvement</a>
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
