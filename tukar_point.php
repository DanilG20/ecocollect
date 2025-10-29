<?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];

// Ambil data user
$stmt = $conn->prepare("SELECT poin, saldo FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

// Saat form dikirim
$message = "";
if (isset($_POST['jumlah_poin'])) {
    $jumlah = (int) $_POST['jumlah_poin'];

    if ($jumlah <= 0) {
        $message = "⚠️ Jumlah poin tidak valid.";
    } elseif ($jumlah > $user['poin']) {
        $message = "❌ Poin Anda tidak cukup.";
    } else {
        // 50 poin = Rp10.000 → berarti 1 poin = 200 rupiah
        $nilai_rupiah = $jumlah * 200;
        $poin_baru = $user['poin'] - $jumlah;

        $update = $conn->prepare("UPDATE users SET poin=?, saldo=saldo+? WHERE id=?");
        $update->bind_param("iii", $poin_baru, $nilai_rupiah, $id);
        $update->execute();
        $update->close();

        $message = "✅ Berhasil menukar $jumlah poin menjadi Rp" . number_format($nilai_rupiah, 0, ',', '.');
        // Refresh data user
        $user['poin'] = $poin_baru;
        $user['saldo'] += $nilai_rupiah;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tukar Poin | EcoCollect</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #C7CCA9;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .card {
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      text-align: center;
      box-shadow: 0 12px 30px rgba(46,125,50,0.25);
      width: 400px;
    }
    h2 { color: #1b5e20; }
    .info { color: #333; margin-bottom: 15px; }
    input {
      padding: 10px;
      width: 80%;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 15px;
    }
    button {
      background: #1b5e20;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 8px;
      font-weight: 600;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #2e7d32;
      transform: scale(1.05);
    }
    a { color: #1b5e20; text-decoration: none; display: block; margin-top: 20px; }
    .message { margin-top: 10px; color: #00796b; font-weight: 600; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Tukar Poin 🌱</h2>
    <div class="info">Poin Anda: <b><?= $user['poin'] ?></b></div>
    <div class="info">Saldo E-Wallet Saat Ini: <b>Rp<?= number_format($user['saldo'], 0, ',', '.') ?></b></div>
    
    <form method="post">
      <input type="number" name="jumlah_poin" placeholder="Masukkan jumlah poin (misal 50)" required>
      <button type="submit">Tukar Sekarang</button>
    </form>

    <?php if ($message): ?>
      <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <a href="profile.php">← Kembali ke Profil</a>
  </div>
</body>
</html>
