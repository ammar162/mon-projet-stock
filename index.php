<?php include 'includes/header.php'; ?>

<!DOCTYPE html>
<html lang="fr" dir="ltr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Accueil | Stock App</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }

    body {
      background: #f5f7fa;
      color: #333;
    }

    .hero {
      text-align: center;
      padding: 60px 20px;
      background: linear-gradient(to right, #dbeafe, #e0f2fe);
      border-radius: 0 0 20px 20px;
    }

    .hero h1 {
      font-size: 36px;
      color: #1e3a8a;
      margin-bottom: 15px;
    }

    .hero p {
      font-size: 18px;
      color: #374151;
    }

    .features {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 30px;
      padding: 40px 20px;
      background-color: #fff;
    }

    .feature-box {
      background: #f9fafb;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 20px;
      width: 280px;
      text-align: center;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
      transition: transform 0.3s;
      text-decoration: none;
      color: inherit;
    }

    .feature-box:hover {
      transform: translateY(-5px);
      background-color: #f1f5f9;
    }

    .feature-box i {
      font-size: 30px;
      color: #0d6efd;
      margin-bottom: 10px;
    }

    .feature-box h3 {
      margin-bottom: 10px;
      color: #222;
    }

    @media (max-width: 768px) {
      .hero h1 {
        font-size: 28px;
      }
      .hero p {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

  <section class="hero">
    <h1>Bienvenue sur Stock App</h1>
    <p>Une application simple et professionnelle pour gérer votre stock et suivre vos produits en temps réel</p>
  </section>

  <section class="features">
    <a class="feature-box" href="products.php">
      <i class="fas fa-boxes"></i>
      <h3>Gestion des produits</h3>
      <p>Ajoutez, modifiez et suivez vos articles facilement</p>
    </a>
    <a class="feature-box" href="dashboard.php">
      <i class="fas fa-chart-line"></i>
      <h3>Statistiques et rapports</h3>
      <p>Consultez des rapports clairs sur l’état du stock</p>
    </a>
    <a class="feature-box" href="movements.php">
      <i class="fas fa-shipping-fast"></i>
      <h3>Mouvements du stock</h3>
      <p>Enregistrez les entrées et sorties de vos produits</p>
    </a>
  </section>

</body>
</html>

<?php include 'includes/footer.php'; ?>
