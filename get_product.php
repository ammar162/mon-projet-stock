<?php
header('Content-Type: application/json');
require_once 'includes/db.php'; // ajuster le chemin selon la structure

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    echo json_encode([
        'success' => false,
        'message' => 'ID invalide ou manquant'
    ]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT nom, description, prix, quantite FROM products WHERE id = ?");
    $stmt->execute([$id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        echo json_encode([
            'success' => true,
            'product' => $product
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Produit non trouvé'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données',
        'error' => $e->getMessage()
    ]);
}
?>
