<?php
// admin_dashboard.php
session_start();
require '../koneksi/config.php';

// Hapus paket jika ada parameter delete_paket
if (isset($_GET['delete_paket']) && ctype_digit($_GET['delete_paket'])) {
    $pid = (int)$_GET['delete_paket'];

    // Hapus file foto
    $stmt0 = $pdo->prepare("SELECT foto FROM paket WHERE id = ?");
    $stmt0->execute([$pid]);
    if ($r = $stmt0->fetch()) {
        $file = __DIR__ . "/../uploads/" . $r['foto'];
        if (file_exists($file)) unlink($file);
    }

    // Hapus record paket
    $pdo->prepare("DELETE FROM paket WHERE id = ?")->execute([$pid]);
    header('Location: admin_dashboard.php');
    exit;
}

// Hitung total paket
$totalPaket = $pdo->query("SELECT COUNT(*) FROM paket")->fetchColumn();
// Hitung total pemesanan
$totalOrder  = $pdo->query("SELECT COUNT(*) FROM pemesanan")->fetchColumn();
// Hitung total pesan kontak
$totalMsg    = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();

// Ambil 5 pemesanan terbaru berdasarkan id (karena tidak ada kolom created_at)
$stmtOrders = $pdo->query(
    "SELECT o.id, p.nama AS paket, o.nama AS customer, o.tanggal_event, o.total
     FROM pemesanan o
     JOIN paket p ON o.paket_id = p.id
     ORDER BY o.id DESC LIMIT 5"
);
$orders = $stmtOrders->fetchAll();

// Ambil 5 paket terbaru berdasarkan id
$stmtPakets = $pdo->query(
    "SELECT * FROM paket ORDER BY id DESC LIMIT 5"
);
$pakets = $stmtPakets->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard – Timeless Event</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

 <header class="header">
    <h1>Admin Dashboard</h1>
    <div>
      <span>Halo, <?= isset($_SESSION['fullname']) ? htmlspecialchars($_SESSION['fullname']) : 'Admin' ?></span>
      &nbsp;|&nbsp;
      <a href="../koneksi/logout.php" class="logout">Logout</a>
    </div>
</header>


  <div class="container">
    <div class="cards">
      <div class="card">
        <h3>Total Paket</h3>
        <p><?= $totalPaket ?></p>
        <a href="#paket-list">Lihat Paket</a>
      </div>
      <div class="card">
        <h3>Total Pemesanan</h3>
        <p><?= $totalOrder ?></p>
        <a href="#pesanan-list">Lihat Pesanan</a>
      </div>
      <div class="card">
        <h3>Pesan Masuk</h3>
        <p><?= $totalMsg ?></p>
        <a href="admin_contacts.php">Lihat Pesan</a>
      </div>
    </div>

    <!-- Section Tambah & Daftar Paket -->
    <section id="paket-list">
      <h2>Paket Terbaru</h2>
      <a href="admin_tambah_product.php" class="btnadd">+ Tambah Produk</a>
      <table>
        <thead>
          <tr>
            <th>ID</th><th>Nama</th><th>Kategori</th><th>Harga</th><th>Diskon</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($pakets): foreach($pakets as $p): ?>
          <tr>
            <td><?= $p['id'] ?></td>
            <td><?= htmlspecialchars($p['nama']) ?></td>
            <td><?= htmlspecialchars($p['kategori']) ?></td>
            <td>Rp <?= number_format($p['harga'],0,',','.') ?></td>
            <td><?= $p['diskon'] ?>%</td>
            <td>
              <a href="admin_edit_product.php?id=<?= $p['id'] ?>" class="btn">Edit</a>
              <a href="?delete_paket=<?= $p['id'] ?>" class="btndelete"
                 onclick="return confirm('Hapus paket <?= addslashes($p['nama']) ?>?')">
                Hapus
              </a>
            </td>
          </tr>
        <?php endforeach; else: ?>
          <tr><td colspan="6">Belum ada paket.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </section>

    <!-- Section Pesanan -->
    <section id="pesanan-list">
      <h2>5 Pesanan Terbaru</h2>
      <?php if ($orders): ?>
      <table>
        <thead>
          <tr>
            <th>#Order</th><th>Paket</th><th>Customer</th><th>Tanggal</th><th>Total</th><th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach($orders as $o): ?>
          <tr>
            <td><?= $o['id'] ?></td>
            <td><?= htmlspecialchars($o['paket']) ?></td>
            <td><?= htmlspecialchars($o['customer']) ?></td>
            <td><?= htmlspecialchars($o['tanggal_event']) ?></td>
            <td>Rp <?= number_format($o['total'],0,',','.') ?></td>
            <td>
              <a href="order_detail.php?id=<?= $o['id'] ?>" class="btn">Detail</a>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <p>Tidak ada pesanan.</p>
      <?php endif; ?>
    </section>

  </div>

</body>
</html>
