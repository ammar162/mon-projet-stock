<?php
require_once('vendor/autoload.php');      // TCPDF
require_once('includes/db.php');          // Connexion à la base

// Récupération des produits
$produits = $pdo->query("SELECT * FROM products")->fetchAll(PDO::FETCH_ASSOC);

// Création du PDF
$pdf = new TCPDF();
$pdf->SetCreator('IS-TECH');
$pdf->SetAuthor('IS-TECH');
$pdf->SetTitle('Catalogue des Produits');
$pdf->SetMargins(10, 30, 10);
$pdf->SetAutoPageBreak(TRUE, 15);

// En-tête personnalisé avec logo
class PDF extends TCPDF {
    public function Header() {
        $logo = 'assets/is-tech.jpeg';
        if (file_exists($logo)) {
            $this->Image($logo, 10, 10, 30);
        }
        $this->SetFont('helvetica', 'B', 16);
        $this->Cell(0, 15, 'Catalogue des Produits - IS-TECH', 0, 1, 'C');
    }
}
$pdf = new PDF();

// Ajouter une page
$pdf->AddPage();

// CSS stylisé (imaginaire)
$css = <<<CSS
<style>
    h1 {
        color: #2c3e50;
        text-align: center;
        margin-bottom: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    th {
        background-color: #34495e;
        color: white;
        padding: 6px;
        font-size: 11pt;
        border: 1px solid #ddd;
    }
    td {
        border: 1px solid #ccc;
        padding: 6px;
        font-size: 10pt;
        text-align: center;
        vertical-align: middle;
    }
    img.prod {
        width: 50px;
        height: auto;
    }
</style>
CSS;

// Début HTML
$html = <<<HTML
$css
<h1>Liste des Produits</h1>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom</th>
            <th>Image</th>
            <th>Description</th>
            <th>Prix</th>
            <th>Quantité</th>
            <th>Seuil</th>
        </tr>
    </thead>
    <tbody>
HTML;

// Boucle produits
foreach ($produits as $p) {
    $image = $p['image'];
    $imageHTML = (file_exists($image)) ? '<img class="prod" src="' . $image . '">' : 'Aucune';

    $html .= '<tr>';
    $html .= '<td>' . $p['id'] . '</td>';
    $html .= '<td>' . htmlspecialchars($p['nom']) . '</td>';
    $html .= '<td>' . $imageHTML . '</td>';
    $html .= '<td>' . htmlspecialchars($p['description']) . '</td>';
    $html .= '<td>' . number_format($p['prix'], 2) . ' DH</td>';
    $html .= '<td>' . $p['quantite'] . '</td>';
    $html .= '<td>' . $p['seuil_alerte'] . '</td>';
    $html .= '</tr>';
}

$html .= '</tbody></table>';

// Écrire dans le PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Générer le PDF
$pdf->Output('catalogue_produits.pdf', 'I');
