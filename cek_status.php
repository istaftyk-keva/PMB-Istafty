<?php
require_once __DIR__ . '/config/config.php';
$title = 'Cek Status Pendaftaran';
$result = null;
$notFound = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $no = trim($_POST['no_pendaftaran'] ?? '');
    if ($no) {
        $stmt = db()->prepare("SELECT * FROM pendaftar WHERE no_pendaftaran = ?");
        $stmt->execute([$no]);
        $result = $stmt->fetch();
        if (!$result) $notFound = true;
    }
}

$tahapList = [
    'pendaftaran'=>1,'seleksi'=>2,'ujian'=>3,
    'pengumuman'=>4,'daftar_ulang'=>5,'ospek'=>6,'selesai'=>7
];

include __DIR__ . '/includes/header.php';
?>
<div class="container py-4" style="max-width:640px;">
  <div class="card">
    <div class="card-header p-3"><i class="bi bi-search me-2 text-primary"></i>Cek Status Pendaftaran</div>
    <div class="card-body p-4">
      <form method="post" class="d-flex gap-2">
        <input type="text" name="no_pendaftaran" class="form-control" placeholder="Masukkan nomor pendaftaran, cth: PMB2025001"
               value="<?= e($_POST['no_pendaftaran'] ?? '') ?>" required>
        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-search"></i></button>
      </form>
    </div>
  </div>

  <?php if ($notFound): ?>
  <div class="alert alert-warning mt-3">
    <i class="bi bi-exclamation-triangle me-2"></i>Nomor pendaftaran tidak ditemukan.
  </div>
  <?php endif; ?>

  <?php if ($result): ?>
  <div class="card mt-3">
    <div class="card-body p-4">
      <div class="d-flex align-items-center gap-3 mb-3">
        <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width:50px;height:50px;flex-shrink:0;">
          <i class="bi bi-person-fill text-white fs-4"></i>
        </div>
        <div>
          <h5 class="fw-bold mb-0"><?= e($result['nama']) ?></h5>
          <div class="small text-muted"><?= e($result['no_pendaftaran']) ?> · <?= e($result['prodi']) ?></div>
        </div>
        <div class="ms-auto"><?= badgeTahap($result['tahap']) ?></div>
      </div>

      <!-- Progress visual -->
      <?php
      $labels = ['Pendaftaran','Seleksi','Ujian','Pengumuman','Daftar Ulang','Ospek'];
      $current = $tahapList[$result['tahap']] ?? 1;
      ?>
      <div class="d-flex justify-content-between align-items-start mb-3" style="position:relative;">
        <div style="position:absolute;top:14px;left:0;right:0;height:2px;background:#dee2e6;z-index:0;"></div>
        <?php for ($i=1; $i<=6; $i++):
          $cls = $i < $current ? 'done' : ($i === $current ? 'active' : '');
        ?>
        <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:4px;position:relative;z-index:1;">
          <div style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;
            background:<?= $cls==='done'?'#198754':($cls==='active'?'#1a3c6e':'#fff') ?>;
            border:2px solid <?= $cls==='done'?'#198754':($cls==='active'?'#1a3c6e':'#dee2e6') ?>;
            color:<?= $cls?'#fff':'#adb5bd' ?>;">
            <?= $cls==='done' ? '✓' : $i ?>
          </div>
          <div style="font-size:9px;color:<?= $cls==='active'?'#1a3c6e':($cls==='done'?'#198754':'#adb5bd') ?>;font-weight:<?= $cls?700:400 ?>;text-align:center;">
            <?= $labels[$i-1] ?>
          </div>
        </div>
        <?php endfor; ?>
      </div>

      <!-- Detail -->
      <table class="table table-sm table-bordered mt-2 mb-0">
        <tr><td class="text-muted small" width="150">Status Seleksi</td><td class="small fw-semibold"><?= ucfirst(e($result['status_seleksi'])) ?></td></tr>
        <?php if ($result['nilai_ujian']): ?>
        <tr><td class="text-muted small">Nilai Ujian</td><td class="small fw-bold text-success"><?= $result['nilai_ujian'] ?></td></tr>
        <?php endif; ?>
        <tr><td class="text-muted small">Pengumuman</td><td class="small"><?= ucfirst(e($result['status_pengumuman'])) ?></td></tr>
        <tr><td class="text-muted small">Status Bayar</td><td class="small"><?= ucfirst(e($result['status_bayar'])) ?></td></tr>
        <tr><td class="text-muted small">Terdaftar</td><td class="small"><?= date('d M Y', strtotime($result['created_at'])) ?></td></tr>
      </table>
      <div class="mt-3 text-center">
        <a href="login.php" class="btn btn-outline-primary btn-sm">
          <i class="bi bi-box-arrow-in-right me-1"></i>Login untuk Akses Lengkap
        </a>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="text-center mt-3 small text-muted">
    Belum mendaftar? <a href="daftar.php">Daftar sekarang</a>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
