<?php
// ✅ Affichage des erreurs (pour le développement uniquement)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ✅ Chargement de la bibliothèque Endroid QR Code via Composer
require_once __DIR__ . '/vendor/autoload.php';

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

// ✅ Vérifier que le paramètre product_id est présent et numérique
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $productId = (int) $_GET['product_id'];

    // ✅ URL de base correcte pour le projet
    $baseUrl = 'http://192.168.1.7/mon-projet-stock';  // Assurez-vous que ce chemin est correct
    $baseUrl = rtrim($baseUrl, '/'); // Supprimer le slash final s’il existe

    // ✅ Construire l’URL complète vers la fiche produit
    $url = $baseUrl . '/view_product.php?id=' . urlencode($productId);

    // ✅ Créer le QR code avec les bons paramètres
    $qrCode = QrCode::create($url)
        ->setEncoding(new Encoding('UTF-8'))
        ->setErrorCorrectionLevel(new ErrorCorrectionLevelHigh())
        ->setSize(200)
        ->setMargin(10);

    // ✅ Générer l’image PNG du QR code
    $writer = new PngWriter();
    $result = $writer->write($qrCode);

    // ✅ Envoyer l’image PNG au navigateur
    header('Content-Type: image/png');
    header('Content-Disposition: inline; filename="qrcode.png"');
    echo $result->getString();
    exit;

} else {
    // ❌ Message d’erreur si l’identifiant est manquant ou invalide
    http_response_code(400);
    echo "Erreur : identifiant du produit manquant ou invalide.";
    exit;
}
