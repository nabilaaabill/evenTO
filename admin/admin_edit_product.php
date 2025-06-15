<?php
// admin_product_edit.php
session_start();
require '../koneksi/config.php';

// validasi ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit;
}
$id = (int)$_GET['id'];

// ambil data lama
$stmt = $pdo->prepare("SELECT * FROM paket WHERE id = ?");
$stmt->execute([$id]);
$item = $stmt->fetch();
if (!$item) {
    die("Produk tidak ditemukan.");
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ambil input
    $nama      = trim($_POST['nama']      ?? '');
    $kategori  = trim($_POST['kategori']  ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $harga     = (float) ($_POST['harga'] ?? 0);
    $diskon    = (int)   ($_POST['diskon'] ?? 0);
    $status    = trim($_POST['status']    ?? '');

    // validasi
    if ($nama === '' || $kategori === '' || $deskripsi === '') {
        $errors[] = "Nama, kategori & deskripsi wajib diisi.";
    }

    if (empty($errors)) {
        // handle upload baru
        $fotoName = $item['foto'];
        if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            // hapus lama
            $oldFile = __DIR__ . '/../uploads/' . $fotoName;
            if ($fotoName && file_exists($oldFile)) unlink($oldFile);
            // simpan baru
            $fotoName = basename($_FILES['foto']['name']);
            move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/../uploads/' . $fotoName);
        }

        // update
        $sql = "UPDATE paket SET
                    nama      = :nama,
                    kategori  = :kategori,
                    deskripsi = :deskripsi,
                    harga     = :harga,
                    diskon    = :diskon,
                    status    = :status,
                    foto      = :foto
                WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nama'      => $nama,
            ':kategori'  => $kategori,
            ':deskripsi' => $deskripsi,
            ':harga'     => $harga,
            ':diskon'    => $diskon,
            ':status'    => $status,
            ':foto'      => $fotoName,
            ':id'        => $id
        ]);

        $success = "Produk berhasil diperbarui.";
        // reload
        $stmt = $pdo->prepare("SELECT * FROM paket WHERE id = ?");
        $stmt->execute([$id]);
        $item = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Produk #<?= $item['id'] ?> – Admin</title>
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>
  <header class="header">
    <h1>Edit Produk</h1>
    <a href="index.php" class="btn-back">← Dashboard</a>
  </header>

  <div class="container">
    <?php if ($success): ?>
      <div class="alert success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php foreach ($errors as $e): ?>
      <div class="alert error"><?= htmlspecialchars($e) ?></div>
    <?php endforeach; ?>

    <div class="form-container">
      <form method="post" enctype="multipart/form-data" class="admin-form">
        <div class="form-group">
          <label>Nama Produk</label>
          <input type="text" name="nama" value="<?= htmlspecialchars($item['nama']) ?>" required>
        </div>

        <div class="form-group">
          <label>Kategori</label>
          <input type="text" name="kategori" value="<?= htmlspecialchars($item['kategori']) ?>" required>
        </div>

        <div class="form-group">
          <label>Deskripsi</label>
          <textarea name="deskripsi" rows="5" required><?= htmlspecialchars($item['deskripsi']) ?></textarea>
        </div>

        <div class="form-grid">
          <div class="form-group small">
            <label>Harga (Rp)</label>
            <input type="number" name="harga" value="<?= htmlspecialchars($item['harga']) ?>" required>
          </div>
          <div class="form-group small">
            <label>Diskon (%)</label>
            <input type="number" name="diskon" min="0" max="100" value="<?= htmlspecialchars($item['diskon']) ?>">
          </div>
          <div class="form-group small">
            <label>Status</label>
            <select name="status">
              <option value="" <?= ($item['status']==='')?'selected':'' ?>>– Pilih –</option>
              <option value="new" <?= ($item['status']==='new')?'selected':'' ?>>New</option>
            </select>
          </div>
        </div>

        <div class="form-group">
          <label>Foto Lama</label>
          <?php if ($item['foto'] && file_exists(__DIR__ . '/../uploads/' . $item['foto'])): ?>
            <img src="../uploads/<?= htmlspecialchars($item['foto']) ?>" alt="Foto Produk" class="preview-img">
          <?php else: ?>
            <p>&ndash; Tidak ada –</p>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label>Ganti Foto (opsional)</label>
          <input type="file" name="foto" accept="image/*">
        </div>

        <button type="submit" class="btn-save">Update Produk</button>
      </form>
    </div>
  </div>
</body>
</html>
