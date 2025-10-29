<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mitra - EcoCollect</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Manrope', sans-serif;
      margin: 0;
      padding: 0;
      background: #f4f9f4;
    }
    header {
      background: #C7CCA9;
      padding: 15px 30px;
      color: white;
      text-align: center;
    }
    header h1 {
      margin: 0;
      font-size: 28px;
    }
    .container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 20px;
    }
    .intro {
      text-align: center;
      margin-bottom: 30px;
      font-size: 18px;
      color: #444;
    }
    .partners {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 25px;
    }
    .partner-card {
      background: white;
      border-radius: 50px;
      padding: 25px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.12);
      text-align: center;
      transition: 0.3s;
    }
    .partner-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.18);
    }
    .partner-card img {
      width: 400px;
	  border-radius: 50px;
      height: 200px;
      margin-bottom: 15px;
    }
    .partner-card h3 {
      margin-bottom: 10px;
      color: #2e7d32;
    }
    .partner-card p {
      color: #555;
      font-size: 15px;
    }
    footer {
      background: #C7CCA9;
      color: white;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<header>
  <h1>Mitra EcoCollect</h1>
</header>

<div class="container">
  <p class="intro">
    EcoCollect percaya bahwa perubahan besar hanya bisa tercapai dengan kolaborasi.  
    Berikut adalah mitra strategis kami dalam membangun ekosistem daur ulang berkelanjutan.
  </p>

  <div class="partners">
    <div class="partner-card">
      <img src="banksampah.jpg" alt="Bank Sampah">
      <h3>Bank Sampah</h3>
      <p>Bekerja sama dengan bank sampah di berbagai daerah untuk memperluas jangkauan layanan daur ulang.</p>
    </div>


    <div class="partner-card">
      <img src="daurulang.jpg" alt="Perusahaan Daur Ulang">
      <h3>Perusahaan Daur Ulang</h3>
      <p>Menyalurkan sampah terpilah ke perusahaan daur ulang untuk diolah menjadi produk baru.</p>
    </div>

  </div>
</div>

<footer>
  <p>© 2025 EcoCollect. Semua Hak Dilindungi.</p>
</footer>

</body>
</html>
