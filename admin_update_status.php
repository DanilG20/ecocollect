<?php
include "db.php";
session_start();

$id = $_GET['name'];
$new_status = $_GET['status']; // 'in_progress' atau 'selesai'
$points_to_add = 10;

$conn->begin_transaction();

try {
    // Update status pickup
    $stmt = $conn->prepare("UPDATE pickup SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);
    $stmt->execute();
    $stmt->close();

    // Jika status selesai → beri poin
    if ($new_status == 'selesai') {
        $stmt_user = $conn->prepare("SELECT user_id FROM pickup WHERE id = ?");
        $stmt_user->bind_param("i", $id);
        $stmt_user->execute();
        $stmt_user->bind_result($user_id);
        $stmt_user->fetch();
        $stmt_user->close();

        // Tambahkan transaksi poin
        $stmt_trans = $conn->prepare("INSERT INTO transactions (user_id, pickup_id, type, points) VALUES (?, ?, 'dropoff', ?)");
        $stmt_trans->bind_param("iii", $user_id, $id, $points_to_add);
        $stmt_trans->execute();
        $stmt_trans->close();

        // Update total poin user
        $stmt_up = $conn->prepare("UPDATE users SET points = points + ? WHERE id = ?");
        $stmt_up->bind_param("ii", $points_to_add, $user_id);
        $stmt_up->execute();
        $stmt_up->close();
    }

    $conn->commit();
    echo "Status berhasil diubah menjadi '$new_status'";
} catch (Exception $e) {
    $conn->rollback();
    echo "Terjadi kesalahan: " . $e->getMessage();
}
