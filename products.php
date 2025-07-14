<?php
include 'includes/header.php';
include 'includes/db.php';

$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Produits</h2>
<a href="add_product.php">Ajouter un produit</a>
<table border="1">
    <tr>
        <th>Nom</th>
        <th>Description</th>
        <th>Prix</th>
        <th>Quantité</th>
        <th>Seuil d'alerte</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= htmlspecialchars($p['nom']) ?></td>
            <td><?= htmlspecialchars($p['description']) ?></td>
            <td><?= $p['prix'] ?> DH</td>
            <td><?= $p['quantite'] ?></td>
            <td><?= $p['seuil_alerte'] ?></td>
            <td>
                <a href="edit_product.php?id=<?= $p['id'] ?>">Modifier</a> |
                <a href="delete_product.php?id=<?= $p['id'] ?>">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php include 'includes/footer.php'; ?>
