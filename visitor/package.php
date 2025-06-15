<?php
// detail.php
require '../koneksi/config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header('Location: product.php');
  exit;
}
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM paket WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) {
  echo "Produk tidak ditemukan."; exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?php echo htmlspecialchars($p['nama']) ?> – Details</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

  <!-- Header (bisa include header.php jika ada) -->
  <header class="site-header">
    <div class="container">
      <a href="index.php" class="logo">TimeLess Event</a>
      <nav>
        <a href="../index.php">Back</a>
      </nav>
    </div>
  </header>

  <!-- Detail Produk -->
  <main class="main-container">
    <div class="package-detail">
      <div class="package-gallery">
        <img src="../uploads/<?php echo htmlspecialchars($p['foto']) ?>" alt="" />
        <!-- Jika ada lebih banyak gambar, loop di sini -->
      </div>
      <div class="package-info">
        <h1><?php echo htmlspecialchars($p['nama']) ?></h1>
        <div class="harga">
          Rp <?php echo number_format($p['harga'],0,',','.') ?>
          <?php if($p['diskon']>0): ?>
            <small>(-<?php echo $p['diskon'] ?>%)</small>
          <?php endif; ?>
        </div>
        <div class="package-description">
          <?php echo nl2br(htmlspecialchars($p['deskripsi'])) ?>
        </div>
      </div>
    </div>
  </main>

   <footer class="site-footer">
        <div class="footer-content">
            <div class="footer-logo">TimeLess EO</div>
            <div class="footer-links">
                <a href="../index.php">Home</a>
                <a href="visitor/list.php">Packages</a>
                <a href="visitor/about.php">About</a>
                <a href="visitor/contact.php">Contact</a>
                <a href="privacy.php">Privacy Policy</a>
            </div>
            <div class="social-links">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-pinterest"></i></a>
            </div>
            <div class="copyright">© 2025 Timeless EO. All rights reserved.</div>
        </div>
    </footer>

</body>
</html>
