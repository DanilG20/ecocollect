<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// Ambil data user
$stmt = $conn->prepare("SELECT id, name, email, poin FROM users WHERE id = ? LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res && $res->num_rows === 1) {
    $user = $res->fetch_assoc();
} else {
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}
$stmt->close();

// Jika form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $points = (int)$_POST['requested_points'];
    $provider = $_POST['ewallet_provider'];
    $account = trim($_POST['ewallet_account']);

    // Validasi poin
    if ($points <= 0 || $points > $user['poin']) {
        $_SESSION['msg_error'] = "❌ Jumlah poin tidak valid!";
        header("Location: tukar_poin.php");
        exit;
    }

    // Cek apakah ada request poin yang masih pending
    $check = $conn->prepare("SELECT COUNT(*) as cnt FROM point_requests WHERE user_id=? AND status='pending'");
    $check->bind_param("i", $user_id);
    $check->execute();
    $res = $check->get_result()->fetch_assoc();
    $check->close();

    if ($res['cnt'] > 0) {
        $_SESSION['msg_error'] = "⚠️ Anda masih memiliki request yang belum disetujui!";
        header("Location: tukar_poin.php");
        exit;
    }

    // Simpan request
    $insert = $conn->prepare("INSERT INTO point_requests (user_id, requested_points, ewallet_provider, ewallet_account) VALUES (?,?,?,?)");
    $insert->bind_param("iiss", $user_id, $points, $provider, $account);
    $insert->execute();
    $insert->close();

    $_SESSION['msg_success'] = "✅ Permintaan penukaran poin berhasil dikirim! Tunggu persetujuan admin.";
    header("Location: tukar_poin.php");
    exit;
}
?>

<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Tukar Poin | EcoCollect</title>
<meta name="viewport" content="width=device-width,initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
body{font-family:'Poppins',sans-serif;background:#f1f6ef;padding:20px}
.exchange-form{background:#fff;max-width:640px;margin:24px auto;padding:28px;border-radius:12px;box-shadow:0 10px 30px rgba(0,0,0,0.08)}
.exchange-form h2{color:#1b5e20;margin:0 0 6px}
.input-group{margin-bottom:14px;text-align:left}
.input-group label{display:block;margin-bottom:6px;font-weight:600;color:#2e7d32}
.input-group input[type="number"], .input-group input[type="text"]{width:100%;padding:10px;border-radius:8px;border:1px solid #dfeee0}
.wallet-options{display:flex;gap:18px;align-items:center}
.wallet-options label{cursor:pointer;display:inline-block;border-radius:10px;padding:6px;transition:transform .15s}
.wallet-options img{width:72px;border-radius:8px;border:2px solid transparent}
.wallet-options input[type="radio"]{display:none}
.wallet-options input[type="radio"]:checked + img{border-color:#43a047;box-shadow:0 8px 18px rgba(67,160,71,0.14)}
.btn-submit{width:100%;background:#43a047;color:white;padding:12px;border-radius:10px;border:none;font-weight:700;cursor:pointer;margin-top:12px}
.btn-submit:disabled{opacity:.6;cursor:not-allowed}
.message{margin-top:16px;padding:12px;border-radius:8px;font-weight:600}
.message.success{background:#e8f5e9;color:#2e7d32}
.message.error{background:#ffebee;color:#c62828}
</style>
</head>
<body>

<form method="post" class="exchange-form" id="exchangeForm">
<h2>💰 Tukar Poin Jadi Saldo E-Wallet</h2>

<?php
if (!empty($_SESSION['msg_success'])) {
    echo "<div class='message success'>" . htmlspecialchars($_SESSION['msg_success']) . "</div>";
    unset($_SESSION['msg_success']);
}
if (!empty($_SESSION['msg_error'])) {
    echo "<div class='message error'>" . htmlspecialchars($_SESSION['msg_error']) . "</div>";
    unset($_SESSION['msg_error']);
}
?>

<div class="input-group">
<label>Jumlah Poin (Anda punya: <strong><?= htmlspecialchars($user['poin']) ?></strong>)</label>
<input type="number" name="requested_points" id="requested_points" min="1" max="<?= $user['poin'] ?>" required>
</div>

<div class="input-group">
<label>Pilih E-Wallet:</label>
<div class="wallet-options">
<label><input type="radio" name="ewallet_provider" value="DANA" required><img src="dana.jpg" alt="DANA"></label>
<label><input type="radio" name="ewallet_provider" value="OVO"><img src="ovo.jpg" alt="OVO"></label>
<label><input type="radio" name="ewallet_provider" value="GOPAY"><img src="gopay.jpg" alt="GOPAY"></label>
</div>
</div>

<div class="input-group">
<label>Nomor Akun / HP E-Wallet</label>
<input type="text" name="ewallet_account" placeholder="Contoh: 081234567890" required>
</div>

<button type="submit" class="btn-submit" id="submitBtn">💸 Buat Request Penukaran</button>

</form>

</body>
</html>
 