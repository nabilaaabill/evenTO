<?php
session_start();
require '../koneksi/config.php';

// Cek apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: ../auth/index.php');
    exit;
}

// Ambil data user dari database
$stmt = $pdo->prepare("SELECT fullname, email FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    echo "User tidak ditemukan.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya - Timeless Event</title>
    <link rel="stylesheet" href="../css/user-style.css">
</head>
<body>
    <div class="profile-container">
        <h2>Profil Saya</h2>
        <div class="profile-info">
            <p><strong>Nama Lengkap:</strong> <?= htmlspecialchars($user['fullname']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        </div>
        <div class="btn-group">
            <a href="index.php" class="btn btn-back">← Kembali</a>
            <a href="../koneksi/logout.php" class="btn btn-logout">Logout</a>
        </div>
    </div>
</body>
</html>
