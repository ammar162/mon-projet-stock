<?php
require 'includes/db.php';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=produits.csv');

// UTF-8 BOM pour Excel
echo "\xEF\xBB\xBF";

$output = fopen('php://output', 'w');

// Entêtes du fichier CSV
fputcsv($output, ['ID', 'Nom', 'Description', 'Prix', 'Quantité', 'Seuil d\'alerte']);

// Récupération des produits
$products = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

// Insertion ligne par ligne
foreach ($products as $p) {
    fputcsv($output, [
        $p['id'],
        $p['nom'],
        $p['description'],
        number_format($p['prix'], 2, '.', ''),
        $p['quantite'],
        $p['seuil_alerte']
    ]);
}

fclose($output);
exit;
?>
