<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';
include 'includes/header.php';

// Récupérer la liste des produits pour le formulaire
$products = $pdo->query("SELECT id, nom FROM products ORDER BY nom")->fetchAll(PDO::FETCH_ASSOC);

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $movement_type = $_POST['movement_type'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 0);
    $user_id = $_SESSION['user']['id'];

    // ✅ Ajout de 'rupture' à la validation
    if ($product_id <= 0 || !in_array($movement_type, ['IN', 'OUT', 'rupture']) || $quantity <= 0) {
        $error = "Veuillez remplir correctement tous les champs.";
    } else {
        if ($movement_type === 'OUT') {
            // Vérifier stock suffisant
            $stmt = $pdo->prepare("SELECT quantite FROM products WHERE id = ?");
            $stmt->execute([$product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$product) {
                $error = "Produit invalide.";
            } elseif ($product['quantite'] < $quantity) {
                $error = "Stock insuffisant pour cette sortie.";
            }
        }
    }

    if (!$error) {
        try {
            // Mouvement enregistré
            $stmt = $pdo->prepare("INSERT INTO stock_movements (product_id, user_id, movement_type, quantity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_id, $user_id, $movement_type, $quantity]);

            // Mettre à jour le stock
            if ($movement_type === 'IN') {
                $stmt = $pdo->prepare("UPDATE products SET quantite = quantite + ? WHERE id = ?");
                $stmt->execute([$quantity, $product_id]);
            } elseif ($movement_type === 'OUT') {
                $stmt = $pdo->prepare("UPDATE products SET quantite = quantite - ? WHERE id = ?");
                $stmt->execute([$quantity, $product_id]);
            } elseif ($movement_type === 'rupture') {
                $stmt = $pdo->prepare("UPDATE products SET quantite = 0 WHERE id = ?");
                $stmt->execute([$product_id]);
            }

            header('Location: movements.php');
            exit;

        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout : " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<!-- 🎨 Style CSS identique à ta version -->
<style>
/* ... (tu peux garder ton style CSS précédent) ... */
</style>

<h2>Ajouter un mouvement de stock</h2>

<div class="form">
    <?php if ($error): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <label for="product_id">Produit :</label>
        <select name="product_id" id="product_id" required>
            <option value="">-- Sélectionnez un produit --</option>
            <?php foreach ($products as $p): ?>
                <option value="<?= $p['id'] ?>" <?= (isset($_POST['product_id']) && $_POST['product_id'] == $p['id']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($p['nom']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="movement_type">Type de mouvement :</label>
        <select name="movement_type" id="movement_type" required>
            <option value="">-- Sélectionnez un type --</option>
            <option value="IN" <?= (isset($_POST['movement_type']) && $_POST['movement_type'] === 'IN') ? 'selected' : '' ?>>Entrée</option>
            <option value="OUT" <?= (isset($_POST['movement_type']) && $_POST['movement_type'] === 'OUT') ? 'selected' : '' ?>>Sortie</option>
            <option value="rupture" <?= (isset($_POST['movement_type']) && $_POST['movement_type'] === 'rupture') ? 'selected' : '' ?>>Rupture</option>
        </select>

        <label for="quantity">Quantité :</label>
        <input type="number" name="quantity" id="quantity" min="1" required value="<?= htmlspecialchars($_POST['quantity'] ?? '') ?>">

        <button type="submit">Ajouter</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
