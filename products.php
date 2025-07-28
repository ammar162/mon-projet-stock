<?php
session_start();

// Rediriger si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php';
include 'includes/db.php';

// Récupérer tous les produits
$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Style CSS intégré -->
<style>
    body {
        font-family: Arial, sans-serif;
        padding: 20px;
    }
    .btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 14px;
        text-decoration: none;
        display: inline-block;
        margin: 2px;
    }
    .btn-ajouter {
        background-color: #007bff;
        color: white;
    }
    .btn-ajouter:hover {
        background-color: #0069d9;
    }
    .btn-modifier {
        background-color: #ffc107;
        color: black;
    }
    .btn-modifier:hover {
        background-color: #e0a800;
    }
    .btn-supprimer {
        background-color: #dc3545;
        color: white;
    }
    .btn-supprimer:hover {
        background-color: #c82333;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    table th, table td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: center;
    }
    table th {
        background-color: #343a40;
        color: white;
    }
    img {
        width: 60px;
        height: auto;
        border-radius: 4px;
    }
</style>

<h2>Liste des produits</h2>

<a href="add_product.php" class="btn btn-ajouter">➕ Ajouter un produit</a>

<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Seuil</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td>
                        <?php if (!empty($product['image']) && file_exists($product['image'])): ?>
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="Image produit">
                        <?php else: ?>
                            <span style="color: grey;">Aucune</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($product['nom']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= number_format($product['prix'], 2) ?> DH</td>
                    <td><?= (int)$product['quantite'] ?></td>
                    <td><?= (int)$product['seuil_alerte'] ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-modifier">✏️ Modifier</a>
                        <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-supprimer" onclick="return confirm('Supprimer ce produit ?');">🗑️ Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="color: grey;">Aucun produit trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
