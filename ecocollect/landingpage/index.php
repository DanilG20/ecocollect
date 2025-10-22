<?php
require_once __DIR__ . '/../db.php';
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Ecocollect - Landing</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header">
    <div class="container header-inner">
      <div class="brand">
        <a href="index.php">Ecocollect</a>
      </div>
      <nav class="nav-right">
        <?php if (!empty($user)): ?>
          <div class="dropdown">
            <button class="btn name-btn"><?= htmlspecialchars($user['name']) ?> <span class="pts">(<?= (int)$user['points'] ?> pts)</span></button>
            <div class="dropdown-content">
              <a href="../profile.php">Profile</a>
              <a href="../pickup.php">Pickup</a>
              <a href="../dropoff.php">Dropoff</a>
              <a href="../logout.php">Logout</a>
            </div>
          </div>
        <?php else: ?>
          <a class="btn" href="../login.php">Login</a>
          <a class="btn btn-primary" href="../register.php">Register</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main>
    <section class="hero">
      <div class="container">
        <h1>Selamat datang di Ecocollect</h1>
        <p>Platform untuk pengumpulan dan daur ulang sampah, yang mudah dan ramah lingkungan.</p>
        <?php if (!empty($user)): ?>
          <a class="cta" href="../pickup.php">Buat Pickup</a>
        <?php else: ?>
          <a class="cta" href="../register.php">Daftar Sekarang</a>
        <?php endif; ?>
      </div>
    </section>

    <section class="features container">
      <div class="card">
        <h3>Pickup</h3>
        <p>Kami mengambil sampah daur ulang dari rumah Anda.</p>
      </div>
      <div class="card">
        <h3>Dropoff</h3>
        <p>Bawa ke titik dropoff dan dapatkan poin lebih besar.</p>
      </div>
      <div class="card">
        <h3>Tukar Poin</h3>
        <p>Tukar poin dengan reward menarik (opsional dikembangkan lagi).</p>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="container">
      &copy; <?= date('Y') ?> Ecocollect
    </div>
  </footer>
</body>
</html>
