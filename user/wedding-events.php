<?php


require __DIR__ . '/../koneksi/config.php';

$kategori = 'Wedding';

// Ambil semua paket dengan kategori Wedding
$stmt = $pdo->prepare("SELECT * FROM paket WHERE kategori = ? ORDER BY id DESC");
$stmt->execute([$kategori]);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Wedding Packages – Timeless EO</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1 class="logo-text">Timeless EO</h1>
      <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
      </div>
      <nav id="menu">
        <a href="index.php">Home</a>
        <a href="list.php" class="active">List</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <span class="icons">
          <a href="../user/auth.php"><i class="fa-solid fa-user"></i></a>
        </span>
      </nav>
    </div>
  </header>

  <div class="main-container">
    <h2 class="section-title">Wedding Packages</h2>
    <div class="package-list">
      <?php if (empty($products)): ?>
        <p>Tidak ada paket di kategori Wedding.</p>
      <?php else: foreach($products as $p): ?>
        <div class="package-card">
          <img src="../uploads/<?= htmlspecialchars($p['foto']) ?>"
               alt="<?= htmlspecialchars($p['nama']) ?>"
               class="package-img">

          <div class="package-content">
            <h3 class="package-title"><?= htmlspecialchars($p['nama']) ?></h3>

            <div class="package-price">
              Rp <?= number_format($p['harga'], 0, ',', '.') ?>
              <?php if($p['diskon'] > 0): ?>
                <small>
                  (after <?= $p['diskon'] ?>% →  
                  Rp <?= number_format($p['harga'] * (100-$p['diskon'])/100,0,',','.') ?>)
                </small>
              <?php endif; ?>
            </div>

            <ul class="package-benefits">
              <?php 
                // potong deskripsi jadi 3 baris maksimal
                $desc = nl2br(htmlspecialchars($p['deskripsi']));
                echo '<li>' . implode('</li><li>', array_slice(explode("\n", $desc), 0, 3)) . '</li>';
              ?>
            </ul>

            <a href="detail.php?id=<?= $p['id'] ?>" class="btn">View Details</a>
          </div>
        </div>
      <?php endforeach; endif; ?>
    </div>
  </div>

  <footer class="site-footer">
    <div class="footer-content">
      <div class="footer-logo">TimeLess EO</div>
      <div class="footer-links">
        <a href="../index.php">Home</a>
        <a href="list.php">Packages</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <a href="../privacy.php">Privacy Policy</a>
      </div>
      <div class="social-links">
        <a href="#"><i class="fab fa-facebook-f"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fab fa-twitter"></i></a>
        <a href="#"><i class="fab fa-pinterest"></i></a>
      </div>
      <div class="copyright">© <?= date('Y') ?> Timeless EO. All rights reserved.</div>
    </div>
  </footer>

  <script>
    const hamburger = document.getElementById('hamburger');
    const menu      = document.getElementById('menu');
    hamburger.addEventListener('click', () => {
      menu.classList.toggle('active');
    });
  </script>
</body>
</html>
