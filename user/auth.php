<?php
session_start();
require __DIR__ . '/../koneksi/config.php';

// Flash message helper
function set_flash($type, $message) {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}
function get_flash() {
    $flashes = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $flashes;
}

$activeTab = 'login';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'login') {
        $activeTab = 'login';
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            set_flash('error', 'Email atau password tidak valid.');
        } else {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Simpan session
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['user_name'] = $user['fullname'];
                $_SESSION['user_role'] = $user['role'];

                set_flash('success', 'Login berhasil!');

                if ($user['role'] === 'admin') {
                    header('Location: ../admin/admin_index.php');
                } else {
                    header('Location: ../user/index.php');
                }
                exit;
            } else {
                set_flash('error', 'Email atau password salah.');
            }
        }
    }

    if (isset($_POST['action']) && $_POST['action'] === 'register') {
        $activeTab = 'register';
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if ($fullname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '' || $password !== $confirm) {
            set_flash('error', 'Data registrasi tidak valid atau password tidak cocok.');
        } else {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);

            if ($stmt->fetch()) {
                set_flash('error', 'Email sudah terdaftar.');
            } else {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (fullname, email, password, role) VALUES (?, ?, ?, 'user')");
                if ($stmt->execute([$fullname, $email, $hash])) {
                    set_flash('success', 'Registrasi berhasil! Silakan login.');
                    $activeTab = 'login';
                } else {
                    set_flash('error', 'Gagal menyimpan data. Coba lagi.');
                }
            }
        }
    }
}
$flashes = get_flash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeless Event - Auth</title>
    <link rel="stylesheet" href="../css/auth.css">
    <style>
    .alert { position: relative; padding: 1rem 1.5rem; margin-bottom:1rem; border-radius:4px; }
    .alert.success { background:#d4edda; color:#155724; }
    .alert.error   { background:#f8d7da; color:#721c24; }
    .alert .close { position:absolute; top:0.5rem; right:0.75rem; background:none; border:none; font-size:1.2rem; cursor:pointer; }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-side">
            <img src="../img/event-organizer.jpg" alt="Timeless Event">
        </div>
        <div class="form-side">
            <div class="branding">
                <h1 class="logo">Timeless Event</h1>
                <p class="tagline">Accard yang tak terlupakan</p>
            </div>
            <div class="welcome-section">
                <h2>Welcome Timeless Event</h2>
                <div class="auth-tabs">
                    <div class="slider" data-active="<?= $activeTab ?>"></div>
                    <button class="tab-btn <?= $activeTab==='login'?'active':'' ?>" data-tab="login">Login</button>
                    <button class="tab-btn <?= $activeTab==='register'?'active':'' ?>" data-tab="register">Register</button>
                </div>
            </div>
            <?php foreach($flashes as $f): ?>
            <div class="alert <?= htmlspecialchars($f['type']) ?>">
                <?= htmlspecialchars($f['message']) ?>
                <button class="close" onclick="this.parentElement.style.display='none';">&times;</button>
            </div>
            <?php endforeach; ?>
            <form method="post" class="login-form <?= $activeTab==='login'?'active':'' ?>">
                <input type="hidden" name="action" value="login">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Enter your password">
                </div>
                <div class="form-options">
                    <label class="remember-me"><input type="checkbox" name="remember"> Remember me</label>
                </div>
                <button type="submit" class="login-btn">Login</button>
                <div style="text-align:right; margin-bottom:1rem;">
    <a href="../index.php">← Back to Home</a>
</div>

            </form>
            <form method="post" class="register-form <?= $activeTab==='register'?'active':'' ?>">
                <input type="hidden" name="action" value="register">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullname" placeholder="Enter fullname" value="<?= htmlspecialchars($_POST['fullname'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Enter your email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" placeholder="Create your password">
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm your password">
                </div>
                <button type="submit" class="login-btn">Register</button>
              <div style="text-align:right; margin-bottom:1rem;">
    <a href="../index.php">← Back to Home</a>
    </div>
</form>
        </div>
    </div>
  

    <script src="../js/script.js"></script>
</body>
</html>
