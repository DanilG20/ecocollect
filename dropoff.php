<?php
session_start();
include "db.php"; // koneksi database

$success = '';
$error = '';
if (!isset($_SESSION['user_id'])) {
    echo "<script>
        alert('Silakan login terlebih dahulu!');
        window.location.href = 'login.php';
    </script>";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST['nama'] ?? '');
    $lokasi = trim($_POST['lokasi'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '');
    $jenis_sampah = trim($_POST['jenis_sampah'] ?? '');

    if (empty($nama) || empty($lokasi) || empty($tanggal) || empty($jenis_sampah)) {
        $error = "❌ Semua field harus diisi.";
    } else {
        $user_id = $_SESSION['user_id'] ?? 0;
        $status = 'pending';
        $poin = 0;

        // Simpan ke tabel dropoff
        $stmt_dropoff = $conn->prepare("
            INSERT INTO dropoff (user_id, nama, lokasi, tanggal, jenis_sampah, status, poin)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt_dropoff) {
            $stmt_dropoff->bind_param("isssssi", $user_id, $nama, $lokasi, $tanggal, $jenis_sampah, $status, $poin);

            if ($stmt_dropoff->execute()) {
                // Simpan juga ke tabel history
                $jenis = 'dropoff';
                $stmt_history = $conn->prepare("
                    INSERT INTO history (user_id, jenis_transaksi, nama, lokasi, jenis_sampah, tanggal, status, poin)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                if ($stmt_history) {
                    $stmt_history->bind_param("issssssi", $user_id, $jenis, $nama, $lokasi, $jenis_sampah, $tanggal, $status, $poin);
                    $stmt_history->execute();
                    $stmt_history->close();
                }

                $success = "✅ Data drop off berhasil dikirim dan menunggu verifikasi di 
                            <a href='history.php' class='message-link'><b>History</b></a>. 
                            Anda akan mendapatkan poin setelah status menjadi <b>selesai</b>.";
            } else {
                $error = "Gagal menyimpan data dropoff: " . $stmt_dropoff->error;
            }

            $stmt_dropoff->close();
        } else {
            $error = "Query dropoff gagal: " . $conn->error;
        }
    }
}

$old_nama = htmlspecialchars($_POST['nama'] ?? '');
$old_lokasi = htmlspecialchars($_POST['lokasi'] ?? '');
$old_tanggal = htmlspecialchars($_POST['tanggal'] ?? '');
$old_jenis_sampah = htmlspecialchars($_POST['jenis_sampah'] ?? '');
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Drop Off Sampah - EcoCollect</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-green: #4caf50;
      --dark-green: #2e7d32;
      --light-bg: #e8f5e9;
      --card-bg: #ffffff;
      --text-color: #333333;
      --error-color: #d32f2f;
      --success-color: #388e3c;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light-bg);
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      min-height: 100vh;
    }

    header {
      background: var(--dark-green);
      width: 100%;
      padding: 20px 0;
      color: var(--card-bg);
      text-align: center;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    header h1 {
      margin: 0;
      font-size: 28px;
      font-weight: 600;
    }

    .form-container {
      background: var(--card-bg);
      padding: 30px;
      max-width: 500px;
      width: 90%;
      margin: 40px auto;
      border-radius: 16px;
      box-shadow: 0 15px 35px rgba(0,0,0,0.15), 0 5px 15px rgba(0,0,0,0.05);
      transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .form-container:hover {
      transform: translateY(-5px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.2), 0 7px 20px rgba(0,0,0,0.1);
    }

    .form-container h2 {
      text-align: center;
      margin-bottom: 25px;
      color: var(--dark-green);
      font-weight: 700;
      font-size: 24px;
    }

    .form-container label {
      font-weight: 600;
      margin-bottom: 5px;
      display: block;
      color: var(--text-color);
      font-size: 14px;
      margin-top: 10px;
    }

    .form-container input,
    .form-container select {
      width: 100%;
      padding: 12px 15px;
      border-radius: 8px;
      border: 1px solid #c8e6c9;
      background-color: #f7fff7;
      margin-bottom: 15px;
      box-sizing: border-box;
      font-size: 15px;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-container input:focus,
    .form-container select:focus {
      border-width: 2px;
      border-color: var(--dark-green);
      box-shadow: 0 0 8px rgba(46, 125, 50, 0.6);
      outline: none;
    }

    .form-container button {
      width: 100%;
      padding: 16px;
      border: none;
      background: var(--primary-green);
      color: white;
      font-weight: 700;
      border-radius: 8px;
      cursor: pointer;
      margin-top: 20px;
      font-size: 18px;
      letter-spacing: 0.5px;
      box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
      transition: background 0.3s, box-shadow 0.3s, transform 0.3s;
    }

    .form-container button:hover {
      background: var(--dark-green);
      box-shadow: 0 8px 20px rgba(46, 125, 50, 0.5);
      transform: translateY(-3px);
    }

    .message-box {
      text-align: center;
      margin-bottom: 20px;
      padding: 15px;
      border-radius: 8px;
      font-weight: 600;
    }

    .success-message {
      background: #e6ffe6;
      border: 1px solid var(--primary-green);
      color: var(--success-color);
    }

    .error-message {
      background: #ffe6e6;
      border: 1px solid var(--error-color);
      color: var(--error-color);
    }

    footer {
      background: var(--dark-green);
      color: white;
      text-align: center;
      padding: 15px;
      width: 100%;
      margin-top: auto;
      font-weight: 300;
      font-size: 14px;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Formulir Drop Off</h2>

  <?php if (!empty($success)): ?>
      <p class='message-box success-message'><?= $success ?></p>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
      <p class='message-box error-message'><?= $error ?></p>
  <?php endif; ?>

  <form method="POST" action="">
      <label for="nama">Nama Lengkap</label>
      <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required value="<?= $old_nama ?>">

      <label for="lokasi">Lokasi Drop Off</label>
      <select id="lokasi" name="lokasi" required>
          <option value="">-- Pilih Lokasi Drop Off --</option>
          <option value="Bank Sampah Palembang" <?= $old_lokasi == 'Bank Sampah Palembang' ? 'selected' : '' ?>>Bank Sampah Palembang</option>
          <option value="Bank Sampah Bukit Siguntang" <?= $old_lokasi == 'Bank Sampah Bukit Siguntang' ? 'selected' : '' ?>>Bank Sampah Bukit Siguntang</option>
          <option value="Bank Sampah Kuto Besak" <?= $old_lokasi == 'Bank Sampah Kuto Besak' ? 'selected' : '' ?>>Bank Sampah Kuto Besak</option>
          <option value="Bank Sampah Ampera" <?= $old_lokasi == 'Bank Sampah Ampera' ? 'selected' : '' ?>>Bank Sampah Ampera</option>
          <option value="Bank Sampah Plaju" <?= $old_lokasi == 'Bank Sampah Plaju' ? 'selected' : '' ?>>Bank Sampah Plaju</option>
          <option value="Bank Sampah Jakabaring" <?= $old_lokasi == 'Bank Sampah Jakabaring' ? 'selected' : '' ?>>Bank Sampah Jakabaring</option>
      </select>

      <label for="tanggal">Tanggal Drop Off</label>
      <input type="date" id="tanggal" name="tanggal" required value="<?= $old_tanggal ?>">

      <label for="jenis_sampah">Jenis Sampah Utama</label>
      <select id="jenis_sampah" name="jenis_sampah" required>
          <option value="">-- Pilih Jenis Sampah --</option>
          <option value="Plastik" <?= $old_jenis_sampah == 'Plastik' ? 'selected' : '' ?>>Plastik</option>
          <option value="Kertas" <?= $old_jenis_sampah == 'Kertas' ? 'selected' : '' ?>>Kertas</option>
          <option value="Logam" <?= $old_jenis_sampah == 'Logam' ? 'selected' : '' ?>>Logam</option>
          <option value="Kaca" <?= $old_jenis_sampah == 'Kaca' ? 'selected' : '' ?>>Kaca</option>
          <option value="Organik" <?= $old_jenis_sampah == 'Organik' ? 'selected' : '' ?>>Organik</option>
          <option value="Elektronik" <?= $old_jenis_sampah == 'Elektronik' ? 'selected' : '' ?>>Elektronik</option>
      </select>

      <button type="submit">Kirim Drop Off Sekarang</button>
  </form>
</div>

<footer>
  <p>Tukar sampah Anda menjadi poin di EcoCollect.</p>
</footer>

</body>
</html>
