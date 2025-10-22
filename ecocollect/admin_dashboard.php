<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Pesan notifikasi sesi
$msg = '';
if (isset($_SESSION['msg'])) {
    $msg = $_SESSION['msg'];
    unset($_SESSION['msg']);
}

// ====== PROSES APPROVE / REJECT ======
if (isset($_GET['approve']) || isset($_GET['reject'])) {
    $req_id = isset($_GET['approve']) ? (int)$_GET['approve'] : (int)$_GET['reject'];
    $action = isset($_GET['approve']) ? 'approved' : 'rejected';

    // Ambil data request
    $stmt = $conn->prepare("SELECT user_id, points AS requested_points FROM point_exchange_requests WHERE id=?");
    $stmt->bind_param("i", $req_id);
    $stmt->execute();
    $r = $stmt->get_result()->fetch_assoc();

    if ($r) {
        // Update status request
        $update = $conn->prepare("UPDATE point_exchange_requests SET status=? WHERE id=?");
        $update->bind_param("si", $action, $req_id);
        $update->execute();

        // Kirim notifikasi ke user
        $notif = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
        if ($action === 'approved') {
            $msg_text = "✅ Penukaran {$r['requested_points']} poin kamu telah disetujui oleh admin!";
        } else {
            $msg_text = "❌ Penukaran {$r['requested_points']} poin kamu ditolak oleh admin.";
        }
        $notif->bind_param("is", $r['user_id'], $msg_text);
        $notif->execute();

        // Simpan pesan sukses
        $_SESSION['msg'] = ($action === 'approved')
            ? "✅ Request #$req_id berhasil disetujui!"
            : "❌ Request #$req_id ditolak!";

        header("Location: admin_dashboard.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard | EcoCollect</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {
  font-family: 'Poppins', sans-serif;
  background: #e8f5e9;
  padding: 40px;
}
.container {
  background: white;
  padding: 30px;
  border-radius: 12px;
  box-shadow: 0 6px 20px rgba(0,0,0,0.1);
}
h1 { color: #2e7d32; margin-bottom: 20px; text-align: center; }
table {
  width: 100%; border-collapse: collapse; margin-top: 20px;
}
th, td {
  padding: 12px 15px; text-align: center;
  border-bottom: 1px solid #ddd;
}
th {
  background: #4caf50; color: white;
}
td { background: #fff; }
a.btn {
  padding: 8px 14px;
  border-radius: 6px;
  text-decoration: none;
  font-weight: bold;
}
.approve-btn {
  background: #388e3c; color: white;
}
.reject-btn {
  background: #f44336; color: white;
}
.approve-btn:hover { background: #2e7d32; }
.reject-btn:hover { background: #d32f2f; }
.msg {
  background: #c8e6c9; color: #1b5e20;
  padding: 10px; border-radius: 8px;
  margin-bottom: 15px; text-align: center;
}
.logout {
  display: inline-block; margin-top: 20px;
  background: #2e7d32; color: white; padding: 10px 18px;
  border-radius: 8px; text-decoration: none;
}
.logout:hover { background: #1b5e20; }
</style>
</head>
<body>
<div class="container">
  <h1>📋 Admin Approval Tukar Poin</h1>

  <?php if ($msg): ?>
    <div class="msg"><?= htmlspecialchars($msg) ?></div>
  <?php endif; ?>

  <table>
    <tr>
      <th>ID</th>
      <th>Nama User</th>
      <th>Voucher</th>
      <th>Poin</th>
      <th>Status</th>
      <th>Aksi</th>
    </tr>
    <?php
    $res = $conn->query("SELECT r.id, u.name, r.voucher_type, r.points, r.status
                         FROM point_exchange_requests r
                         JOIN users u ON r.user_id = u.id
                         ORDER BY r.created_at DESC");
    while ($row = $res->fetch_assoc()):
    ?>
    <tr>
      <td><?= $row['id'] ?></td>
      <td><?= htmlspecialchars($row['name']) ?></td>
      <td><?= htmlspecialchars($row['voucher_type']) ?></td>
      <td><?= $row['points'] ?></td>
      <td><?= ucfirst($row['status']) ?></td>
      <td>
        <?php if ($row['status'] === 'pending'): ?>
          <a class="btn approve-btn" href="?approve=<?= $row['id'] ?>">Approve</a>
          <a class="btn reject-btn" href="?reject=<?= $row['id'] ?>">Reject</a>
        <?php elseif ($row['status'] === 'approved'): ?>
          ✅ Disetujui
        <?php else: ?>
          ❌ Ditolak
        <?php endif; ?>
      </td>
    </tr>
    <?php endwhile; ?>
  </table>

  <a class="logout" href="profile.php">⬅️ Kembali ke Profil</a>
</div>
</body>
</html>
