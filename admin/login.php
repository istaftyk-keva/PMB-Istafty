<?php
require_once __DIR__ . '/../config/config.php';
$title = 'Login Admin';
$error = '';

if (isAdmin()) redirect(APP_URL.'/admin/dashboard.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare("SELECT * FROM admin WHERE username = ?");
    $stmt->execute([trim($_POST['username'] ?? '')]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($_POST['password'] ?? '', $admin['password'])) {
        $_SESSION['admin_id']   = $admin['id'];
        $_SESSION['admin_nama'] = $admin['nama'];
        redirect(APP_URL.'/admin/dashboard.php');
    } else {
        $error = 'Username atau password salah.';
    }
}
include __DIR__ . '/../includes/header.php';
?>
<div class="container py-5" style="max-width:420px;">
  <div class="card">
    <div class="card-header p-3 text-center"><i class="bi bi-shield-lock me-2 text-primary"></i>Login Admin PMB</div>
    <div class="card-body p-4">
      <?php if ($error): ?><div class="alert alert-danger py-2 small"><?= e($error) ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label fw-semibold">Username</label>
          <input type="text" name="username" class="form-control" value="admin" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk</button>
      </form>
      <div class="text-center mt-3 small text-muted">Default: admin / password</div>
    </div>
  </div>
  <div class="text-center mt-2"><a href="<?= APP_URL ?>" class="small text-muted">← Kembali ke Beranda</a></div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
