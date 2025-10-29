<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EcoCollect</title>
  
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Poppins', sans-serif;
      line-height: 1.6;
      color: #333;
      background-color: #f5f5f5;
      padding-top: 80px;
    }

    /* === NAVBAR RAPIH === */
    .navbar {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background: #C7CCA9;
      padding: 6px 30px;
      position: fixed;
      top: 0; left: 0;
      width: 100%;
      height: 65px;
      z-index: 1000;
      box-shadow: 0 2px 8px rgba(0,0,0,0.15);
      border-bottom: 2px solid #b2b89b;
    }
    .logo img {
      height: 55px;
      border-radius: 8px;
    }
    .menu {
      list-style: none;
      display: flex;
      gap: 20px;
      align-items: center;
      font-family: 'Manrope', sans-serif;
    }
    .menu li a {
      text-decoration: none;
      color: white;
      font-weight: 600;
      font-size: 14.5px;
      transition: color 0.3s;
    }
    .menu li a:hover { color: #2e7d32; }

    .login-btn, .logout-btn {
      text-decoration: none;
      background: #2e7d32;
      color: white;
      font-weight: bold;
      padding: 6px 14px;
      border-radius: 6px;
      transition: 0.3s;
      border: 2px solid transparent;
      font-size: 14px;
    }
    .login-btn:hover, .logout-btn:hover {
      background: #e8f5e9;
      border-color: #2e7d32;
      color: #2e7d32;
    }

    /* === HERO SECTION === */
    .hero {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 80px 100px;
      background: linear-gradient(to right, #ffffff 60%, #e8f5e9);
      flex-wrap: wrap;
      animation: fadeIn 1.2s ease-in;
      border-radius: 0 0 50px 50px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
      font-family: 'Manrope', sans-serif;
    }
    .hero-text { max-width: 500px; }
    .hero-text h1 {
      font-size: 64px;
      color: #2e7d32;
      font-family: 'Poppins', sans-serif;
      margin-bottom: 20px;
    }
    .hero-text p { font-size: 20px; color: #555; margin-bottom: 40px; }
    .btn {
      display: inline-block;
      background-color: #2e7d32;
      color: white;
      border: none;
      padding: 14px 32px;
      border-radius: 8px;
      font-size: 18px;
      text-decoration: none;
      font-weight: bold;
      transition: all 0.3s;
    }
    .btn:hover { background-color: #1b5e20; transform: scale(1.05); }
    .hero-img img {
      max-width: 500px;
      border-radius: 20px;
      box-shadow: 0 5px 25px rgba(0, 0, 0, 0.25);
      transition: transform 0.4s ease;
    }
    .hero-img img:hover { transform: scale(1.03); }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* === LAYANAN === */
    .services {
      font-family: 'Manrope', sans-serif;
      padding: 80px 30px;
      text-align: center;
      background-color: #ffffff;
    }
    .services h2 { font-size: 36px; margin-bottom: 10px; color: #2e7d32; }
    .services p { margin-bottom: 50px; color: #555; font-size: 18px; }
    .service-box {
      display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;
    }
    .card {
      background: white;
      padding: 25px;
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.1);
      width: 270px;
      transition: transform 0.3s, box-shadow 0.3s;
    }
    .card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.15); }
    .card h3 { color: #2e7d32; margin-bottom: 10px; }
    .card p { margin-bottom: 20px; font-size: 16px; }

    /* === DASHBOARD === */
    .dashboard {
      padding: 80px 30px;
      font-family: 'Manrope', sans-serif;
      text-align: center;
      background: #e8f5e9;
      border-top: 2px solid #dcedc8;
    }
    .dashboard h2 { font-size: 32px; margin-bottom: 20px; color: #2e7d32; }

    footer {
      background: #C7CCA9;
      color: white;
      text-align: center;
      padding: 25px;
      font-size: 15px;
      margin-top: 50px;
      letter-spacing: 0.5px;
    }

    @media (max-width: 900px) {
      .hero { flex-direction: column; text-align: center; padding: 60px 30px; }
      .hero-text h1 { font-size: 48px; }
      .hero-text { margin-bottom: 40px; }
      .hero-img img { width: 90%; }
      .service-box { flex-direction: column; align-items: center; }
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header>
    <nav class="navbar">
      <div class="logo">
        <img src="logoterbaru.jpg" alt="EcoCollect Logo">
      </div>
      <ul class="menu">
        <li><a href="about.php">Tentang Kami</a></li>
        <li><a href="services.php">Layanan</a></li>
        <li><a href="solutions.php">Solusi Kami</a></li>
        <li><a href="mitra.php">Mitra</a></li>
        <li><a href="blog.php">Blog</a></li>
        <li><a href="kontak.php">Kontak Kami</a></li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <li><a href="profile.php" class="login-btn">Hi, <?= htmlspecialchars($_SESSION['name']) ?></a></li>
          <li><a href="History.php" class="logout-btn">History</a></li>

          <!-- Menu Khusus Admin -->
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <li><a href="admin_approval.php" class="logout-btn">Approval Request</a></li>
            <li><a href="admin_history.php" class="logout-btn">Admin History</a></li>
          <?php endif; ?>
          
        <?php else: ?>
          <li><a href="login.php" class="login-btn">Login</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-text">
      <h1>EcoCollect</h1>
      <p>Clean Earth, Bright Future </p>
      <a href="#layanan" class="btn">Mulai Sekarang</a>
    </div>
    <div class="hero-img">
      <img src="pekerja.jpg" alt="EcoCollect Worker">
    </div>
  </section>

  <!-- LAYANAN -->
  <section class="services" id="layanan">
    <h2>Layanan</h2>
    <p>Revolusi daur ulang EcoCollect untuk semua orang.</p>

    <div class="service-box">
      <div class="card">
        <h3>Pick Up</h3>
        <p>Kolektor terdekat akan menjemput sampahmu.</p>
        <a href="pickup.php" class="btn">Isi Form Pick Up</a>
      </div>
      <div class="card">
        <h3>Drop Off</h3>
        <p>Bawa langsung ke Recycling Centre terdekat.</p>
        <a href="dropoff.php" class="btn">Isi Form Drop Off</a>
      </div>
    </div>
  </section>

  <!-- DASHBOARD -->
  <section class="dashboard">
    <h2>Dashboard EcoCollect</h2>
    <p><a href="dashboard.php" class="btn">Lihat Data</a></p>
  </section>

  <footer>
    <p>© 2025 EcoCollect. Semua Hak Dilindungi.</p>
  </footer>

</body>
</html>
