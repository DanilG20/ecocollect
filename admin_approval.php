<?php
session_start();
require 'db.php';

// pastikan admin login
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// handle approve/reject
if (isset($_GET['action'], $_GET['id'])) {
    $req_id = (int)$_GET['id'];
    $action = $_GET['action'];

    // ambil data request
    $stmt = $conn->prepare("SELECT point_requests.*, users.name FROM point_requests JOIN users ON users.id = point_requests.user_id WHERE point_requests.id=? LIMIT 1");
    $stmt->bind_param("i", $req_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $r = $res->fetch_assoc();
    $stmt->close();

    if ($r) {
        if ($action === 'approve' && $r['status'] === 'pending') {
            // update status jadi approved
            $stmt = $conn->prepare("UPDATE point_requests SET status='approved', approved_at=NOW() WHERE id=?");
            $stmt->bind_param("i", $req_id);
            $stmt->execute();
            $stmt->close();

            // tambahkan saldo user
            $saldo = $r['requested_points'] * 200; // 1 poin = Rp200
            $stmt = $conn->prepare("UPDATE users SET saldo = saldo + ?, poin = poin - ? WHERE id=?");
            $stmt->bind_param("iii", $saldo, $r['requested_points'], $r['user_id']);
            $stmt->execute();
            $stmt->close();

            // kirim notifikasi ke user
            $notif = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            $msg = "✅ Penukaran {$r['requested_points']} poin kamu telah disetujui oleh admin!";
            $notif->bind_param("is", $r['user_id'], $msg);
            $notif->execute();
            $notif->close();

            $_SESSION['msg'] = "✅ Request #$req_id berhasil disetujui!";
        }

        if ($action === 'reject' && $r['status'] === 'pending') {
            // update status jadi rejected
            $stmt = $conn->prepare("UPDATE point_requests SET status='rejected', approved_at=NOW() WHERE id=?");
            $stmt->bind_param("i", $req_id);
            $stmt->execute();
            $stmt->close();

            // kirim notifikasi ke user
            $notif = $conn->prepare("INSERT INTO notifications (user_id, message) VALUES (?, ?)");
            $msg = "❌ Penukaran {$r['requested_points']} poin kamu ditolak oleh admin.";
            $notif->bind_param("is", $r['user_id'], $msg);
            $notif->execute();
            $notif->close();

            $_SESSION['msg'] = "❌ Request #$req_id ditolak!";
        }
    }
    header("Location: admin_approval.php");
    exit;
}

// ambil semua request
$sql = "SELECT point_requests.id AS request_id, point_requests.requested_points, point_requests.ewallet_provider, 
        point_requests.ewallet_account, point_requests.status, point_requests.created_at, point_requests.approved_at,
        users.name 
        FROM point_requests 
        JOIN users ON users.id = point_requests.user_id
        ORDER BY point_requests.id DESC";

$res = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Admin Approval | EcoCollect</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
body {font-family:'Poppins',sans-serif; background:#f1f6ef; padding:20px;}
table {width:100%; border-collapse:collapse;}
th, td {padding:12px; border-bottom:1px solid #ccc; text-align:center;}
th {background:#43a047; color:#fff;}
a.button {padding:6px 12px; border-radius:6px; text-decoration:none; color:#fff;}
a.approve {background:#43a047;}
a.reject {background:#d32f2f;}
.msg {padding:12px; background:#e8f5e9; color:#1b5e20; margin-bottom:12px; border-radius:8px; font-weight:600;}
</style>
</head>
<body>
<h2>👑 Admin Approval</h2>

<?php if(!empty($_SESSION['msg'])): ?>
  <div class="msg"><?= htmlspecialchars($_SESSION['msg']) ?></div>
  <?php unset($_SESSION['msg']); ?>
<?php endif; ?>

<table>
<tr>
<th>#</th>
<th>User</th>
<th>Poin</th>
<th>E-Wallet</th>
<th>Akun</th>
<th>Status</th>
<th>Request At</th>
<th>Approved At</th>
<th>Aksi</th>
</tr>
<?php while($r = $res->fetch_assoc()): ?>
<tr>
<td><?= $r['request_id'] ?></td>
<td><?= htmlspecialchars($r['name']) ?></td>
<td><?= $r['requested_points'] ?></td>
<td><?= htmlspecialchars($r['ewallet_provider']) ?></td>
<td><?= htmlspecialchars($r['ewallet_account']) ?></td>
<td><?= ucfirst($r['status']) ?></td>
<td><?= date('d/m/Y H:i', strtotime($r['created_at'])) ?></td>
<td><?= $r['approved_at'] ? date('d/m/Y H:i', strtotime($r['approved_at'])) : '-' ?></td>
<td>
  <?php if($r['status']=='pending'): ?>
    <a href="?action=approve&id=<?= $r['request_id'] ?>" class="button approve">Approve</a>
    <a href="?action=reject&id=<?= $r['request_id'] ?>" class="button reject">Reject</a>
  <?php else: ?>
    -
  <?php endif; ?>
</td>
</tr>
<?php endwhile; ?>
</table>
</body>
</html>
