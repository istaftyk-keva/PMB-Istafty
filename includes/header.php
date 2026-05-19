<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($title) ? e($title).' - ' : '' ?>PMB UHB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body { font-family: 'Segoe UI', sans-serif; background: #f4f6fb; }
.navbar-brand { font-weight: 800; font-size: 18px; }
.sidebar { min-height: calc(100vh - 56px); background: #1a3c6e; }
.sidebar .nav-link { color: rgba(255,255,255,.75); padding: 10px 16px; border-radius: 8px; margin: 2px 8px; font-size: 14px; }
.sidebar .nav-link:hover, .sidebar .nav-link.active { background: rgba(255,255,255,.15); color: #fff; }
.sidebar .nav-link i { width: 20px; }
.card { border: 1px solid #e2e8f2; border-radius: 12px; box-shadow: 0 1px 4px rgba(0,0,0,.06); }
.card-header { background: #fff; border-bottom: 1px solid #e2e8f2; font-weight: 700; font-size: 15px; }
.btn-primary { background: #1a3c6e; border-color: #1a3c6e; }
.btn-primary:hover { background: #2557a7; border-color: #2557a7; }
.table th { background: #f4f6fb; font-size: 13px; font-weight: 700; color: #4a5568; }
.badge { font-size: 12px; }
.alert { border-radius: 10px; }
.step-bar { display: flex; gap: 0; overflow-x: auto; padding: 16px 0; }
.step-item { display: flex; flex-direction: column; align-items: center; flex: 1; min-width: 80px; position: relative; }
.step-item::before { content:''; position:absolute; top:16px; left:-50%; right:50%; height:2px; background:#dee2e6; z-index:0; }
.step-item:first-child::before { display: none; }
.step-circle { width:34px;height:34px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;position:relative;z-index:1;border:2px solid #dee2e6;background:#fff;color:#adb5bd; }
.step-circle.done { background:#198754;border-color:#198754;color:#fff; }
.step-circle.active { background:#1a3c6e;border-color:#1a3c6e;color:#fff; }
.step-label { font-size:11px;margin-top:5px;color:#6c757d;text-align:center;font-weight:500; }
.step-item.active .step-label { color:#1a3c6e;font-weight:700; }
.step-item.done .step-label { color:#198754; }
.step-item.done::before, .step-item.active::before { background:#198754; }
</style>
</head>
<body>

<nav class="navbar navbar-dark" style="background:#1a3c6e;">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= APP_URL ?>"><i class="bi bi-mortarboard-fill me-2"></i>PMB UHB</a>
    <div class="d-flex align-items-center gap-2">
      <?php if(isPeserta()): ?>
        <span class="text-white-50 small"><i class="bi bi-person-circle me-1"></i><?= e($_SESSION['peserta_nama'] ?? '') ?></span>
        <a href="<?= APP_URL ?>/logout.php" class="btn btn-sm btn-outline-light">Keluar</a>
      <?php elseif(isAdmin()): ?>
        <span class="text-white-50 small">Admin</span>
        <a href="<?= APP_URL ?>/admin/logout.php" class="btn btn-sm btn-outline-light">Keluar</a>
      <?php else: ?>
        <a href="<?= APP_URL ?>/login.php" class="btn btn-sm btn-outline-light">Login Peserta</a>
        <a href="<?= APP_URL ?>/daftar.php" class="btn btn-sm btn-warning fw-bold">Daftar</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
