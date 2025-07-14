<?php
include 'includes/db.php';

$id = $_GET['id'];

// Supprimer d'abord tous les mouvements liés à ce produit
$stmt = $pdo->prepare("DELETE FROM movements WHERE product_id = ?");
$stmt->execute([$id]);

// Ensuite seulement supprimer le produit
$stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
$stmt->execute([$id]);

header('Location: products.php');
exit;
