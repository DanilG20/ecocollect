<?php
session_start();
require 'db.php';
if ($_SESSION['role'] !== 'admin') {
    header("Location: profile.php");
    exit;
}

$id = (int)$_POST['id'];
$action = $_POST['action'];

$status = ($action === 'approve') ? 'approved' : 'rejected';

$stmt = $conn->prepare("UPDATE tukar_poin_requests SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();
$stmt->close();

header("Location: admin_approval.php");
exit;
?>
