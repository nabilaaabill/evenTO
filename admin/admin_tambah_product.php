<?php
// admin_product.php
session_start();
require '../koneksi/config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ambil dan bersihkan input
    $nama      = trim($_POST['nama']      ?? '');
    $kategori  = trim($_POST['kategori']  ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga     = (float) ($_POST['harga'] ?? 0);
    $diskon    = (int)   ($_POST['diskon'] ?? 0);
    $status    = trim($_POST['status']    ?? '');

    // validasi sederhana
    if ($nama === '' || $kategori === '' || $deskripsi === '') {
        $message = '<div class="alert error">Nama, kategori & deskripsi wajib diisi.</div>';
    } else {
        // handle upload file
        $imgName = '';
        if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $tmp  = $_FILES['foto']['tmp_name'];
            $imgName = basename($_FILES['foto']['name']);
            move_uploaded_file($tmp, __DIR__ . "/../uploads/" . $imgName);
        }

        // INSERT ke database
        $sql = "INSERT INTO paket
                (nama, kategori, deskripsi, harga, diskon, status, foto)
                VALUES
                (:nama, :kategori, :deskripsi, :harga, :diskon, :status, :foto)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nama'      => $nama,
            ':kategori'  => $kategori,
            ':deskripsi' => $deskripsi,
            ':harga'     => $harga,
            ':diskon'    => $diskon,
            ':status'    => $status,
            ':foto'      => $imgName
        ]);
        $message = '<div class="alert success">Produk berhasil ditambahkan!</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Tambah Produk – Admin</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
  <header class="header">
    <h1>Tambah Produk</h1>
    <a href="index.php" class="btn-back">← Dashboard</a>
  </header>

  <div class="container">
    <?= $message ?>

    <div class="form-container">
      <form method="post" enctype="multipart/form-data" class="admin-form">
        <div class="form-group">
          <label>Nama Produk</label>
          <input type="text" name="nama" value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label>Kategori</label>
          <input type="text" name="kategori" value="<?= htmlspecialchars($_POST['kategori'] ?? '') ?>" required>
        </div>

        <div class="form-group">
          <label>Deskripsi</label>
          <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
        </div>

        <div class="form-grid">
          <div class="form-group small">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>" required>
          </div>
          <div class="form-group small">
            <label>Diskon (%)</label>
            <input type="number" name="diskon" min="0" max="100" value="<?= htmlspecialchars($_POST['diskon'] ?? '0') ?>">
          </div>
          <div class="form-group small">
            <label>Status</label>
            <select name="status">
              <option value="">– Pilih –</option>
              <option value="new" <?= (($_POST['status'] ?? '')==='new')?'selected':'' ?>>New</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Gambar Produk</label>
          <input type="file" name="foto" accept="image/*" required>
        </div>

        <button type="submit" class="btn-save">Simpan Produk</button>
      </form>
    </div>
  </div>
</body>
</html>
