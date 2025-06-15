<?php
// booking.php
require '../koneksi/config.php';
require '../koneksi/midtrans_config.php';

// Ambil paket berdasarkan ?id=…
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die('Paket tidak ditemukan.');
}
$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM paket WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) {
  die('Paket tidak ditemukan.');
}

// Jika form disubmit, generate Snap token
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ambil data booking
    $customer_name  = trim($_POST['name']);
    $customer_email = trim($_POST['email']);
    $event_date     = $_POST['date'];
    $qty            = max(1, (int)$_POST['qty']);

    // Call the stored procedure to place the order
    try {
        $stmt = $pdo->prepare("CALL PlaceOrder(:pid, :name, :email, :date, :qty)");
        $stmt->execute([
            ':pid'   => $id,
            ':name'  => $customer_name,
            ':email' => $customer_email,
            ':date'  => $event_date,
            ':qty'   => $qty
        ]);
        $order_id = $pdo->lastInsertId(); // This might not work directly with CALL, consider alternative if needed
    } catch (PDOException $e) {
        die("Error placing order: " . $e->getMessage());
    }

    // prepare params Midtrans
    $unitPrice = $p['harga'] * (100 - $p['diskon'])/100;
    $grossAmt  = $unitPrice * $qty; // This grossAmt is now calculated by the DB procedure, but we need it for Midtrans

    $transaction = [
      'transaction_details' => [
        'order_id'     => 'ORDER-'.$order_id,
        'gross_amount' => $grossAmt,
      ],
      'customer_details' => [
        'first_name' => $customer_name,
        'email'      => $customer_email,
      ],
      'item_details' => [[
        'id'       => $p['id'],
        'price'    => $unitPrice,
        'quantity' => $qty,
        'name'     => $p['nama']
      ]]
    ];

    // generate Snap token
    try {
      $snapToken = \Midtrans\Snap::getSnapToken($transaction);
    } catch (Exception $e) {
      die("Midtrans error: " . $e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Booking – <?= htmlspecialchars($p['nama']) ?></title>
  <link rel="stylesheet" href="../css/style.css">
  <?php if (!empty($snapToken)): ?>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="<?= \Midtrans\Config::$clientKey ?>"></script>
  <?php endif; ?>
</head>
<body>
  <header class="site-header">
    <div class="container">
      <h1 class="logo-text">TimeLess EO</h1>
      <div class="hamburger" id="hamburger">
        <span></span><span></span><span></span>
      </div>
      <nav id="menu">
        <a href="index.php">Home</a>
        <a href="list.php" class="active">List</a>
        <a href="about.php">About</a>
        <a href="contact.php">Contact</a>
        <span class="icons">
          <a href="../user/auth.php"><i class="fa-solid fa-user-slash"></i></a>
        </span>
      </nav>
    </div>
  </header>
  <main class="main-container">
    <h1 class="section-title">Booking: <?= htmlspecialchars($p['nama']) ?></h1>

    <?php if (empty($snapToken)): ?>
      <!-- FORM BOOKING -->
      <form method="post" class="simulation-form">
        <div class="form-group">
          <label>Nama Lengkap</label>
          <input type="text" name="name" required>
        </div>
        <div class="form-group">
          <label>Email</label>
          <input type="email" name="email" required>
        </div>
        <div class="form-group">
          <label>Tanggal Event</label>
          <input type="date" name="date" required>
        </div>
        <div class="form-group">
          <label>Jumlah Paket</label>
          <input type="number" name="qty" min="1" value="1" required>
        </div>
        <div class="simulation-result">
          <h3>Total: Rp <?= number_format($p['harga'] * (100 - $p['diskon'])/100,0,',','.') ?> x <span id="qty">1</span> = 
            Rp <span id="total"><?= number_format(($p['harga'] * (100 - $p['diskon'])/100),0,',','.') ?></span>
          </h3>
        </div>
        <button type="submit" class="btn">Lanjut ke Pembayaran</button>
      </form>

      <script>
        // realtime hitung total
        const price = <?= $p['harga'] * (100 - $p['diskon'])/100 ?>;
        const qtyInput = document.querySelector('input[name=qty]');
        const qtyLabel = document.getElementById('qty');
        const totalLabel = document.getElementById('total');
        qtyInput.addEventListener('input', ()=>{
          let q = parseInt(qtyInput.value)||1;
          qtyLabel.textContent = q;
          totalLabel.textContent = (price*q).toLocaleString('id-ID');
        });
      </script>

    <?php else: ?>
      <!-- SNAPS -->
      <p>Redirecting to payment gateway...</p>
      <script>
        window.snap.pay('<?= $snapToken ?>', {
          onSuccess: function(result){ console.log(result); window.location='thankyou.php'; },
          onPending: function(result){ console.log(result); window.location='thankyou.php'; },
          onError:   function(result){ console.log(result); alert('Pembayaran gagal!'); },
          onClose:   function(){ alert('Anda menutup popup tanpa menyelesaikan pembayaran.'); }
        });
      </script>
    <?php endif; ?>

  </main>
</body>
</html>


