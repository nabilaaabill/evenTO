<?php
// admin_contacts.php
require '../koneksi/config.php';

// Hapus pesan jika ada param ?delete=…
if (isset($_GET['delete']) && ctype_digit($_GET['delete'])) {
    $delId = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM contacts WHERE id = ?")
        ->execute([$delId]);
    header('Location: admin_contacts.php');
    exit;
}

// Ambil semua kontak, terbaru di atas
$stmt     = $pdo->query("SELECT * FROM contacts ORDER BY created_at DESC");
$contacts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin – Messages</title>
  <link rel="stylesheet" href="../css/style.css">
  <style>
    body { font-family: Arial, sans-serif; padding:2rem; }
    h1 { margin-bottom:1rem; }
    a.button { 
      display:inline-block; padding:.4rem .8rem; 
      margin-bottom:1rem; background:#17a2b8; color:#fff; 
      text-decoration:none; border-radius:4px;
    }
    table { width:100%; border-collapse: collapse; }
    th, td { border:1px solid #ddd; padding:.5rem; vertical-align: top; }
    th { background:#f4f4f4; }
    td.message { max-width: 300px; white-space: pre-wrap; }
    a.delete { color:#dc3545; text-decoration:none; }
  </style>
</head>
<body>
  <h1>Incoming Messages</h1>
  <a href="index.php" class="button">← Back to Dashboard</a>

  <?php if (empty($contacts)): ?>
    <p>No messages yet.</p>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Email</th>
          <th>Subject</th>
          <th>Message</th>
          <th>Received At</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($contacts as $c): ?>
        <tr>
          <td><?= htmlspecialchars($c['id']) ?></td>
          <td><?= htmlspecialchars($c['name']) ?></td>
          <td><?= htmlspecialchars($c['email']) ?></td>
          <td><?= htmlspecialchars($c['subject'] ?: '-') ?></td>
          <td class="message"><?= nl2br(htmlspecialchars($c['message'])) ?></td>
          <td><?= htmlspecialchars($c['created_at']) ?></td>
          <td>
            <a href="?delete=<?= $c['id'] ?>"
               class="delete"
               onclick="return confirm('Hapus pesan dari <?= addslashes($c['name']) ?>?');">
              Delete
            </a>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</body>
</html>
