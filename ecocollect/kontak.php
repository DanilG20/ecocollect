<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $_POST['nama'];
  $email = $_POST['email'];
  $pesan = $_POST['pesan'];

  $sql = "INSERT INTO kontak (nama, email, pesan) VALUES ('$nama', '$email', '$pesan')";
  if ($conn->query($sql) === TRUE) {
    $success = "Pesan Anda berhasil dikirim!";
  } else {
    $error = "Terjadi kesalahan: " . $conn->error;
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kontak Kami - EcoCollect</title>
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Manrope', sans-serif;
      margin: 0;
      padding: 0;
      background: #f4f9f4;
    }
    header {
      background: #C7CCA9;
      padding: 15px 30px;
      color: white;
      text-align: center;
    }
    header h1 {
      margin: 0;
      font-size: 28px;
    }
    .container {
      max-width: 700px;
      margin: 40px auto;
      padding: 20px;
      background: white;
      border-radius: 12px;
      box-shadow: 0 6px 15px rgba(0,0,0,0.12);
    }
    h2 {
      text-align: center;
      margin-bottom: 20px;
      color: #2e7d32;
    }
    form {
      display: flex;
      flex-direction: column;
    }
    label {
      margin-bottom: 5px;
      font-weight: bold;
      color: #444;
    }
    input, textarea {
      margin-bottom: 15px;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 15px;
    }
    textarea {
      resize: vertical;
      min-height: 120px;
    }
    button {
      background: #2e7d32;
      color: white;
      border: none;
      padding: 12px;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      transition: 0.3s;
    }
    button:hover {
      background: #1b5e20;
    }
    .message {
      text-align: center;
      margin-bottom: 20px;
      font-weight: bold;
      color: #2e7d32;
    }
    .error {
      color: red;
      text-align: center;
    }
    footer {
      background: #C7CCA9;
      color: white;
      text-align: center;
      padding: 20px;
      margin-top: 40px;
    }
  </style>
</head>
<body>

<header>
  <h1>Kontak Kami</h1>
</header>

<div class="container">
  <h2>Kirim Pesan</h2>

  <?php if (isset($success)) echo "<p class='message'>$success</p>"; ?>
  <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

  <form method="POST" action="">
    <label for="nama">Nama Lengkap</label>
    <input type="text" id="nama" name="nama" required>

    <label for="email">Email</label>
    <input type="email" id="email" name="email" required>

    <label for="pesan">Pesan</label>
    <textarea id="pesan" name="pesan" required></textarea>

    <button type="submit">Kirim</button>
  </form>
</div>

<footer>
  <p>© 2025 EcoCollect. Semua Hak Dilindungi.</p>
</footer>

</body>
</html>
