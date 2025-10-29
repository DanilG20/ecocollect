<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Layanan - EcoCollect</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Manrope', sans-serif;
      margin: 0;
      padding: 0;
      background: #f4f6f7;
      line-height: 1.6;
    }
    header {
      background: #C7CCA9;
      padding: 15px 30px;
      color: white;
      text-align: center;
    }
    header h1 {
      margin: 0;
    }
    .container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 20px;
    }
    .services-section {
      display: flex;
      flex-wrap: wrap;
      gap: 25px;
      justify-content: center;
    }
    .service-card {
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.15);
      width: 300px;
      text-align: center;
      transition: 0.3s;
    }
    .service-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.2);
    }
    .service-card h3 {
      color: #2e7d32;
      margin-bottom: 15px;
    }
    .service-card p {
      font-size: 15px;
      color: #444;
    }
    .icon {
      font-size: 50px;
      margin-bottom: 15px;
    }
    footer {
      text-align: center;
      padding: 20px;
      margin-top: 40px;
      background: #C7CCA9;
      color: white;
    }
  </style>
</head>
<body>

<header>
  <h1>Layanan EcoCollect</h1>
</header>

<div class="container">
  <p style="text-align:center; margin-bottom:30px; font-size:18px;">
    EcoCollect hadir untuk memberikan solusi praktis dalam pengelolaan sampah melalui layanan unggulan kami.
  </p>

  <a href="pickup.php"><div class="services-section">
    <div class="service-card"><a href="pickup.php">
      <div class="icon">🚛</div>
	  <h3><li><a href="pickup.php">Pickup Sampah</a></li></h3>
      <p>Foto sampahmu, upload di aplikasi EcoCollect, lalu tim kolektor terdekat akan menjemput dan memberikan nilai tukar untuk sampahmu.</p>
    </div>

    <a href="dropoff.php"><div class="service-card">
      <div class="icon">🏭</div><a href="dropoff.php">
	  <h3> <li><a href="dropoff.php">Drop Off Sampah</a></li> </h3>
      <p>Bawa langsung ke Recycling Centre mitra kami, daur ulang bisa dimulai bahkan dari sampah kecil.</p>
    </div>
  </div>
</div>

<footer>
  <p>© 2025 EcoCollect. Semua Hak Dilindungi.</p>
</footer>

</body>
</html>
