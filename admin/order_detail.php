<?php
session_start();
require '../koneksi/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID pesanan tidak valid.');
}

$id = $_GET['id'];

$stmt = $pdo->prepare("
    SELECT 
        pemesanan.id AS id_pesanan,
        pemesanan.nama AS nama_pemesan,
        pemesanan.email,
        pemesanan.tanggal_event,
        pemesanan.qty,
        pemesanan.total,
        pemesanan.status,
        pemesanan.created_at,
        paket.nama AS nama_paket,
        paket.kategori,
        paket.harga,
        paket.diskon
    FROM pemesanan
    JOIN paket ON pemesanan.paket_id = paket.id
    WHERE pemesanan.id = ?
");
$stmt->execute([$id]);
$data = $stmt->fetch();

if (!$data) {
    die('Data pesanan tidak ditemukan.');
}

// Hitung harga akhir setelah diskon
function hitung_harga_setelah_diskon($harga, $diskon) {
    return $harga * (100 - $diskon) / 100;
}

$harga_diskon = hitung_harga_setelah_diskon($data['harga'], $data['diskon']);
$total_harga = $harga_diskon * $data['qty'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan - Admin</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            font-family: sans-serif;
            background: #f4f4f4;
            padding: 20px;
        }
        .detail-box {
            background: #fff;
            padding: 20px;
            max-width: 700px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            margin-top: 0;
        }
        table {
            width: 100%;
            border-spacing: 10px;
        }
        td {
            vertical-align: top;
        }
    </style>
</head>
<body>

<div class="detail-box">
    <h2>Detail Pesanan</h2>
    <table>
        <tr>
            <td><strong>ID Pesanan:</strong></td>
            <td><?= htmlspecialchars($data['id_pesanan']) ?></td>
        </tr>
        <tr>
            <td><strong>Nama Pemesan:</strong></td>
            <td><?= htmlspecialchars($data['nama_pemesan']) ?></td>
        </tr>
        <tr>
            <td><strong>Email:</strong></td>
            <td><?= htmlspecialchars($data['email']) ?></td>
        </tr>
        <tr>
            <td><strong>Paket:</strong></td>
            <td><?= htmlspecialchars($data['nama_paket']) ?> (<?= $data['kategori'] ?>)</td>
        </tr>
        <tr>
            <td><strong>Harga Paket:</strong></td>
            <td>Rp.<?= number_format($data['harga'], 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td><strong>Diskon:</strong></td>
            <td><?= $data['diskon'] ?>%</td>
        </tr>
        <tr>
            <td><strong>Harga Setelah Diskon:</strong></td>
            <td>Rp.<?= number_format($harga_diskon, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <td><strong>Jumlah Pesanan:</strong></td>
            <td><?= $data['qty'] ?> paket</td>
        </tr>
        <tr>
            <td><strong>Total Bayar:</strong></td>
            <td><strong>Rp.<?= number_format($total_harga, 0, ',', '.') ?></strong></td>
        </tr>
        <tr>
            <td><strong>Tanggal Event:</strong></td>
            <td><?= htmlspecialchars($data['tanggal_event']) ?></td>
        </tr>
        <tr>
            <td><strong>Status:</strong></td>
            <td><?= htmlspecialchars($data['status']) ?></td>
        </tr>
        <tr>
            <td><strong>Waktu Pemesanan:</strong></td>
            <td><?= htmlspecialchars($data['created_at']) ?></td>
        </tr>
    </table>
    <br>
    <a href="admin_index.php" class="btn">Kembali ke Dashboard</a>
</div>

</body>
</html>
