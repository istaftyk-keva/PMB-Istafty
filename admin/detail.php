<?php
require_once __DIR__ . '/../config/config.php';
if (!isAdmin()) redirect(APP_URL.'/admin/login.php');

$id = (int)($_GET['id'] ?? 0);
$pdo = db();
$stmt = $pdo->prepare("SELECT * FROM pendaftar WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();
if (!$p) { echo "Data tidak ditemukan."; exit; }

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_tahap') {
        $tahap = $_POST['tahap'];
        $pdo->prepare("UPDATE pendaftar SET tahap=? WHERE id=?")->execute([$tahap, $id]);
        $msg = 'Tahap berhasil diperbarui.';
    } elseif ($action === 'update_seleksi') {
        $status = $_POST['status_seleksi'];
        $nextTahap = $status === 'lulus' ? 'ujian' : ($status === 'tidak_lulus' ? 'ditolak' : 'seleksi');
        $pdo->prepare("UPDATE pendaftar SET status_seleksi=?, tahap=? WHERE id=?")->execute([$status, $nextTahap, $id]);
        $msg = 'Status seleksi diperbarui.';
    } elseif ($action === 'input_nilai') {
        $nilai = (float)$_POST['nilai_ujian'];
        $status = $nilai >= 60 ? 'lulus' : 'tidak_lulus';
        $tahap = $nilai >= 60 ? 'pengumuman' : 'ditolak';
        $pdo->prepare("UPDATE pendaftar SET nilai_ujian=?, status_pengumuman=?, tahap=? WHERE id=?")->execute([$nilai, $status, $tahap, $id]);
        $msg = 'Nilai ujian dan pengumuman diperbarui.';
    } elseif ($action === 'konfirmasi_bayar') {
        $pdo->prepare("UPDATE pendaftar SET status_bayar='lunas', tahap='ospek' WHERE id=?")->execute([$id]);
        $msg = 'Pembayaran dikonfirmasi. Peserta masuk tahap Ospek.';
    } elseif ($action === 'selesai_ospek') {
        $pdo->prepare("UPDATE pendaftar SET tahap='selesai' WHERE id=?")->execute([$id]);
        $msg = 'Ospek selesai. Mahasiswa resmi terdaftar.';
    }
    // Refresh data
    $stmt->execute([$id]);
    $p = $stmt->fetch();
}
?>
<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Detail Pendaftar - Admin PMB</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body{font-family:'Segoe UI',sans-serif;background:#f4f6fb;}
.sidebar{width:220px;min-height:100vh;background:#1a3c6e;position:fixed;top:0;left:0;bottom:0;}
.sidebar-brand{padding:18px 16px;color:#fff;font-weight:800;border-bottom:1px solid rgba(255,255,255,.1);}
.sidebar .nav-link{color:rgba(255,255,255,.72);padding:9px 16px;border-radius:8px;margin:2px 8px;font-size:13.5px;}
.sidebar .nav-link:hover,.sidebar .nav-link.active{background:rgba(255,255,255,.15);color:#fff;}
.main{margin-left:220px;}.topbar{background:#fff;border-bottom:1px solid #e2e8f2;padding:14px 20px;}
.content{padding:20px;}.card{border:1px solid #e2e8f2;border-radius:12px;}
.card-header{background:#fff;border-bottom:1px solid #e2e8f2;font-weight:700;}
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
  <div class="topbar d-flex align-items-center gap-2">
    <a href="pendaftar.php" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i></a>
    <strong>Detail Pendaftar</strong>
  </div>
  <div class="content">
    <?php if ($msg): ?>
    <div class="alert alert-success py-2 small"><i class="bi bi-check-circle me-2"></i><?= e($msg) ?></div>
    <?php endif; ?>

    <div class="row g-3">
      <!-- Data Peserta -->
      <div class="col-md-5">
        <div class="card">
          <div class="card-header p-3"><i class="bi bi-person me-2 text-primary"></i>Data Peserta</div>
          <div class="card-body p-0">
            <table class="table table-sm mb-0">
              <tr><td class="text-muted ps-3 small" width="130">No. Pendaftaran</td><td><code><?= e($p['no_pendaftaran']) ?></code></td></tr>
              <tr><td class="text-muted ps-3 small">Nama</td><td class="fw-semibold"><?= e($p['nama']) ?></td></tr>
              <tr><td class="text-muted ps-3 small">Email</td><td><?= e($p['email']) ?></td></tr>
              <tr><td class="text-muted ps-3 small">No. HP</td><td><?= e($p['no_hp']) ?></td></tr>
              <tr><td class="text-muted ps-3 small">Tgl. Lahir</td><td><?= e($p['tanggal_lahir']) ?></td></tr>
              <tr><td class="text-muted ps-3 small">Asal Sekolah</td><td><?= e($p['asal_sekolah']) ?></td></tr>
              <tr><td class="text-muted ps-3 small">Prodi</td><td><?= e($p['prodi']) ?></td></tr>
              <tr><td class="text-muted ps-3 small">Jalur</td><td><?= e($p['jalur']) ?></td></tr>
              <tr><td class="text-muted ps-3 small">Tahap Saat Ini</td><td><?= badgeTahap($p['tahap']) ?></td></tr>
              <tr><td class="text-muted ps-3 small">Nilai Ujian</td><td class="fw-bold text-success"><?= $p['nilai_ujian'] ?: '-' ?></td></tr>
              <tr><td class="text-muted ps-3 small">Status Bayar</td><td><?= ucfirst(e($p['status_bayar'])) ?></td></tr>
            </table>
            <?php if ($p['foto'] && file_exists(UPLOAD_PATH.$p['foto'])): ?>
            <div class="p-3">
              <div class="text-muted small mb-1">Foto:</div>
              <img src="<?= APP_URL ?>/uploads/<?= e($p['foto']) ?>" style="max-height:100px;border-radius:8px;">
            </div>
            <?php endif; ?>
            <?php if ($p['bukti_bayar']): ?>
            <div class="p-3">
              <div class="text-muted small mb-1">Bukti Bayar:</div>
              <a href="<?= APP_URL ?>/uploads/<?= e($p['bukti_bayar']) ?>" target="_blank" class="btn btn-sm btn-outline-success">
                <i class="bi bi-file-image me-1"></i>Lihat Bukti
              </a>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <!-- Panel Aksi per Tahap -->
      <div class="col-md-7">

        <!-- 1. Seleksi Berkas -->
        <div class="card mb-3">
          <div class="card-header p-3"><i class="bi bi-file-check text-warning me-2"></i>Seleksi Berkas</div>
          <div class="card-body p-3">
            <p class="small text-muted">Status saat ini: <strong><?= ucfirst(e($p['status_seleksi'])) ?></strong></p>
            <form method="post" class="d-flex gap-2 flex-wrap">
              <input type="hidden" name="action" value="update_seleksi">
              <select name="status_seleksi" class="form-select form-select-sm" style="width:180px;">
                <option value="menunggu" <?= $p['status_seleksi']==='menunggu'?'selected':'' ?>>Menunggu</option>
                <option value="lulus"    <?= $p['status_seleksi']==='lulus'?'selected':'' ?>>Lulus → Lanjut Ujian</option>
                <option value="tidak_lulus" <?= $p['status_seleksi']==='tidak_lulus'?'selected':'' ?>>Tidak Lulus → Ditolak</option>
              </select>
              <button class="btn btn-sm btn-warning">Simpan Seleksi</button>
            </form>
          </div>
        </div>

        <!-- 2. Input Nilai Ujian -->
        <div class="card mb-3">
          <div class="card-header p-3"><i class="bi bi-pen text-purple me-2" style="color:#7c3aed;"></i>Input Nilai Ujian</div>
          <div class="card-body p-3">
            <p class="small text-muted">Nilai saat ini: <strong><?= $p['nilai_ujian'] ?: 'Belum diinput' ?></strong>
              <?php if($p['nilai_ujian']): ?>
              <span class="badge <?= $p['nilai_ujian']>=60 ? 'bg-success':'bg-danger' ?>"><?= $p['nilai_ujian']>=60?'LULUS':'TIDAK LULUS' ?></span>
              <?php endif; ?>
            </p>
            <form method="post" class="d-flex gap-2">
              <input type="hidden" name="action" value="input_nilai">
              <input type="number" name="nilai_ujian" class="form-control form-control-sm" min="0" max="100" step="0.5"
                     placeholder="0-100" value="<?= e($p['nilai_ujian']) ?>" style="width:120px;" required>
              <button class="btn btn-sm btn-primary">Simpan Nilai</button>
            </form>
            <div class="small text-muted mt-1">*Nilai ≥ 60 = Lulus, &lt; 60 = Tidak Lulus</div>
          </div>
        </div>

        <!-- 3. Konfirmasi Pembayaran -->
        <div class="card mb-3">
          <div class="card-header p-3"><i class="bi bi-cash-coin text-success me-2"></i>Konfirmasi Pembayaran UKT</div>
          <div class="card-body p-3">
            <p class="small text-muted">Status bayar: <strong><?= ucfirst(e($p['status_bayar'])) ?></strong></p>
            <?php if ($p['status_bayar'] === 'proses'): ?>
            <form method="post">
              <input type="hidden" name="action" value="konfirmasi_bayar">
              <button class="btn btn-sm btn-success"><i class="bi bi-check-circle me-1"></i>Konfirmasi Lunas → Ospek</button>
            </form>
            <?php elseif ($p['status_bayar'] === 'lunas'): ?>
            <span class="badge bg-success">Sudah Lunas</span>
            <?php else: ?>
            <span class="text-muted small">Menunggu peserta upload bukti bayar.</span>
            <?php endif; ?>
          </div>
        </div>

        <!-- 4. Selesai Ospek -->
        <div class="card mb-3">
          <div class="card-header p-3"><i class="bi bi-mortarboard text-dark me-2"></i>Selesaikan Ospek</div>
          <div class="card-body p-3">
            <?php if ($p['tahap'] === 'ospek'): ?>
            <form method="post">
              <input type="hidden" name="action" value="selesai_ospek">
              <button class="btn btn-sm btn-dark"><i class="bi bi-check2-all me-1"></i>Tandai Ospek Selesai → Mahasiswa Aktif</button>
            </form>
            <?php elseif ($p['tahap'] === 'selesai'): ?>
            <span class="badge bg-success">Mahasiswa Aktif ✓</span>
            <?php else: ?>
            <span class="text-muted small">Tersedia saat peserta berada di tahap Ospek.</span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Update Tahap Manual -->
        <div class="card">
          <div class="card-header p-3"><i class="bi bi-arrows-move me-2 text-secondary"></i>Pindah Tahap Manual</div>
          <div class="card-body p-3">
            <form method="post" class="d-flex gap-2">
              <input type="hidden" name="action" value="update_tahap">
              <select name="tahap" class="form-select form-select-sm">
                <?php foreach(['pendaftaran','seleksi','ujian','pengumuman','daftar_ulang','ospek','selesai','ditolak'] as $t): ?>
                <option value="<?= $t ?>" <?= $p['tahap']===$t?'selected':'' ?>><?= ucfirst(str_replace('_',' ',$t)) ?></option>
                <?php endforeach; ?>
              </select>
              <button class="btn btn-sm btn-secondary">Pindah</button>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body></html>
