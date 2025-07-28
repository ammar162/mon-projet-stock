<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';
include 'includes/header.php';

// Récupérer les mouvements avec nom et image du produit
$stmt = $pdo->query("
    SELECT m.*, p.nom AS produit_nom, p.image AS produit_image
    FROM stock_movements m
    JOIN products p ON m.product_id = p.id
    ORDER BY m.movement_date DESC
");
$mouvements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    a.add-button {
        background-color: #4CAF50;
        color: white;
        padding: 10px 18px;
        margin: 20px 0;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        display: inline-block;
        transition: background-color 0.3s ease;
    }
    a.add-button:hover {
        background-color: #45a049;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        padding: 10px;
        border: 1px solid #ccc;
        text-align: center;
    }
    th {
        background-color: #343a40;
        color: white;
    }
    img.product-img {
        width: 50px;
        height: auto;
        border-radius: 4px;
    }
</style>

<h2>Mouvements de stock</h2>
<a href="add_movement.php" class="add-button">Ajouter un mouvement</a>

<table>
    <thead>
        <tr>
            <th>Image</th>
            <th>Produit</th>
            <th>Type</th>
            <th>Quantité</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($mouvements as $mv): 
            $imgPath = __DIR__ . '/' . $mv['produit_image'];
        ?>
        <tr>
            <td>
                <?php if (!empty($mv['produit_image']) && file_exists($imgPath)): ?>
                    <img src="<?= htmlspecialchars($mv['produit_image']) ?>" alt="Image produit" class="product-img">
                <?php else: ?>
                    <span style="color: grey;">Aucune</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($mv['produit_nom']) ?></td>
            <td><?= $mv['movement_type'] === 'IN' ? 'Entrée' : 'Sortie' ?></td>
            <td><?= (int)$mv['quantity'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($mv['movement_date'])) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include 'includes/footer.php'; ?>
