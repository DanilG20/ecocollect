<?php
session_start();
include "db.php";

// Pastikan user sudah login (opsional)
$user_id = $_SESSION['user_id'] ?? 0;

// Ambil data pickup
$sql = "
    SELECT id, user_id, nama, lokasi, jenis_sampah, tanggal, status, poin, 'pickup' AS jenis_transaksi
    FROM pickup
    WHERE user_id = ?
    UNION ALL
    SELECT id, user_id, nama, lokasi, jenis_sampah, tanggal, status, poin, 'dropoff' AS jenis_transaksi
    FROM dropoff
    WHERE user_id = ?
    ORDER BY tanggal DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $user_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Transaksi - EcoCollect</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f1f8e9;
        margin: 0;
        padding: 0;
    }
    header {
        background-color: #2e7d32;
        color: white;
        text-align: center;
        padding: 20px 0;
        font-size: 22px;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    table {
        width: 90%;
        margin: 30px auto;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    th, td {
        padding: 14px 16px;
        text-align: center;
        border-bottom: 1px solid #e0e0e0;
    }
    th {
        background-color: #4caf50;
        color: white;
        font-weight: 600;
    }
    tr:hover {
        background-color: #f1f8e9;
    }
    .status {
        font-weight: bold;
        text-transform: capitalize;
    }
    .status.pending {
        color: #f57c00;
    }
    .status.selesai {
        color: #388e3c;
    }
    .status.ditolak {
        color: #d32f2f;
    }
    .no-data {
        text-align: center;
        padding: 20px;
        font-size: 16px;
        color: #666;
    }
    footer {
        background: #2e7d32;
        color: white;
        text-align: center;
        padding: 10px;
        margin-top: 30px;
        font-size: 14px;
        letter-spacing: 0.3px;
    }
</style>
</head>
<body>

<header>📜 Riwayat Pickup & Dropoff Anda</header>

<table>
    <tr>
        <th>No</th>
        <th>Jenis Transaksi</th>
        <th>Nama</th>
        <th>Lokasi</th>
        <th>Tanggal</th>
        <th>Jenis Sampah</th>
        <th>Status</th>
        <th>Poin</th>
    </tr>

    <?php
    if ($result && $result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$no}</td>
                    <td>{$row['jenis_transaksi']}</td>
                    <td>{$row['nama']}</td>
                    <td>{$row['lokasi']}</td>
                    <td>{$row['tanggal']}</td>
                    <td>{$row['jenis_sampah']}</td>
                    <td class='status {$row['status']}'>".$row['status']."</td>
                    <td>".($row['poin'] ?? 0)."</td>
                  </tr>";
            $no++;
        }
    } else {
        echo "<tr><td colspan='8' class='no-data'>Belum ada riwayat transaksi.</td></tr>";
    }
    ?>
</table>
<br></br><br></br><br></br><br></br><br></br>
<footer>
    <p>EcoCollect ♻️ - Tukar Sampah Jadi Poin, Jaga Bumi Bersih!</p>
</footer>

</body>
</html>
