<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les autres champs
    $nom = $_POST['nom'];
    $description = $_POST['description'];
    $prix = $_POST['prix'];
    $quantite = $_POST['quantite'];
    $seuil = $_POST['seuil'];

    // Traitement de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmp = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $targetDir = "uploads/";
        $targetPath = $targetDir . uniqid() . "_" . $imageName;

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true); // créer le dossier si non existant
        }

        move_uploaded_file($imageTmp, $targetPath);
    } else {
        $targetPath = null; // ou une image par défaut
    }

    // Insertion
    $stmt = $pdo->prepare("INSERT INTO products (nom, description, prix, quantite, seuil_alerte, image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nom, $description, $prix, $quantite, $seuil, $targetPath]);

    header("Location: products.php");
}
?>
