<?php include 'includes/header.php'; ?>

<h2>Ajouter un produit</h2>
<div class="form">
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="nom" placeholder="Nom" required><br>
        <textarea name="description" placeholder="Description"></textarea><br>
        <input type="number" step="0.01" name="prix" placeholder="Prix" required><br>
        <input type="number" name="quantite" placeholder="Quantité" required><br>
        <input type="number" name="seuil_alerte" placeholder="Seuil d'alerte" required><br>
        
        <!-- Champ image -->
        <label for="image">Image du produit :</label>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit">Enregistrer</button>
    </form>
</div>

<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $description = trim($_POST['description']);
    $prix = floatval($_POST['prix']);
    $quantite = intval($_POST['quantite']);
    $seuil_alerte = intval($_POST['seuil_alerte']);

    // Traitement image
    $imagePath = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $targetDir = 'uploads/';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true); // crée le dossier s'il n'existe pas
        }

        $imagePath = $targetDir . uniqid() . '_' . $imageName;
        move_uploaded_file($imageTmp, $imagePath);
    }

    // Validation
    if (!empty($nom) && $prix > 0 && $quantite >= 0 && $seuil_alerte >= 0) {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO products (nom, description, prix, quantite, seuil_alerte, image)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$nom, $description, $prix, $quantite, $seuil_alerte, $imagePath]);

            header('Location: products.php');
            exit;
        } catch (PDOException $e) {
            echo "<p>Erreur lors de l'ajout du produit : " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    } else {
        echo "<p>Veuillez remplir correctement tous les champs.</p>";
    }
}
?>

<?php include 'includes/footer.php'; ?>
