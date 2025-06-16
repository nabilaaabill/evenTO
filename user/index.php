<?php
// index.php
require '../koneksi/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeless Event Organizer</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header class="site-header">
        <div class="container">
            <!-- <img src="img/Logo_timeless.png" alt="Timeless Logo" class="logo-img"> -->
            <h1 class="logo-text">Timeless EO</h1>
            <div class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="list.php">List</a>
                <a href="about.php">About</a>
                 <a href="contact.php">Contact</a>
                <span class="icons">
                    <a href="profile.php"><i class="fa-solid fa-user"></i></a>
                </span>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="hero-content">
            <h2>Create Unforgettable Moments</h2>
            <p>Timeless Event specializes in crafting bespoke experiences that leave lasting impressions. Let us turn your vision into reality.</p>
            <a href="list.php" class="btn">Explore Packages</a>
        </div>
    </section>

    <section class="event-categories">
        <h1>Browse The Range</h1>
        <p>Discover our curated collection of exceptional event services</p>
        <div class="divider"></div>
        
        <div class="category-grid">
            <!-- Birthday Party -->
            <div class="category-card">
                <img src="../img/birthday.jpeg" alt="birthday Package" class="package-img">
                <h2 class="category-name">Birthday Party</h2>
                <a href="birthday-events.php" class="btn">View Options</a>
            </div>
            
            <!-- Wedding -->
            <div class="category-card">
                <img src="../img/wedding.jpg" alt="Wedding Package" class="package-img">
                <h2 class="category-name">Wedding</h2>
                <a href="wedding-events.php" class="btn">View Options</a>
            </div>
            
            <!-- Festival -->
            <div class="category-card">
                <img src="../img/festival.jpg" alt="festival Package" class="package-img">
                <h2 class="category-name">Festival</h2>
                <a href="festival-events.php" class="btn">View Options</a>
            </div>
        </div>
    </section>

    <div class="main-container">
    <h2 class="section-title">Featured Packages</h2>
    <div class="package-list">
      <?php
      // array kategori yang mau ditampilkan
      $cats = ['Wedding','Birthday','Festival'];

      foreach($cats as $cat):
        // ambil paket terbaru per kategori
        $stmt = $pdo->prepare("SELECT * FROM paket 
                              WHERE kategori = ? 
                              ORDER BY id DESC 
                              LIMIT 1");
        $stmt->execute([$cat]);
        $p = $stmt->fetch();
        if (!$p) continue;  // kalau tidak ada paket di kategori ini, skip
      ?>
      <div class="package-card">
        <img src="../uploads/<?= htmlspecialchars($p['foto']) ?>"
             alt="<?= htmlspecialchars($p['nama']) ?>"
             class="package-img">
        <div class="package-content">
          <h3 class="package-title"><?= htmlspecialchars($p['nama']) ?></h3>
          <div class="package-price">
            Rp.<?= number_format($p['harga'],0,',','.') ?>
            <?php if($p['diskon']>0): ?>
              <small>-<?= $p['diskon']?>% 
                Rp.<?= number_format($p['harga']*(100-$p['diskon'])/100,0,',','.') ?></small>
            <?php endif; ?>
          </div>
          <ul class="package-benefits">
            <li><?= nl2br(htmlspecialchars(substr($p['deskripsi'],0,100))) ?>…</li>
          </ul>
          <a href="detail.php?id=<?= $p['id'] ?>" class="btn">
            View Details
          </a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>


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
<script>
    const hamburger = document.getElementById('hamburger');
    const menu = document.getElementById('menu');
    hamburger.addEventListener('click', function() {
    menu.classList.toggle('active');
   });
</script>
</body>
</html>