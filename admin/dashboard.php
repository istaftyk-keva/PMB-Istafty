<?php
require_once __DIR__ . '/../config/config.php';
if (!isAdmin()) redirect(APP_URL.'/admin/login.php');
$title = 'Dashboard Admin';

$pdo   = db();
$stats = [
    'total'       => $pdo->query("SELECT COUNT(*) FROM pendaftar")->fetchColumn(),
    'seleksi'     => $pdo->query("SELECT COUNT(*) FROM pendaftar WHERE tahap='seleksi'")->fetchColumn(),
    'ujian'       => $pdo->query("SELECT COUNT(*) FROM pendaftar WHERE tahap='ujian'")->fetchColumn(),
    'lulus'       => $pdo->query("SELECT COUNT(*) FROM pendaftar WHERE status_pengumuman='lulus'")->fetchColumn(),
    'daftar_ulang'=> $pdo->query("SELECT COUNT(*) FROM pendaftar WHERE tahap='daftar_ulang'")->fetchColumn(),
    'ospek'       => $pdo->query("SELECT COUNT(*) FROM pendaftar WHERE tahap='ospek' OR tahap='selesai'")->fetchColumn(),
];
$terbaru = $pdo->query("SELECT * FROM pendaftar ORDER BY id DESC LIMIT 10")->fetchAll();

// Sidebar helper
function sidelink($href, $icon, $label, $active='') {
    $cls = strpos($_SERVER['PHP_SELF'],$href)!==false ? 'active' : '';
    echo "<a href='$href' class='nav-link $cls'><i class='bi $icon me-2'></i>$label</a>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Dashboard - PMB UHB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{font-family:'Segoe UI',sans-serif;background:#f4f6fb;}
.sidebar{width:220px;min-height:100vh;background:#1a3c6e;position:fixed;top:0;left:0;bottom:0;overflow-y:auto;z-index:100;}
.sidebar-brand{padding:18px 16px;color:#fff;font-weight:800;font-size:15px;border-bottom:1px solid rgba(255,255,255,.1);}
.sidebar .nav-link{color:rgba(255,255,255,.72);padding:9px 16px;border-radius:8px;margin:2px 8px;font-size:13.5px;}
.sidebar .nav-link:hover,.sidebar .nav-link.active{background:rgba(255,255,255,.15);color:#fff;}
.main{margin-left:220px;}
.topbar{background:#fff;border-bottom:1px solid #e2e8f2;padding:14px 20px;display:flex;align-items:center;justify-content:space-between;}
.content{padding:20px;}
.stat-card{background:#fff;border:1px solid #e2e8f2;border-radius:12px;padding:16px 18px;display:flex;align-items:center;gap:14px;}
.stat-icon{width:46px;height:46px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:20px;flex-shrink:0;}
.card{border:1px solid #e2e8f2;border-radius:12px;}
.card-header{background:#fff;border-bottom:1px solid #e2e8f2;font-weight:700;}
.table th{background:#f4f6fb;font-size:12.5px;font-weight:700;color:#4a5568;}
.table td{font-size:13px;vertical-align:middle;}
</style>
</head>
<body>
<div class="sidebar">
  <div class="sidebar-brand"><i class="bi bi-mortarboard-fill me-2"></i>Admin PMB</div>
  <nav class="pt-2">
    <a href="dashboard.php" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
    <a href="pendaftar.php" class="nav-link"><i class="bi bi-people me-2"></i>Data Pendaftar</a>
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
  <div class="topbar">
    <strong>Dashboard</strong>
    <span class="text-muted small"><?= date('l, d F Y') ?> · <?= e($_SESSION['admin_nama']) ?></span>
  </div>
  <div class="content">

    <!-- Stat Cards -->
    <div class="row g-3 mb-4">
      <?php
      $cards = [
        ['Total Pendaftar', $stats['total'],        'bi-people-fill',     '#1a3c6e', 'rgba(26,60,110,.1)'],
        ['Seleksi Berkas',  $stats['seleksi'],      'bi-file-check',      '#2557a7', 'rgba(37,87,167,.1)'],
        ['Peserta Ujian',   $stats['ujian'],        'bi-pen',             '#7c3aed', 'rgba(124,58,237,.1)'],
        ['Dinyatakan Lulus',$stats['lulus'],        'bi-trophy-fill',     '#198754', 'rgba(25,135,84,.1)'],
        ['Daftar Ulang',    $stats['daftar_ulang'], 'bi-cash-coin',       '#b45309', 'rgba(180,83,9,.1)'],
        ['Ospek/Selesai',   $stats['ospek'],        'bi-mortarboard-fill','#c0392b', 'rgba(192,57,43,.1)'],
      ];
      foreach ($cards as [$lbl,$val,$ico,$clr,$bg]):
      ?>
      <div class="col-xl-2 col-md-4 col-6">
        <div class="stat-card">
          <div class="stat-icon" style="background:<?= $bg ?>;color:<?= $clr ?>;"><i class="bi <?= $ico ?>"></i></div>
          <div>
            <div style="font-size:22px;font-weight:800;color:<?= $clr ?>;"><?= $val ?></div>
            <div style="font-size:11.5px;color:#6b7a8d;"><?= $lbl ?></div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Tabel Pendaftar Terbaru -->
    <div class="card">
      <div class="card-header p-3 d-flex align-items-center justify-content-between">
        <span><i class="bi bi-clock-history me-2 text-primary"></i>Pendaftar Terbaru</span>
        <a href="pendaftar.php" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0">
          <thead>
            <tr>
              <th class="ps-3">No. Pendaftaran</th>
              <th>Nama</th>
              <th>Prodi</th>
              <th>Jalur</th>
              <th>Tahap</th>
              <th>Tanggal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($terbaru as $r): ?>
            <tr>
              <td class="ps-3"><code><?= e($r['no_pendaftaran']) ?></code></td>
              <td class="fw-semibold"><?= e($r['nama']) ?></td>
              <td><?= e($r['prodi']) ?></td>
              <td><?= e($r['jalur']) ?></td>
              <td><?= badgeTahap($r['tahap']) ?></td>
              <td class="text-muted small"><?= date('d/m/Y', strtotime($r['created_at'])) ?></td>
              <td>
                <a href="detail.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-outline-secondary py-0">Detail</a>
              </td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($terbaru)): ?>
            <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada data</td></tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>

  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
