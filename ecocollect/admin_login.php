<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password, is_admin FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();
    $u = $res->fetch_assoc();
    $stmt->close();

    if ($u && password_verify($password, $u['password']) && (int)$u['is_admin'] === 1) {
        $_SESSION['admin_id'] = $u['id'];
        header("Location: admin_dashboard.php"); exit;
    } else {
        $error = "Login admin gagal.";
    }
}
?>
<!-- HTML form sederhana -->
<form method="post">
  <input name="email" type="email" required placeholder="admin email">
  <input name="password" type="password" required placeholder="password">
  <button type="submit">Login as Admin</button>
</form>
<?php if (isset($error)) echo $error; ?>
