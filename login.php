<?php
require_once __DIR__ . '/config/config.php';
$title = 'Login Peserta';
$error = '';

if (isPeserta()) redirect(APP_URL . '/dashboard.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = db()->prepare("SELECT * FROM pendaftar WHERE email = ?");
    $stmt->execute([trim($_POST['email'] ?? '')]);
    $user = $stmt->fetch();
    if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
        $_SESSION['peserta_id']   = $user['id'];
        $_SESSION['peserta_nama'] = $user['nama'];
        redirect(APP_URL . '/dashboard.php');
    } else {
        $error = 'Email atau password salah.';
    }
}

include __DIR__ . '/includes/header.php';
$flash = getFlash();
?>
<div class="container py-5" style="max-width:420px;">
  <?php if ($flash): ?>
  <div class="alert alert-<?= $flash['type']==='success'?'success':'danger' ?> py-2"><?= $flash['msg'] ?></div>
  <?php endif; ?>
  <div class="card">
    <div class="card-header p-3 text-center"><i class="bi bi-person-circle me-2 text-primary"></i>Login Peserta</div>
    <div class="card-body p-4">
      <?php if ($error): ?>
      <div class="alert alert-danger py-2 small"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post">
        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Password</label>
          <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="bi bi-box-arrow-in-right me-2"></i>Masuk</button>
      </form>
      <hr>
      <div class="text-center small">
        Belum daftar? <a href="daftar.php">Daftar sekarang</a> &nbsp;|&nbsp;
        <a href="cek_status.php">Cek status</a>
      </div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
