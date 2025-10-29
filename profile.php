<?php
session_start();
require 'db.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['user_id'];

// Ambil data user
$stmt = $conn->prepare("SELECT name, email, poin, saldo FROM users WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$user = $res->fetch_assoc();
$stmt->close();

// Ambil notifikasi terbaru (jika ada)
$notif_sql = $conn->prepare("SELECT * FROM notifications WHERE user_id=? AND status='unread' ORDER BY created_at DESC LIMIT 1");
$notif_sql->bind_param("i", $id);
$notif_sql->execute();
$notif_res = $notif_sql->get_result();
$notif_data = $notif_res->fetch_assoc();

// Ubah jadi 'read' setelah tampil
if ($notif_data) {
    $update = $conn->prepare("UPDATE notifications SET status='read' WHERE id=?");
    $update->bind_param("i", $notif_data['id']);
    $update->execute();
}

// Ambil riwayat penukaran poin user
$history = [];
$hstmt = $conn->prepare("SELECT requested_points, ewallet_provider, ewallet_account, status, created_at 
                         FROM point_requests 
                         WHERE user_id=? 
                         ORDER BY created_at DESC");
$hstmt->bind_param("i", $id);
$hstmt->execute();
$hres = $hstmt->get_result();
while ($row = $hres->fetch_assoc()) {
    $history[] = $row;
}
$hstmt->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Profil | EcoCollect</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: #C7CCA9;
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      margin: 0;
      padding: 40px 0;
    }

    .container {
      width: 90%;
      max-width: 800px;
      display: flex;
      flex-direction: column;
      gap: 30px;
    }

    .card, .history {
      background: #fff;
      padding: 40px;
      border-radius: 16px;
      box-shadow: 0 12px 30px rgba(46,125,50,0.25);
      width: 100%;
      box-sizing: border-box;
    }

    .card {
      text-align: center;
    }

    h2 {
      color: #1b5e20;
      margin-top: 0;
    }

    .info {
      margin-bottom: 10px;
      color: #555;
    }

    .points-badge {
      background: #43a047;
      color: white;
      padding: 15px 25px;
      border-radius: 12px;
      margin: 20px 0;
      font-size: 1.3rem;
      font-weight: 700;
      cursor: pointer;
      transition: all 0.3s;
      text-decoration: none;
      display: inline-block;
    }

    .points-badge:hover {
      background: #2e7d32;
      transform: scale(1.05);
    }

    .saldo {
      background: #00796b;
      color: #fff;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-weight: 600;
    }

    .actions a {
      display: inline-block;
      margin: 8px;
      padding: 10px 20px;
      background: #e8f5e9;
      border-radius: 8px;
      border: 1px solid #c8e6c9;
      color: #2e7d32;
      font-weight: 600;
      text-decoration: none;
    }

    .actions a:hover {
      background: #c8e6c9;
    }

    /* Tabel Riwayat */
    .history h3 {
      color: #1b5e20;
      margin-top: 0;
      margin-bottom: 15px;
      text-align: center;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      text-align: center;
      padding: 10px;
      border-bottom: 1px solid #eee;
      font-size: 0.95rem;
    }

    th {
      background: #e8f5e9;
      color: #1b5e20;
    }

    tr:hover {
      background: #f5f5f5;
    }

    .status {
      font-weight: 600;
      border-radius: 8px;
      padding: 6px 10px;
      display: inline-block;
    }

    .status.pending { background: #fff3cd; color: #856404; }
    .status.approved { background: #d4edda; color: #155724; }
    .status.rejected { background: #f8d7da; color: #721c24; }

    /* Notifikasi approval */
    .notif {
      background: #e8f5e9;
      border: 1px solid #43a047;
      color: #1b5e20;
      padding: 12px;
      border-radius: 8px;
      font-weight: 600;
      text-align: center;
      margin-bottom: 20px;
      animation: fadeIn 0.6s;
    }

    @keyframes fadeIn {
      from {opacity: 0; transform: translateY(-10px);}
      to {opacity: 1; transform: translateY(0);}
    }

    /* Responsif */
    @media (max-width: 600px) {
      .card, .history {
        padding: 25px;
      }
      .points-badge {
        font-size: 1.1rem;
        padding: 12px 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <!-- Notifikasi otomatis -->
    <?php if (!empty($notif_data)): ?>
      <div class="notif" id="notifBox"><?= htmlspecialchars($notif_data['message']) ?></div>
    <?php endif; ?>

    <!-- CARD PROFIL -->
    <div class="card">
      <h2>Halo, <?= htmlspecialchars($user['name']) ?> 👋</h2>
      <div class="info">Email: <?= htmlspecialchars($user['email']) ?></div>

      <a href="tukar_poin.php" class="points-badge">
        🌱 Poin Anda: <?= $user['poin'] ?> (Klik untuk tukar)
      </a>

      <div class="actions">
        <a href="pickup.php">Pickup (+5)</a>
        <a href="dropoff.php">Dropoff (+10)</a>
        <?php if ($user['email'] === 'admin@ecocollect.com'): ?>
          <a href="admin_dashboard.php" style="background:#c8e6c9;">👑 Admin Panel</a>
        <?php endif; ?>
      </div>

      <a href="logout.php" style="color:#d32f2f; text-decoration:underline;">Logout</a>
    </div>

    <!-- RIWAYAT PENUKARAN -->
    <div class="history">
      <h3>📜 Riwayat Penukaran Poin</h3>
      <?php if (count($history) > 0): ?>
      <table>
        <tr>
          <th>Tanggal Request</th>
          <th>Jumlah Poin</th>
          <th>E-Wallet</th>
          <th>Status</th>
        </tr>
        <?php foreach ($history as $row): ?>
        <tr>
          <td><?= date('d/m/Y H:i', strtotime($row['created_at'])) ?></td>
          <td><?= htmlspecialchars($row['requested_points']) ?></td>
          <td><?= htmlspecialchars($row['ewallet_provider']) ?></td>
          <td>
            <span class="status <?= strtolower($row['status']) ?>">
              <?= ucfirst($row['status']) ?>
            </span>
          </td>
        </tr>
        <?php endforeach; ?>
      </table>
      <?php else: ?>
        <p style="text-align:center; color:#777;">Belum ada riwayat penukaran poin.</p>
      <?php endif; ?>
    </div>
  </div>

  <script>
    const notif = document.getElementById('notifBox');
    if (notif) {
      setTimeout(() => notif.style.display = 'none', 5000);
    }
  </script>
</body>
</html>
