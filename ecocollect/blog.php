<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog - EcoCollect</title>
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
    .blog-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 25px;
    }
    .blog-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.12);
      transition: 0.3s;
    }
    .blog-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 12px 20px rgba(0,0,0,0.18);
    }
    .blog-card img {
      width: 100%;
      border-radius: 10px;
      margin-bottom: 15px;
    }
    .blog-card h3 {
      margin-bottom: 10px;
      color: #2e7d32;
    }
    .blog-card p {
      color: #555;
      font-size: 15px;
      margin-bottom: 15px;
    }
    .blog-card a {
      display: inline-block;
      background: #2e7d32;
      color: white;
      padding: 8px 15px;
      border-radius: 6px;
      text-decoration: none;
      transition: 0.3s;
    }
    .blog-card a:hover {
      background: #1b5e20;
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
  <h1>Blog EcoCollect</h1>
</header>

<div class="container">
  <p class="intro">
    Ikuti berita terbaru, tips, dan inspirasi dari EcoCollect untuk gaya hidup ramah lingkungan.
  </p>

  <div class="blog-grid">

    <div class="blog-card">
      <img src="blog1.png" alt="Tips Daur Ulang">
      <h3>5 Tips Mudah Memulai Daur Ulang di Rumah</h3>
      <p>Mulai dari memilah sampah organik dan anorganik hingga memanfaatkan kembali barang bekas. Yuk, coba sekarang!</p>
      <a href="#">Baca Selengkapnya</a>
    </div>

    <div class="blog-card">
      <img src="blog2.jpeg" alt="Inovasi Hijau">
      <h3>Inovasi Hijau: Plastik Jadi Bahan Bangunan</h3>
      <p>Bagaimana limbah plastik bisa diubah menjadi batu bata ramah lingkungan? Simak kisah inspiratifnya di sini.</p>
      <a href="#">Baca Selengkapnya</a>
    </div>

    <div class="blog-card">
      <img src="blog3.png" alt="Komunitas Lingkungan">
      <h3>Komunitas Hijau yang Mengubah Dunia</h3>
      <p>Komunitas lokal bisa menjadi penggerak utama dalam menjaga bumi. Mari kenali komunitas EcoCollect!</p>
      <a href="#">Baca Selengkapnya</a>
    </div>

  </div>
</div>

<footer>
  <p>© 2025 EcoCollect. Semua Hak Dilindungi.</p>
</footer>

</body>
</html>
