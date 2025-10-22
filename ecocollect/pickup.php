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
    $lokasi = trim($_POST['alamat'] ?? '');
    $tanggal = trim($_POST['tanggal'] ?? '');
    $jenis_sampah = trim($_POST['jenis_sampah'] ?? '');

    if (empty($nama) || empty($lokasi) || empty($tanggal) || empty($jenis_sampah)) {
        $error = "❌ Semua field harus diisi.";
    } else {
        $user_id = $_SESSION['user_id'] ?? 0;
        $status = 'pending';
        $poin = 0;

        // Simpan ke tabel pickup
        $stmt = $conn->prepare("INSERT INTO pickup (user_id, nama, lokasi, jenis_sampah, tanggal, status, poin)
                                VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("isssssi", $user_id, $nama, $lokasi, $jenis_sampah, $tanggal, $status, $poin);

            if ($stmt->execute()) {
    // Simpan juga ke history
    $stmt_hist = $conn->prepare("INSERT INTO history 
        (user_id, jenis_transaksi, nama, lokasi, jenis_sampah, tanggal, status, poin)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $jenis_transaksi = 'pickup';
    $stmt_hist->bind_param("issssssi", $user_id, $jenis_transaksi, $nama, $lokasi, $jenis_sampah, $tanggal, $status, $poin);
    $stmt_hist->execute();
    $stmt_hist->close();

    $success = "✅ Data pickup berhasil dikirim dan menunggu verifikasi di 
                <a href='history.php' class='message-link'><b>History</b></a>. 
                Anda akan mendapatkan poin setelah status menjadi <b>selesai</b>.";
}
 else {
                $error = "Gagal menyimpan data pickup: " . $stmt->error;
            }

            $stmt->close();
        } else {
            $error = "Query pickup gagal: " . $conn->error;
        }
    }
}

$old_nama = htmlspecialchars($_POST['nama'] ?? '');
$old_lokasi = htmlspecialchars($_POST['alamat'] ?? '');
$old_tanggal = htmlspecialchars($_POST['tanggal'] ?? '');
$old_jenis_sampah = htmlspecialchars($_POST['jenis_sampah'] ?? '');
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pickup Sampah - EcoCollect</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-green: #4caf50; /* Hijau utama */
            --dark-green: #2e7d32; /* Hijau gelap untuk hover */
            --light-bg: #e8f5e9; /* Background sangat terang */
            --card-bg: #ffffff; /* Background card */
            --text-color: #333333;
            --error-color: #d32f2f; /* Merah untuk error */
            --success-color: #388e3c; /* Hijau untuk sukses */
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
            border-radius: 16px; /* Slightly more rounded */
            box-shadow: 0 15px 35px rgba(0,0,0,0.15), 0 5px 15px rgba(0,0,0,0.05); /* Deeper, layered shadow */
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
        
        /* Visual flourish for the title */
        .form-container h2::before {
            content: "🗓️"; 
            margin-right: 10px;
            font-size: 1.2em;
            vertical-align: middle;
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
            border: 1px solid #c8e6c9; /* Softer border color */
            background-color: #f7fff7; /* Very subtle light green background */
            margin-bottom: 15px;
            box-sizing: border-box;
            font-size: 15px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .form-container input:focus,
        .form-container select:focus {
            border-width: 2px; /* Thicker border on focus */
            border-color: var(--dark-green); /* Darker green border */
            box-shadow: 0 0 8px rgba(46, 125, 50, 0.6);
            outline: none;
        }

        .form-container button {
            width: 100%;
            padding: 16px; /* Slightly larger button */
            border: none;
            background: var(--primary-green);
            color: white;
            font-weight: 700; /* Bold font */
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 18px;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4); /* Default shadow */
            transition: background 0.3s, box-shadow 0.3s, transform 0.3s;
        }
        
        .form-container button:hover {
            background: var(--dark-green);
            box-shadow: 0 8px 20px rgba(46, 125, 50, 0.5); /* Deeper shadow on hover */
            transform: translateY(-3px); /* Prominent lift */
        }

        /* Message Styling */
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
        
        .success-message b {
            font-weight: 800;
        }
        
        .success-message .message-link {
            color: var(--dark-green);
            text-decoration: underline;
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
    <h2>Formulir Pengambilan</h2>

    <?php 
    // Tampilkan pesan sukses
    if (!empty($success)): 
    ?>
        <p class='message-box success-message'>
            <?php 
              // Mengganti teks bold dari success message dengan tag HTML untuk styling yang benar
              echo str_replace(['**', '🌱'], ['<b>', '🌱 '], $success); 
            ?>
        </p>
    <?php endif; ?>
    
    <?php 
    // Tampilkan pesan error
    if (!empty($error)): 
    ?>
        <p class='message-box error-message'><?= $error ?></p>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="nama">Nama Lengkap</label>
        <input type="text" id="nama" name="nama" placeholder="Masukkan nama Anda" required value="<?= $old_nama ?>">

        <label for="alamat">Alamat Lengkap</label>
        <input type="text" id="alamat" name="alamat" placeholder="Masukkan alamat lengkap Anda" required value="<?= $old_lokasi ?>">

        <label for="tanggal">Tanggal Pickup</label>
        <input type="date" id="tanggal" name="tanggal" required value="<?= $old_tanggal ?>">

        <label for="jenis_sampah">Jenis Sampah Utama</label>
        <select id="jenis_sampah" name="jenis_sampah" required>
            <option value="" <?= $old_jenis_sampah == '' ? 'selected' : '' ?>>-- Pilih Jenis Sampah --</option>
            <option value="Plastik" <?= $old_jenis_sampah == 'Plastik' ? 'selected' : '' ?>>Plastik</option>
            <option value="Kertas" <?= $old_jenis_sampah == 'Kertas' ? 'selected' : '' ?>>Kertas</option>
            <option value="Logam" <?= $old_jenis_sampah == 'Logam' ? 'selected' : '' ?>>Logam (Kaleng, Besi, dll)</option>
            <option value="Kaca" <?= $old_jenis_sampah == 'Kaca' ? 'selected' : '' ?>>Kaca</option>
            <option value="Organik" <?= $old_jenis_sampah == 'Organik' ? 'selected' : '' ?>>Organik</option>
            <option value="Elektronik" <?= $old_jenis_sampah == 'Elektronik' ? 'selected' : '' ?>>E-Waste (Elektronik)</option>
        </select>

        <button type="submit">Jadwalkan Pickup Sekarang</button>
    </form>
</div>

<footer>
  <p>Tukar sampah Anda menjadi poin di EcoCollect.</p>
</footer>

</body>
</html>

