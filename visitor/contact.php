<?php
// visitor/contact.php
require __DIR__ . '/../koneksi/config.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ambil dan bersihkan input
    $name    = trim($_POST['name']    ?? '');
    $email   = trim($_POST['email']   ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // validasi sederhana
    if ($name === '')    $errors[] = 'Nama wajib diisi.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL))
                        $errors[] = 'Email tidak valid.';
    if ($message === '') $errors[] = 'Pesan tidak boleh kosong.';

    if (empty($errors)) {
        // simpan ke database
        $sql = "INSERT INTO contacts
                (name, email, subject, message)
                VALUES
                (:name, :email, :subject, :message)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'    => $name,
            ':email'   => $email,
            ':subject' => $subject ?: null,
            ':message' => $message
        ]);
        $success = true;
        // kosongkan kembali form
        $name = $email = $subject = $message = '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <title>Contact Us - Timeless EO</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1 class="logo-text">TimeLess EO</h1>
      <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
      </div>
      <nav id="menu">
        <a href="../index.php">Home</a>
        <a href="list.php">List</a>
        <a href="about.php">About</a>
        <a href="contact.php" class="active">Contact</a>
        <span class="icons">
          <a href="../user/auth.php"><i class="fa-solid fa-user-slash"></i></a>
        </span>
      </nav>
    </div>
  </header>

  <main class="contact-main">
    <section class="about-hero">
      <div class="container">
        <h2>Get In Touch With Us</h2>
        <p class="subtitle">
          For More Information About Our Product & Services. Please Feel Free To Drop Us
          An Email. Our Staff Always Be There To Help You Out. Do Not Hesitate!
        </p>
      </div>
    </section>

    <div class="container">
      <div class="contact-container">
        <div class="contact-info">
          <!-- tetap statis seperti sebelumnya -->
          <div class="info-box">
            <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="info-content">
              <h3>Address</h3>
              <p>Jl. Pendidikan No. 15, Bandung, Indonesia</p>
            </div>
          </div>
          <div class="info-box">
            <div class="info-icon"><i class="fas fa-phone-alt"></i></div>
            <div class="info-content">
              <h3>Phone</h3>
              <p>Mobile: +84 545-6789<br>Hotline: +84 456-6788</p>
            </div>
          </div>
          <div class="info-box">
            <div class="info-icon"><i class="fas fa-envelope"></i></div>
            <div class="info-content">
              <h3>Email address</h3>
              <p>timelesseo@gmail.com</p>
            </div>
          </div>
          <div class="info-box">
            <div class="info-icon"><i class="fas fa-clock"></i></div>
            <div class="info-content">
              <h3>Working Time</h3>
              <p>Monday-Friday: 8:00-22:00<br>Saturday-Sunday: 8:00-21:00</p>
            </div>
          </div>
        </div>

        <div class="contact-form">
          <form method="post" novalidate>
            <h3>Send Us a Message</h3>

            <?php if ($success): ?>
              <p style="color:green;">Pesan Anda telah terkirim. Terima kasih!</p>
            <?php endif; ?>

            <?php if ($errors): ?>
              <?php foreach ($errors as $err): ?>
                <p style="color:red;"><?= htmlspecialchars($err) ?></p>
              <?php endforeach; ?>
            <?php endif; ?>

            <div class="form-group">
              <label for="name">Your name</label>
              <input type="text" id="name" name="name"
                     value="<?= htmlspecialchars($name ?? '') ?>" required>
            </div>

            <div class="form-group">
              <label for="email">Email address</label>
              <input type="email" id="email" name="email"
                     value="<?= htmlspecialchars($email ?? '') ?>" required>
            </div>

            <div class="form-group">
              <label for="subject">Subject 
                <span class="optional">(optional)</span>
              </label>
              <input type="text" id="subject" name="subject"
                     value="<?= htmlspecialchars($subject ?? '') ?>">
            </div>

            <div class="form-group">
              <label for="message">Message</label>
              <textarea id="message" name="message" required><?= htmlspecialchars($message ?? '') ?></textarea>
            </div>

            <button type="submit" class="submit-btn">
              <i class="fas fa-paper-plane"></i> Send Message
            </button>
          </form>
        </div>
      </div>
    </div>
  </main>

  <footer class="site-footer">
    <div class="footer-content">
      <div class="footer-logo">Timeless EO</div>
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
      <div class="copyright">
        © <?= date('Y') ?> Timeless EO. All rights reserved.
      </div>
    </div>
  </footer>

  <script>
    const hamburger = document.getElementById('hamburger');
    const menu      = document.getElementById('menu');
    hamburger.addEventListener('click', () =>
      menu.classList.toggle('active')
    );
  </script>
</body>
</html>
