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

    // Validation simple
    if ($product_id <= 0 || !in_array($movement_type, ['IN', 'OUT']) || $quantity <= 0) {
        $error = "Veuillez remplir correctement tous les champs.";
    } else {
        // Si sortie, vérifier stock suffisant
        if ($movement_type === 'OUT') {
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
            $stmt = $pdo->prepare("INSERT INTO stock_movements (product_id, user_id, movement_type, quantity) VALUES (?, ?, ?, ?)");
            $stmt->execute([$product_id, $user_id, $movement_type, $quantity]);

            if ($movement_type === 'IN') {
                $stmt = $pdo->prepare("UPDATE products SET quantite = quantite + ? WHERE id = ?");
            } else {
                $stmt = $pdo->prepare("UPDATE products SET quantite = quantite - ? WHERE id = ?");
            }
            $stmt->execute([$quantity, $product_id]);

            // Redirection après succès
            header('Location: movements.php');
            exit;

        } catch (PDOException $e) {
            $error = "Erreur lors de l'ajout : " . htmlspecialchars($e->getMessage());
        }
    }
}
?>

<style>
.form {
  max-width: 500px;
  margin: 60px auto;
  background: #fff;
  padding: 30px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}
.form form {
  display: flex;
  flex-direction: column;
}
.form select,
.form input {
  padding: 14px 16px;
  margin-bottom: 15px;
  font-size: 16px;
  border: 1px solid #ccc;
  border-radius: 12px;
  transition: border 0.3s ease, box-shadow 0.3s ease;
  background-color: #f9f9f9;
}
.form select:focus,
.form input:focus {
  border-color: #6a5acd;
  box-shadow: 0 0 8px rgba(106, 90, 205, 0.2);
  background-color: #fff;
  outline: none;
}
.form button {
  padding: 14px;
  font-size: 17px;
  background: #6a5acd;
  color: white;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  transition: background 0.3s ease, transform 0.2s ease;
}
.form button:hover {
  background: #5a4bb3;
  transform: scale(1.02);
}
.form button:active {
  transform: scale(0.97);
}
.error-message {
    color: red;
    font-weight: bold;
    margin-bottom: 20px;
}
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
        </select>

        <label for="quantity">Quantité :</label>
        <input type="number" name="quantity" id="quantity" min="1" required value="<?= htmlspecialchars($_POST['quantity'] ?? '') ?>">

        <button type="submit">Ajouter</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
