<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}

$user_id = (int)$_SESSION['user_id'];
$stmt = $conn->prepare("SELECT id, name, email, poin, saldo FROM users WHERE id = ? LIMIT 1");
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
$user['poin'] = (int)($user['poin'] ?? 0);
?>
<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Tukar Poin | EcoCollect</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
  :root {
    --green: #2e7d32;
    --light-green: #e8f5e9;
    --accent: #43a047;
    --shadow: rgba(46, 125, 50, 0.2);
  }
  body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #d7f3dc, #f1f8f6);
    min-height: 100vh;
    padding: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .exchange-form {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    max-width: 640px;
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 10px 35px var(--shadow);
    padding: 32px 38px;
    animation: fadeIn 0.7s ease-in-out;
  }

  @keyframes fadeIn {
    from {opacity: 0; transform: translateY(10px);}
    to {opacity: 1; transform: translateY(0);}
  }

  .exchange-form h2 {
    font-weight: 700;
    color: var(--green);
    text-align: center;
    margin-bottom: 8px;
  }

  .desc {
    color: #555;
    text-align: center;
    margin-bottom: 24px;
    font-size: 0.95rem;
  }

  .input-group {
    margin-bottom: 18px;
  }

  .input-group label {
    font-weight: 600;
    color: var(--green);
    display: block;
    margin-bottom: 8px;
  }

  .input-group input {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #dfeee0;
    font-size: 1rem;
    transition: 0.2s;
  }

  .input-group input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(67, 160, 71, 0.2);
    outline: none;
  }

  .quick-buttons {
    margin-top: 8px;
  }

  .preset {
    background: var(--light-green);
    border: 1px solid #c8e6c9;
    color: var(--green);
    padding: 6px 14px;
    border-radius: 8px;
    font-weight: 600;
    margin-right: 6px;
    transition: 0.2s;
    cursor: pointer;
  }

  .preset:hover {
    background: var(--accent);
    color: #fff;
    transform: scale(1.05);
  }

  .conversion {
    background: #f9fff9;
    border: 1px solid #e3f6e6;
    border-radius: 14px;
    padding: 16px;
    margin-bottom: 20px;
    color: var(--green);
    box-shadow: 0 2px 8px rgba(46,125,50,0.08);
  }

  .converted {
    margin-top: 10px;
    font-size: 1.3rem;
    font-weight: 700;
  }

  .wallet-options {
    display: flex;
    gap: 18px;
    align-items: center;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 10px;
  }

  .wallet-options label {
    cursor: pointer;
    transition: transform 0.15s ease;
  }

  .wallet-options input[type="radio"] {
    display: none;
  }

  .wallet-options img {
    width: 80px;
    height: auto;
    border-radius: 10px;
    border: 2px solid transparent;
    transition: all 0.25s ease;
  }

  .wallet-options input[type="radio"]:checked + img {
    border-color: var(--accent);
    box-shadow: 0 6px 14px rgba(67,160,71,0.25);
    transform: scale(1.05);
  }

  .btn-submit {
    width: 100%;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 12px;
    padding: 14px;
    font-size: 1.05rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .btn-submit:hover:not(:disabled) {
    background: #2e7d32;
    transform: translateY(-2px);
    box-shadow: 0 8px 16px rgba(46,125,50,0.3);
  }

  .btn-submit:disabled {
    opacity: 0.6;
    cursor: not-allowed;
  }

  .message {
    margin-top: 16px;
    padding: 12px;
    border-radius: 8px;
    font-weight: 600;
    text-align: center;
  }

  .message.success {background: #e8f5e9; color: var(--green);}
  .message.error {background: #ffebee; color: #c62828;}
  .hint {margin-top: 8px; font-size: 0.95rem;}
</style>
</head>
<body>

<form method="post" action="tukar_poin_submit.php" class="exchange-form" id="exchangeForm">
  <h2>💰 Tukar Poin Jadi Saldo E-Wallet</h2>
  <p class="desc">Masukkan jumlah poin yang ingin ditukar — konversi akan dihitung otomatis.</p>

  <div class="input-group">
    <label>Jumlah Poin (Anda punya: <strong id="userPoints"><?= htmlspecialchars($user['poin']) ?></strong>)</label>
    <input type="number" name="requested_points" id="requested_points" min="1" placeholder="Misal: 50" required>
    <div class="quick-buttons">
      <button type="button" class="preset" data-points="50">50</button>
      <button type="button" class="preset" data-points="100">100</button>
      <button type="button" class="preset" data-points="200">200</button>
    </div>
    <div class="hint" id="pointsHint"></div>
  </div>

  <div class="conversion">
    <div>Nilai konversi: <strong id="rateLabel">1 poin = Rp200</strong></div>
    <div class="converted">Hasil: <span id="convertedAmount">Rp0</span></div>
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
    <label for="ewallet_account">Nomor Akun / HP E-Wallet</label>
    <input type="text" name="ewallet_account" id="ewallet_account" placeholder="Contoh: 081234567890" required>
  </div>

  <button type="submit" class="btn-submit" id="submitBtn">💸 Buat Request Penukaran</button>

  <?php
    if (!empty($_SESSION['msg_success'])) {
      echo "<div class='message success'>".htmlspecialchars($_SESSION['msg_success'])."</div>";
      unset($_SESSION['msg_success']);
    }
    if (!empty($_SESSION['msg_error'])) {
      echo "<div class='message error'>".htmlspecialchars($_SESSION['msg_error'])."</div>";
      unset($_SESSION['msg_error']);
    }
  ?>
</form>

<script>
(function(){
  const nilaiPerPoin = 200;
  document.getElementById('rateLabel').textContent = `1 poin = Rp${formatCurrency(nilaiPerPoin)}`;
  const userPoints = parseInt(document.getElementById('userPoints').textContent, 10) || 0;
  const requestedInput = document.getElementById('requested_points');
  const convertedEl = document.getElementById('convertedAmount');
  const hintEl = document.getElementById('pointsHint');
  const submitBtn = document.getElementById('submitBtn');
  const presets = document.querySelectorAll('.preset');

  function formatCurrency(num) {
    return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
  }

  function updateConversion() {
    let pts = parseInt(requestedInput.value || 0, 10);
    if (isNaN(pts) || pts <= 0) {
      convertedEl.textContent = 'Rp0';
      hintEl.textContent = '';
      submitBtn.disabled = true;
      return;
    }
    const rupiah = pts * nilaiPerPoin;
    convertedEl.textContent = 'Rp' + formatCurrency(rupiah);
    if (pts > userPoints) {
      hintEl.textContent = `⚠️ Anda hanya memiliki ${userPoints} poin. Kurangi jumlah poin.`;
      hintEl.style.color = '#c62828';
      submitBtn.disabled = true;
    } else {
      hintEl.textContent = `✅ Anda akan menerima Rp${formatCurrency(rupiah)}.`;
      hintEl.style.color = '#2e7d32';
      submitBtn.disabled = false;
    }
  }

  requestedInput.addEventListener('input', updateConversion);
  presets.forEach(btn => btn.addEventListener('click', () => {
    requestedInput.value = btn.dataset.points;
    updateConversion();
  }));

  updateConversion();

  document.getElementById('exchangeForm').addEventListener('submit', function(e){
    const pts = parseInt(requestedInput.value || 0, 10);
    if (isNaN(pts) || pts <= 0 || pts > userPoints) {
      e.preventDefault();
      alert('Jumlah poin tidak valid atau tidak cukup.');
    }
  });
})();
</script>
</body>
</html>
