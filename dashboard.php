<?php
// Koneksi ke database
$koneksi = mysqli_connect("localhost", "root", "", "ecocollect");

// Cek koneksi
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Hitung jumlah pickup dan dropoff dari tabel masing-masing
$pickup = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM pickup"))['total'];
$dropoff = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM dropoff"))['total'];
$total = $pickup + $dropoff;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard EcoCollect</title>
    <style>
        * {
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        body {
            margin: 0;
            background-color: #f6faf7;
            color: #2e7d32;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        header {
            width: 100%;
            background-color: #C7CCA9;
            color: white;
            text-align: center;
            padding: 20px 0;
            font-size: 26px;
            letter-spacing: 1px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .container {
            margin-top: 60px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 40px;
        }

        .card {
            background-color: white;
            width: 280px;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(46, 125, 50, 0.3);
        }

        .icon {
            font-size: 45px;
            color: #2e7d32;
            margin-bottom: 15px;
        }

        h2 {
            font-size: 22px;
            margin: 10px 0;
        }

        p {
            font-size: 18px;
            color: #388e3c;
            font-weight: bold;
        }

        footer {
            margin-top: 60px;
            text-align: center;
            color: #666;
            font-size: 14px;
            padding: 20px;
        }
    </style>

    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>🌿 Dashboard EcoCollect</header>

    <div class="container">
        <div class="card">
            <div class="icon"><i class="fas fa-truck"></i></div>
            <h2>Pickup</h2>
            <p><?php echo $pickup; ?> Transaksi</p>
        </div>

        <div class="card">
            <div class="icon"><i class="fas fa-recycle"></i></div>
            <h2>Drop Off</h2>
            <p><?php echo $dropoff; ?> Transaksi</p>
        </div>

        <div class="card">
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <h2>Total Semua</h2>
            <p><?php echo $total; ?> Transaksi</p>
        </div>
    </div>

    <footer>
        © 2025 EcoCollect | Daur Ulang untuk Masa Depan Hijau 🌱
    </footer>
</body>
</html>
