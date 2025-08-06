<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

include 'includes/auth.php';
include 'includes/header.php';
include 'includes/db.php';

// Statistiques globales
$count = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
$rupture = $pdo->query("SELECT COUNT(*) FROM products WHERE quantite = 0")->fetchColumn();
$faible = $pdo->query("SELECT COUNT(*) FROM products WHERE quantite < seuil_alerte AND quantite > 0")->fetchColumn();
$valeurStock = $pdo->query("SELECT SUM(prix * quantite) FROM products")->fetchColumn();

// Derniers mouvements de stock
$stmt = $pdo->query("SELECT m.*, p.nom AS produit_nom, p.image AS produit_image 
                     FROM stock_movements m 
                     JOIN products p ON m.product_id = p.id 
                     ORDER BY m.movement_date DESC 
                     LIMIT 5");
$mouvements = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f6f8fa;
    padding: 20px;
    color: #343a40;
    min-height: 100vh;
    margin: 0;
}
.dashboard-container {
    max-width: 1100px;
    margin: auto;
}
.stats {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-between;
    margin-bottom: 30px;
}
.stat-box {
    flex: 1;
    min-width: 200px;
    background: white;
    border-radius: 10px;
    padding: 20px;
    text-align: center;
    border-top: 4px solid #007bff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: transform 0.2s ease;
}
.stat-box:hover {
    transform: translateY(-5px);
}
.stat-box.warning { border-top-color: #ffc107; }
.stat-box.danger { border-top-color: #dc3545; }
.stat-box.green { border-top-color: #28a745; }

.chart-container {
    background: white;
    border-radius: 10px;
    padding: 30px;
    margin-bottom: 40px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

#searchInput::placeholder {
    color: #888;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
th, td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #ccc;
    vertical-align: middle;
}
th {
    background-color: #343a40;
    color: white;
}
table img {
    width: 50px;
    height: auto;
    border-radius: 6px;
}

#reader {
    width: 300px;
    margin: 20px auto;
    border: 2px dashed #007bff;
    padding: 10px;
    border-radius: 10px;
    background: #f8f9fa;
    box-shadow: 0 0 12px rgba(0,123,255,0.3);
}
a.export-btn {
    padding: 10px 15px;
    color: white;
    border-radius: 5px;
    text-decoration: none;
    font-weight: 600;
    margin-left: 10px;
    transition: background-color 0.3s ease;
}
a.export-btn.csv {
    background-color: #198754;
}
a.export-btn.csv:hover {
    background-color: #146c43;
}
a.export-btn.pdf {
    background-color: #0d6efd;
}
a.export-btn.pdf:hover {
    background-color: #084298;
}

@media(max-width: 700px) {
    .stats {
        flex-direction: column;
    }
    #reader {
        width: 100%;
    }
}
</style>

<div class="dashboard-container">
    <h2>📊 Tableau de bord - Gestion de Stock</h2>

    <div class="stats">
        <div class="stat-box">
            <h4>Total produits</h4>
            <p><?= $count ?></p>
        </div>
        <div class="stat-box warning">
            <h4>Stock faible</h4>
            <p><?= $faible ?></p>
        </div>
        <div class="stat-box danger">
            <h4>Ruptures</h4>
            <p><?= $rupture ?></p>
        </div>
        <div class="stat-box green">
            <h4>Valeur du stock</h4>
            <p><?= number_format($valeurStock, 2) ?> DH</p>
        </div>
    </div>

    <div class="chart-container">
        <h3>📈 État global des stocks</h3>
        <canvas id="stockChart" height="100"></canvas>
    </div>

    <div style="display:flex; flex-wrap: wrap; justify-content: space-between; align-items: center; margin: 20px 0;">
        <input type="text" id="searchInput" placeholder="🔍 Rechercher un mouvement..." style="padding: 10px; border: 1px solid #ccc; border-radius: 5px; width: 300px; max-width: 100%;">
        <div>
            <a href="export_csv.php" class="export-btn csv" title="Exporter en CSV">📄 Export CSV</a>
            <a href="export_pdf.php" class="export-btn pdf" title="Exporter en PDF">📄 Export PDF</a>
        </div>
    </div>

    <h3>📦 Derniers mouvements</h3>
    <table id="mouvementTable" aria-label="Table des derniers mouvements de stock">
        <thead>
            <tr>
                <th>Image</th>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($mouvements as $mv): ?>
            <tr>
                <td>
                <?php if (!empty($mv['produit_image']) && file_exists($mv['produit_image'])): ?>
                    <img src="<?= htmlspecialchars($mv['produit_image']) ?>" alt="Image du produit <?= htmlspecialchars($mv['produit_nom']) ?>">
                <?php else: ?>
                    <span style="color: grey;">Aucune</span>
                <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($mv['produit_nom']) ?></td>
                <td>
                    <?php
                        switch ($mv['movement_type']) {
                            case 'IN': echo 'Entrée 📥'; break;
                            case 'OUT': echo 'Sortie 📤'; break;
                            case 'rupture': echo '<span style="color:red;font-weight:bold;">Rupture ⚠️</span>'; break;
                            default: echo htmlspecialchars($mv['movement_type']);
                        }
                    ?>
                </td>
                <td><?= (int)$mv['quantity'] ?></td>
                <td><?= (new DateTime($mv['movement_date']))->format('d/m/Y H:i') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>📷 Scanner un code QR</h3>
    <div id="reader" role="region" aria-label="Scanner QR Code"></div>
    <p><strong>Résultat:</strong> <span id="qr-result" style="color: green;">Aucun</span></p>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<script>
const ctx = document.getElementById('stockChart').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['🟢 En stock', '🟡 Stock faible', '🔴 Rupture'],
        datasets: [{
            label: 'Nombre de produits',
            data: [<?= max(0, $count - $faible - $rupture) ?>, <?= $faible ?>, <?= $rupture ?>],
            backgroundColor: ['#28a745', '#ffc107', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                ticks: { stepSize: 1 },
                title: { display: true, text: 'Quantité' }
            },
            x: {
                title: { display: true, text: 'État du stock' }
            }
        },
        plugins: { legend: { display: false } }
    }
});

// QR Code Reader
Html5Qrcode.getCameras().then(devices => {
    if (devices.length) {
        const html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            devices[0].id,
            { fps: 10, qrbox: 250 },
            text => {
                document.getElementById('qr-result').textContent = text;
                try {
                    const url = new URL(text);
                    const idParam = url.searchParams.get("id");
                    if (idParam && !isNaN(idParam)) {
                        window.location.href = url.href;
                    } else {
                        alert("❌ QR invalide : identifiant non trouvé.");
                    }
                } catch (e) {
                    // Le QR contient du texte non-URL, on affiche juste le texte
                }
            },
            error => {
                // On ignore les erreurs de scan pour ne pas spammer l'utilisateur
            }
        );
    } else {
        alert("Aucune caméra détectée.");
    }
});

// Recherche instantanée dans le tableau
document.getElementById("searchInput").addEventListener("keyup", function() {
    const value = this.value.toLowerCase();
    document.querySelectorAll("#mouvementTable tbody tr").forEach(row => {
        row.style.display = row.innerText.toLowerCase().includes(value) ? "" : "none";
    });
});
</script>

<?php include 'includes/footer.php'; ?>
