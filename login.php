<?php
session_start();
require 'db.php';

$error = '';
$success_message = '';

if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = "🥳 Akun berhasil dibuat! Silakan login.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, name, email, password, poin, role FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows === 1) {
        $user = $res->fetch_assoc();

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['poin'] = $user['poin'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | EcoCollect</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --primary-green: #4caf50;
  --dark-green: #2e7d32;
  --light-bg: #e8f5e9;
  --card-bg: #ffffff;
  --text-color: #333333;
  --error-color: #f44336;
  --success-color: #388e3c;
}

body {
  font-family: 'Poppins', sans-serif;
  background: var(--light-bg);
  display: flex;
  align-items: center;
  justify-content: center;
  min-height: 100vh;
  margin: 0;
}

.login-container {
  background: var(--card-bg);
  padding: 40px;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.1);
  width: 100%;
  max-width: 400px;
  text-align: center;
}
h2 { color: var(--dark-green); margin-bottom: 30px; }

input {
  width: 100%;
  padding: 12px;
  margin: 10px 0;
  border-radius: 8px;
  border: 1px solid #ccc;
}

button {
  background: var(--primary-green);
  color: white;
  border: none;
  padding: 12px;
  border-radius: 8px;
  width: 100%;
  font-size: 16px;
  cursor: pointer;
}

button:hover { background: var(--dark-green); }

.error-message {
  color: var(--error-color);
  background: #ffebee;
  padding: 10px;
  border-radius: 5px;
}
.success-message {
  color: var(--success-color);
  background: #e8f5e9;
  padding: 10px;
  border-radius: 5px;
}
</style>
</head>
<body>
<div class="login-container">
  <form method="post">
    <h2>🌿 Login ke EcoCollect</h2>
    <?php if($success_message): ?><p class="success-message"><?= $success_message ?></p><?php endif; ?>
    <?php if($error): ?><p class="error-message"><?= $error ?></p><?php endif; ?>

    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Kata Sandi" required>
    <button type="submit">Masuk</button>

    <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
  </form>
</div>
</body>
</html>
