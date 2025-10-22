<?php
require_once 'db.php';
if (empty($user)) { header('Location: login.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['transaksi_id'])) { header('Location: profile.php'); exit; }

$transaksi_id = (int)$_POST['transaksi_id'];
$stmt = $conn->prepare("SELECT id, jenis, status FROM transaksi WHERE id = ? AND user_id = ?");
$stmt->execute([$transaksi_id, $user['id']]);
$t = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$t) { $_SESSION['flash']='Transaksi tidak ditemukan.'; header('Location: profile.php'); exit; }
if ($t['status'] === 'selesai') { $_SESSION['flash']='Transaksi sudah selesai.'; header('Location: profile.php'); exit; }

$update = $conn->prepare("UPDATE transaksi SET status = 'selesai' WHERE id = ?");
$update->execute([$transaksi_id]);

$pointsToAdd = ($t['jenis'] === 'pickup') ? 5 : 10;
$upd = $conn->prepare("UPDATE users SET points = points + ? WHERE id = ?");
$upd->execute([$pointsToAdd, $user['id']]);

header('Location: profile.php');
exit;
