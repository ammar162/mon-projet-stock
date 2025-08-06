<?php
// Activer l'affichage des erreurs (développement uniquement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Connexion à la base de données
include 'includes/db.php';

// Vérification que l'ID du produit est bien présent dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $productId = $_GET['id'];

    // Récupération du produit depuis la base de données
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$productId]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product):
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du produit</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f2f2f2;
            padding: 30px;
            direction: ltr;
            text-align: left;
        }

        .product-container {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .product-container img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .product-container h2 {
            color: #333;
        }

        .product-container p {
            font-size: 16px;
            color: #555;
            margin: 8px 0;
        }

        .price {
            color: #007bff;
            font-weight: bold;
            font-size: 18px;
        }

        .btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .back-link {
            color: #007bff;
        }

        .edit-link {
            background-color: #ffc107;
            color: #000;
        }

        .qr-link {
            background-color: #28a745;
            color: white;
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }
            .product-container {
                padding: 20px;
                font-size: 15px;
            }
            .btn {
                width: 100%;
                text-align: center;
                margin-top: 10px;
            }
        }
    </style>
</head>
<body>

<div class="product-container">
    <?php if (!empty($product['image']) && file_exists($product['image'])): ?>
        <img src="<?= htmlspecialchars($product['image']) ?>" alt="Image du produit">
    <?php endif; ?>

    <h2><?= htmlspecialchars($product['nom']) ?></h2>
    <p>📄 <strong>Description :</strong> <?= htmlspecialchars($product['description']) ?></p>
    <p class="price">💰 <strong>Prix :</strong> <?= number_format($product['prix'], 2) ?> MAD</p>
    <p>📦 <strong>Quantité disponible :</strong> <?= (int)$product['quantite'] ?></p>

    <!-- Lien retour -->
    <a href="products.php" class="btn back-link">⬅️ Retour à la liste des produits</a>

    <!-- Lien modifier -->
    <a href="edit_product.php?id=<?= $product['id'] ?>" class="btn edit-link">✏️ Modifier le produit</a>

    <!-- Lien QR -->
    <a href="generate_qr.php?product_id=<?= $product['id'] ?>" target="_blank" class="btn qr-link">🖨️ Afficher le QR Code</a>
</div>

</body>
</html>

<?php
    else:
        echo "<p style='color:red; text-align:center;'>⚠️ Produit non trouvé.</p>";
    endif;
} else {
    echo "<p style='color:red; text-align:center;'>⚠️ Identifiant du produit invalide ou manquant.</p>";
}
?>
