<?php
// thanks.php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Thank You | Timeless Event</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css"> <!-- sesuaikan path jika perlu -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Segoe UI', sans-serif;
            text-align: center;
            padding: 50px;
        }

        .thank-you-container {
            background: white;
            max-width: 600px;
            margin: auto;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            border-radius: 16px;
        }

        .thank-you-container h1 {
            color: #2c3e50;
            font-size: 36px;
        }

        .thank-you-container p {
            font-size: 18px;
            color: #555;
            margin: 20px 0;
        }

        .thank-you-container .icon {
            font-size: 60px;
            color: #27ae60;
            margin-bottom: 20px;
        }

        .btn-home {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 24px;
            background-color: #2c3e50;
            color: #fff;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s;
        }

        .btn-home:hover {
            background-color: #34495e;
        }
    </style>
</head>
<body>
    <div class="thank-you-container">
        <div class="icon"><i class="fas fa-check-circle"></i></div>
        <h1>Terima Kasih!</h1>
        <p>Pembayaran Anda telah berhasil diproses.</p>
        <p>Kami akan segera memproses pemesanan Anda. Silakan cek email untuk informasi selanjutnya.</p>
        <a href="index.php" class="btn-home">Kembali ke Beranda</a>
    </div>
</body>
</html>
