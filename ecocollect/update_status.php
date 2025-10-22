<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$id = $_POST['id'];
$action = $_POST['action'];
$status = ($action === 'approve') ? 'approved' : 'rejected';

// Ambil data request
$stmt = $conn->prepare("SELECT user_id, requested_points FROM point_requests WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$request = $res->fetch_assoc();
$stmt->close();

if ($status === 'approved') {
    // Kurangi poin user
    $stmt = $conn->prepare("UPDATE users SET poin = poin - ? WHERE id = ?");
    $stmt->bind_param("ii", $request['requested_points'], $request['user_id']);
    $stmt->execute();
    $stmt->close();
}

// Update status request
$stmt = $conn->prepare("UPDATE point_requests SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();
$stmt->close();

$_SESSION['msg_success'] = "Status request berhasil diupdate.";
header("Location: admin_dashboard.php");
exit;
?>
