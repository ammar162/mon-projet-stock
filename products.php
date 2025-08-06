<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';
include 'includes/header.php';

// Traitement de la recherche
$search = $_GET['search'] ?? '';
$search = htmlspecialchars($search);

// Requête SQL
if (!empty($search)) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE nom LIKE :search OR description LIKE :search");
    $stmt->execute(['search' => '%' . $search . '%']);
} else {
    $stmt = $pdo->query("SELECT * FROM products");
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Style tableau amélioré -->
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
    }

    .search-bar {
        margin: 20px 0;
    }

    .search-bar input[type="text"] {
        padding: 8px;
        width: 250px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }

    .search-bar button {
        padding: 8px 14px;
        background-color: #007bff;
        border: none;
        color: white;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-ajouter {
        background-color: #3528a7ff;
        color: white;
        padding: 10px 16px;
        border-radius: 5px;
        text-decoration: none;
        display: inline-block;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }

    th {
        background-color: #343a40;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    tr.low-stock {
        background-color: #fff3cd;
    }

    img.product-img {
        width: 60px;
        height: auto;
        border-radius: 4px;
    }

    .btn {
        padding: 6px 10px;
        font-size: 13px;
        border-radius: 4px;
        text-decoration: none;
        margin: 0 2px;
    }

    .btn-modifier {
        background-color: #ffc107;
        color: black;
    }

    .btn-supprimer {
        background-color: #dc3545;
        color: white;
    }

    .stock-alert {
        color: red;
        font-weight: bold;
    }

    img.qr-img {
        width: 60px;
        height: 60px;
    }
</style>

<h2>📦 Liste des produits</h2>

<!-- Formulaire de recherche -->
<form method="GET" class="search-bar">
    <input type="text" name="search" placeholder="🔍 Rechercher un produit..." value="<?= $search ?>">
    <button type="submit">Rechercher</button>
</form>

<a href="add_product.php" class="btn-ajouter">➕ Ajouter un produit</a>

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
            <th>QR Code</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($products) > 0): ?>
            <?php foreach ($products as $product): ?>
                <tr class="<?= ($product['quantite'] <= $product['seuil_alerte']) ? 'low-stock' : '' ?>">
                    <td>
                        <?php if (!empty($product['image']) && file_exists($product['image'])): ?>
                            <img src="<?= htmlspecialchars($product['image']) ?>" alt="Produit" class="product-img">
                        <?php else: ?>
                            <span style="color: grey;">Aucune</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($product['nom']) ?></td>
                    <td><?= htmlspecialchars($product['description']) ?></td>
                    <td><?= number_format($product['prix'], 2) ?> DH</td>
                    <td>
                        <?= (int)$product['quantite'] ?>
                        <?php if ($product['quantite'] <= $product['seuil_alerte']): ?>
                            <div class="stock-alert">⚠ Stock faible</div>
                        <?php endif; ?>
                    </td>
                    <td><?= (int)$product['seuil_alerte'] ?></td>
                    <td>
                        <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn btn-modifier">✏️ Modifier</a>
                        <a href="delete_product.php?id=<?= $product['id'] ?>" class="btn btn-supprimer" onclick="return confirm('Supprimer ce produit ?');">🗑️ Supprimer</a>
                    </td>
                    <td>
                        <img src="generate_qr.php?product_id=<?= $product['id'] ?>" alt="QR Code" class="qr-img">
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8" style="color: grey;">Aucun produit trouvé.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
