<?php
include "db.php";

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    $jenis = $_POST['jenis_transaksi'];

    if ($jenis == 'Pickup') {
        $table = 'pickup';
    } elseif ($jenis == 'Dropoff') {
        $table = 'dropoff';
    } else {
        die("Jenis transaksi tidak valid");
    }

    // Ambil data transaksi lama dulu (termasuk status_sebelumnya dan user_id)
    $cek = $conn->query("SELECT status, user_id FROM $table WHERE id = $id");
    if ($cek->num_rows == 0) {
        echo "<script>alert('Data tidak ditemukan di tabel $table'); window.location='admin_history.php';</script>";
        exit;
    }
    $data_lama = $cek->fetch_assoc();
    $status_sebelumnya = $data_lama['status'];
    $user_id = $data_lama['user_id'];

    // Update status
    $update = $conn->query("UPDATE $table SET status='$status' WHERE id=$id");

    // Jika status berubah dari Pending ke Selesai, tambahkan poin sesuai jenis transaksi
    if (strtolower($status_sebelumnya) == 'pending' && strtolower($status) == 'selesai') {
        $poin = 0;
        if (strtolower($jenis) == 'pickup') {
            $poin = 5;
        } elseif (strtolower($jenis) == 'dropoff') {
            $poin = 10;
        }

        if ($poin > 0) {
            // Update poin transaksi
            $conn->query("UPDATE $table SET poin = poin + $poin WHERE id=$id");

            // Hitung ulang total poin dari semua transaksi Selesai user ini
            $totalPoinQuery = "
                SELECT SUM(poin) AS total_poin FROM (
                    SELECT poin FROM pickup WHERE user_id = $user_id AND status = 'Selesai'
                    UNION ALL
                    SELECT poin FROM dropoff WHERE user_id = $user_id AND status = 'Selesai'
                ) AS all_poin_selesai
            ";
            $poinResult = $conn->query($totalPoinQuery);
            $total_poin = 0;
            if ($poinResult && $row = $poinResult->fetch_assoc()) {
                $total_poin = $row['total_poin'] ?? 0;
            }

            // Update total poin di tabel users
            $conn->query("UPDATE users SET poin = $total_poin WHERE id = $user_id");
        }
    }

    if ($update) {
        echo "<script>alert('Status berhasil diperbarui'); window.location='admin_history.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status');</script>";
    }
}


// Gabung data pickup dan dropoff
$sql = "SELECT id, 'Pickup' AS jenis_transaksi, nama, lokasi, tanggal, jenis_sampah, status, poin FROM pickup
        UNION
        SELECT id, 'Dropoff' AS jenis_transaksi, nama, lokasi, tanggal, jenis_sampah, status, poin FROM dropoff";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin - History Transaksi</title>
  <style>
    body { background-color: #e8f5e9; font-family: Arial; }
    table { width: 90%; margin: 50px auto; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
    th, td { padding: 10px; text-align: center; border-bottom: 1px solid #ddd; }
    th { background-color: #4CAF50; color: white; }
    .pending { color: orange; font-weight: bold; }
    .done { color: green; font-weight: bold; }
  </style>
</head>
<body>
  <h2 style="text-align:center; color:#2e7d32;">📋 Admin Panel - History Transaksi</h2>
  <table>
    <tr>
      <th>No</th>
      <th>Jenis</th>
      <th>Nama</th>
      <th>Lokasi</th>
      <th>Tanggal</th>
      <th>Jenis Sampah</th>
      <th>Status</th>
      <th>Poin</th>
      <th>Aksi</th>
    </tr>
    <?php
    if ($result->num_rows > 0) {
      $no = 1;
      while ($row = $result->fetch_assoc()) {
        echo "<tr>
          <td>{$no}</td>
          <td>{$row['jenis_transaksi']}</td>
          <td>{$row['nama']}</td>
          <td>{$row['lokasi']}</td>
          <td>{$row['tanggal']}</td>
          <td>{$row['jenis_sampah']}</td>
          <td class='".($row['status']=='Pending'?'pending':'done')."'>{$row['status']}</td>
          <td>{$row['poin']}</td>
          <td>
            <form method='POST'>
              <input type='hidden' name='id' value='{$row['id']}'>
              <input type='hidden' name='jenis_transaksi' value='{$row['jenis_transaksi']}'>
              <select name='status'>
                <option value='Pending' ".($row['status']=='Pending'?'selected':'').">Pending</option>
                <option value='Selesai' ".($row['status']=='Selesai'?'selected':'').">Selesai</option>
              </select>
              <button type='submit' name='update'>Update</button>
            </form>
          </td>
        </tr>";
        $no++;
      }
    } else {
      echo "<tr><td colspan='9'>Belum ada data</td></tr>";
    }
    ?>
  </table>
</body>
</html>
