<?php
// Pastikan file koneksi database ini ada
require 'db.php'; 

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $name = trim($_POST['name']);
  $email = trim($_POST['email']);
  $password_input = $_POST['password'];

  // Validasi sederhana
  if (empty($name) || empty($email) || empty($password_input)) {
      $error = "Semua field harus diisi.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $error = "Format email tidak valid.";
  } elseif (strlen($password_input) < 6) {
      $error = "Password minimal 6 karakter.";
  } else {
      // Hash password
      $password = password_hash($password_input, PASSWORD_DEFAULT);

      // Cek apakah email sudah terdaftar
      $check_stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
      $check_stmt->bind_param("s", $email);
      $check_stmt->execute();
      $check_stmt->store_result();

      if ($check_stmt->num_rows > 0) {
          $error = "Email **$email** sudah terdaftar. Silakan **Login**.";
      } else {
          // Lakukan registrasi, inisialisasi poin dengan 0
          $stmt = $conn->prepare("INSERT INTO users (name, email, password, poin) VALUES (?, ?, ?, 0)");
          $stmt->bind_param("sss", $name, $email, $password);
          
          if ($stmt->execute()) {
              // Sukses, redirect ke halaman login dengan parameter sukses
              // login.php akan menampilkan pesan "Akun berhasil dibuat! Silakan Login."
              header("Location: login.php?success=1");
              exit;
          } else {
              // Error database lainnya
              $error = "Gagal mendaftar. Silakan coba lagi.";
          }
      }
      $check_stmt->close();
  }
}
// Untuk mempertahankan input yang valid jika terjadi error
$old_name = htmlspecialchars($_POST['name'] ?? '');
$old_email = htmlspecialchars($_POST['email'] ?? '');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar | EcoCollect</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary-green: #4caf50; /* Hijau utama */
      --dark-green: #2e7d32; /* Hijau gelap untuk hover */
      --light-bg: #e8f5e9; /* Background sangat terang */
      --card-bg: #ffffff; /* Background card */
      --text-color: #333333;
      --error-color: #f44336;
    }

    body {
      font-family: 'Poppins', sans-serif;
      background: var(--light-bg);
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
      box-sizing: border-box;
    }

    .register-container {
      background: var(--card-bg);
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
      text-align: center;
      transition: transform 0.3s ease;
    }

    .register-container:hover {
        transform: translateY(-5px);
    }

    h2 {
      color: var(--dark-green);
      margin-bottom: 30px;
      font-weight: 700;
    }

    h2::before {
      content: "🌱"; 
      margin-right: 8px;
    }

    .input-group {
      margin-bottom: 20px;
      text-align: left;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px 15px;
      margin-top: 5px;
      border: 1px solid #ddd;
      border-radius: 8px;
      box-sizing: border-box; 
      font-size: 16px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }

    input:focus {
      border-color: var(--primary-green);
      box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
      outline: none;
    }

    button[type="submit"] {
      background: var(--primary-green);
      color: var(--card-bg);
      border: none;
      padding: 12px;
      width: 100%;
      border-radius: 8px;
      font-size: 18px;
      font-weight: 600;
      cursor: pointer;
      margin-top: 10px;
      transition: background 0.3s ease, box-shadow 0.3s ease;
    }

    button[type="submit"]:hover {
      background: var(--dark-green);
      box-shadow: 0 4px 10px rgba(46, 125, 50, 0.3);
    }

    .error-message {
      color: var(--error-color);
      margin-bottom: 15px;
      font-weight: 600;
      background: #ffebee;
      padding: 10px;
      border-radius: 5px;
    }

    p a {
      color: var(--primary-green);
      text-decoration: none;
      font-weight: 600;
      transition: color 0.3s ease;
    }

    p a:hover {
      color: var(--dark-green);
      text-decoration: underline;
    }
  </style>
</head>
<body>
<div class="register-container">
  <form method="post">
    <h2>Daftar Akun EcoCollect</h2>
    <?php 
    // Tampilkan pesan error jika ada
    if(!empty($error)): 
    ?>
      <p class='error-message'>
        <?php 
          // Mengganti teks bold dari error message dengan tag HTML untuk styling yang benar
          echo str_replace(['**', '##'], ['<b>', '</b>'], $error); 
        ?>
      </p>
    <?php endif; ?>

    <div class="input-group">
      <input type="text" name="name" placeholder="Nama Lengkap" required value="<?= $old_name ?>">
    </div>
    
    <div class="input-group">
      <input type="email" name="email" placeholder="Alamat Email" required value="<?= $old_email ?>">
    </div>
    
    <div class="input-group">
      <input type="password" name="password" placeholder="Buat Kata Sandi (min. 6 karakter)" required>
    </div>
    
    <button type="submit">Buat Akun Baru</button>
    
    <p style="margin-top: 25px; color: var(--text-color);">
      Sudah punya akun? <a href="login.php">Login di Sini</a>
    </p>
  </form>
</div>
</body>
</html>
