<?php
require_once __DIR__ . '/../config/config.php';
if (!isAdmin()) redirect(APP_URL.'/admin/login.php');
$title = 'Data Pendaftar';

$pdo = db();
$search = trim($_GET['q'] ?? '');
$tahap  = $_GET['tahap'] ?? '';

$sql = "SELECT * FROM pendaftar WHERE 1=1";
$params = [];
if ($search) { $sql .= " AND (nama LIKE ? OR email LIKE ? OR no_pendaftaran LIKE ?)"; $params = array_merge($params, ["%$search%","%$search%","%$search%"]); }
if ($tahap)  { $sql .= " AND tahap = ?"; $params[] = $tahap; }
$sql .= " ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll();
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Data Pendaftar - Admin PMB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{font-family:'Segoe UI',sans-serif;background:#f4f6fb;}
.sidebar{width:220px;min-height:100vh;background:#1a3c6e;position:fixed;top:0;left:0;bottom:0;}
.sidebar-brand{padding:18px 16px;color:#fff;font-weight:800;font-size:15px;border-bottom:1px solid rgba(255,255,255,.1);}
.sidebar .nav-link{color:rgba(255,255,255,.72);padding:9px 16px;border-radius:8px;margin:2px 8px;font-size:13.5px;}
.sidebar .nav-link:hover,.sidebar .nav-link.active{background:rgba(255,255,255,.15);color:#fff;}
.main{margin-left:220px;}.topbar{background:#fff;border-bottom:1px solid #e2e8f2;padding:14px 20px;}
.content{padding:20px;}.card{border:1px solid #e2e8f2;border-radius:12px;}
.card-header{background:#fff;border-bottom:1px solid #e2e8f2;font-weight:700;}
.table th{background:#f4f6fb;font-size:12.5px;font-weight:700;color:#4a5568;}.table td{font-size:13px;vertical-align:middle;}
</style>
</head><body>
<div class="sidebar">
  <div class="sidebar-brand"><i class="bi bi-mortarboard-fill me-2"></i>Admin PMB</div>
  <nav class="pt-2">
    <a href="dashboard.php" class="nav-link"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="pendaftar.php" class="nav-link active"><i class="bi bi-people me-2"></i>Data Pendaftar</a>
    <a href="seleksi.php" class="nav-link"><i class="bi bi-file-check me-2"></i>Seleksi Berkas</a>
    <a href="ujian.php" class="nav-link"><i class="bi bi-pen me-2"></i>Nilai Ujian</a>
    <a href="pengumuman.php" class="nav-link"><i class="bi bi-megaphone me-2"></i>Pengumuman</a>
    <a href="daftar_ulang.php" class="nav-link"><i class="bi bi-cash-coin me-2"></i>Daftar Ulang</a>
    <a href="ospek.php" class="nav-link"><i class="bi bi-people-fill me-2"></i>Ospek</a>
    <hr style="border-color:rgba(255,255,255,.1);margin:8px 16px;">
    <a href="logout.php" class="nav-link" style="color:rgba(255,150,150,.8);"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a>
  </nav>
</div>
<div class="main">
  <div class="topbar d-flex align-items-center justify-content-between">
    <strong>Data Pendaftar</strong>
    <span class="text-muted small"><?= e($_SESSION['admin_nama']) ?></span>
  </div>
  <div class="content">
    <div class="card">
      <div class="card-header p-3 d-flex gap-2 flex-wrap align-items-center">
        <i class="bi bi-people text-primary"></i>Semua Pendaftar
        <form class="d-flex gap-2 ms-auto flex-wrap" method="get">
          <input type="text" name="q" class="form-control form-control-sm" placeholder="Cari nama/email..." value="<?= e($search) ?>" style="width:200px;">
          <select name="tahap" class="form-select form-select-sm" style="width:160px;">
            <option value="">Semua Tahap</option>
            <?php foreach(['pendaftaran','seleksi','ujian','pengumuman','daftar_ulang','ospek','selesai','ditolak'] as $t): ?>
            <option value="<?= $t ?>" <?= $tahap===$t?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$t)) ?></option>
            <?php endforeach; ?>
          </select>
          <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
          <a href="pendaftar.php" class="btn btn-sm btn-outline-secondary">Reset</a>
        </form>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th class="ps-3">#</th>
              <th>No. Pendaftaran</th>
              <th>Nama</th>
              <th>Email</th>
              <th>Prodi</th>
              <th>Tahap</th>
              <th>Nilai</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($rows as $i => $r): ?>
            <tr>
              <td class="ps-3 text-muted"><?= $i+1 ?></td>
              <td><code><?= e($r['no_pendaftaran']) ?></code></td>
              <td class="fw-semibold"><?= e($r['nama']) ?></td>
              <td class="text-muted small"><?= e($r['email']) ?></td>
              <td><?= e($r['prodi']) ?></td>
              <td><?= badgeTahap($r['tahap']) ?></td>
              <td><?= $r['nilai_ujian'] ? '<strong class="text-success">'.$r['nilai_ujian'].'</strong>' : '-' ?></td>
              <td>
                <a href="detail.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-primary py-0 px-2">Detail</a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($rows)): ?>
            <tr><td colspan="8" class="text-center py-4 text-muted">Tidak ada data</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <div class="p-3 text-muted small">Total: <?= count($rows) ?> pendaftar</div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
