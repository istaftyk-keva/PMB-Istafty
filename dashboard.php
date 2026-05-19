<?php
require_once __DIR__ . '/config/config.php';
if (!isPeserta()) redirect('login.php');
$title = 'Dashboard Peserta';

$stmt = db()->prepare("SELECT * FROM pendaftar WHERE id = ?");
$stmt->execute([$_SESSION['peserta_id']]);
$p = $stmt->fetch();
if (!$p) { session_destroy(); redirect('login.php'); }

// Urutan tahap
$tahapList = [
    ['key'=>'pendaftaran',  'label'=>'Pendaftaran',   'icon'=>'bi-pencil-square'],
    ['key'=>'seleksi',      'label'=>'Seleksi Berkas', 'icon'=>'bi-file-check'],
    ['key'=>'ujian',        'label'=>'Ujian Masuk',    'icon'=>'bi-pen'],
    ['key'=>'pengumuman',   'label'=>'Pengumuman',     'icon'=>'bi-megaphone'],
    ['key'=>'daftar_ulang', 'label'=>'Daftar Ulang',  'icon'=>'bi-cash-coin'],
    ['key'=>'ospek',        'label'=>'Ospek',          'icon'=>'bi-people'],
];

$urutanTahap = array_column($tahapList, 'key');
$currentIdx  = array_search($p['tahap'], $urutanTahap);

include __DIR__ . '/includes/header.php';
?>
<div class="container py-4">
  <div class="row g-3">

    <!-- Kartu Info -->
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body text-center p-4">
          <div class="mb-3">
            <?php if ($p['foto'] && file_exists(UPLOAD_PATH.$p['foto'])): ?>
            <img src="uploads/<?= e($p['foto']) ?>" class="rounded-circle" width="80" height="80" style="object-fit:cover;">
            <?php else: ?>
            <div class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center" style="width:80px;height:80px;">
              <i class="bi bi-person-fill text-white" style="font-size:36px;"></i>
            </div>
            <?php endif; ?>
          </div>
          <h5 class="fw-bold mb-1"><?= e($p['nama']) ?></h5>
          <div class="small text-muted mb-2"><?= e($p['email']) ?></div>
          <div class="badge bg-primary mb-3"><?= e($p['no_pendaftaran']) ?></div>
          <table class="table table-sm text-start">
            <tr><td class="text-muted small">Program Studi</td><td class="small fw-semibold"><?= e($p['prodi']) ?></td></tr>
            <tr><td class="text-muted small">Jalur</td><td class="small"><?= e($p['jalur']) ?></td></tr>
            <tr><td class="text-muted small">Tahap</td><td><?= badgeTahap($p['tahap']) ?></td></tr>
          </table>
        </div>
      </div>
    </div>

    <!-- Progres Tahap + Info -->
    <div class="col-md-8">
      <!-- Step Bar -->
      <div class="card mb-3">
        <div class="card-body py-3 px-4">
          <div class="step-bar">
            <?php foreach ($tahapList as $i => $t):
              $cls = '';
              if ($i < $currentIdx) $cls = 'done';
              elseif ($i === $currentIdx) $cls = 'active';
            ?>
            <div class="step-item <?= $cls ?>">
              <div class="step-circle <?= $cls ?>">
                <?php if ($i < $currentIdx): ?><i class="bi bi-check-lg"></i>
                <?php else: ?><?= $i+1 ?><?php endif; ?>
              </div>
              <div class="step-label"><?= $t['label'] ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- Status Detail per Tahap -->
      <?php
      // Info berdasarkan tahap saat ini
      $info = [
        'pendaftaran'  => ['success', 'bi-check-circle', 'Pendaftaran selesai!', 'Dokumen Anda sedang menunggu proses seleksi berkas oleh panitia.'],
        'seleksi'      => ['warning', 'bi-hourglass-split', 'Seleksi Berkas Berlangsung', 'Panitia sedang memverifikasi kelengkapan dokumen Anda. Status: '.($p['status_seleksi'] === 'menunggu' ? '<strong>Menunggu verifikasi</strong>' : '<strong>'.ucfirst($p['status_seleksi']).'</strong>')],
        'ujian'        => ['info', 'bi-pen', 'Tahap Ujian Masuk', 'Anda diwajibkan mengikuti ujian masuk. Perhatikan jadwal yang diumumkan panitia.'],
        'pengumuman'   => ['primary', 'bi-megaphone', 'Pengumuman Hasil', 'Hasil seleksi: <strong>'.ucfirst($p['status_pengumuman']).'</strong>'. ($p['nilai_ujian'] ? ' | Nilai Ujian: <strong>'.$p['nilai_ujian'].'</strong>' : '')],
        'daftar_ulang' => ['warning', 'bi-cash-coin', 'Daftar Ulang & Pembayaran UKT', 'Status pembayaran: <strong>'.ucfirst($p['status_bayar']).'</strong>. Segera lakukan pembayaran UKT untuk mengkonfirmasi kehadiran Anda.'],
        'ospek'        => ['primary', 'bi-people', 'Orientasi Kampus (Ospek)', 'Selamat! Anda akan segera mengikuti kegiatan Ospek. Pantau informasi jadwal dari kampus.'],
        'selesai'      => ['success', 'bi-mortarboard', 'Proses Selesai!', 'Selamat datang di keluarga besar UHB! Proses PMB Anda telah selesai.'],
        'ditolak'      => ['danger', 'bi-x-circle', 'Tidak Diterima', 'Mohon maaf, Anda belum diterima pada seleksi ini. Anda dapat mencoba di gelombang berikutnya.'],
      ];
      [$alertType,$icon,$judul,$deskripsi] = $info[$p['tahap']] ?? ['secondary','bi-info-circle','Status tidak diketahui',''];
      ?>
      <div class="card mb-3">
        <div class="card-body p-3">
          <div class="alert alert-<?= $alertType ?> mb-0 d-flex gap-3 align-items-start">
            <i class="bi <?= $icon ?>" style="font-size:22px;flex-shrink:0;margin-top:2px;"></i>
            <div>
              <div class="fw-bold mb-1"><?= $judul ?></div>
              <div class="small"><?= $deskripsi ?></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Aksi: Upload bukti bayar jika tahap daftar_ulang -->
      <?php if ($p['tahap'] === 'daftar_ulang' && $p['status_bayar'] !== 'lunas'): ?>
      <div class="card mb-3">
        <div class="card-header p-3"><i class="bi bi-upload me-2"></i>Upload Bukti Pembayaran UKT</div>
        <div class="card-body p-3">
          <?php
          $uploadMsg = '';
          if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_FILES['bukti_bayar'])) {
              $ext = strtolower(pathinfo($_FILES['bukti_bayar']['name'], PATHINFO_EXTENSION));
              if (in_array($ext,['jpg','jpeg','png','pdf']) && $_FILES['bukti_bayar']['size'] <= 3*1024*1024) {
                  $fn = 'bukti_'.$p['id'].'_'.uniqid().'.'.$ext;
                  move_uploaded_file($_FILES['bukti_bayar']['tmp_name'], UPLOAD_PATH.$fn);
                  db()->prepare("UPDATE pendaftar SET bukti_bayar=?, status_bayar='proses' WHERE id=?")->execute([$fn, $p['id']]);
                  $uploadMsg = 'Bukti berhasil diupload! Menunggu konfirmasi admin.';
                  $p['status_bayar'] = 'proses';
              } else {
                  $uploadMsg = 'File tidak valid (JPG/PNG/PDF, maks 3MB).';
              }
          }
          if ($uploadMsg): ?>
          <div class="alert alert-info small py-2"><?= e($uploadMsg) ?></div>
          <?php endif; ?>
          <form method="post" enctype="multipart/form-data">
            <div class="mb-2">
              <label class="form-label fw-semibold small">Pilih file bukti transfer:</label>
              <input type="file" name="bukti_bayar" class="form-control form-control-sm" accept="image/*,.pdf" required>
            </div>
            <button type="submit" class="btn btn-success btn-sm"><i class="bi bi-upload me-1"></i>Upload Bukti</button>
          </form>
        </div>
      </div>
      <?php endif; ?>

      <!-- Ringkasan Data -->
      <div class="card">
        <div class="card-header p-3"><i class="bi bi-person-lines-fill me-2"></i>Data Pendaftaran</div>
        <div class="card-body p-0">
          <table class="table mb-0 table-sm">
            <tbody>
              <tr><td class="text-muted ps-3 py-2 small" width="160">No. Pendaftaran</td><td class="fw-bold small"><code><?= e($p['no_pendaftaran']) ?></code></td></tr>
              <tr><td class="text-muted ps-3 py-2 small">Nama Lengkap</td><td class="small"><?= e($p['nama']) ?></td></tr>
              <tr><td class="text-muted ps-3 py-2 small">Email</td><td class="small"><?= e($p['email']) ?></td></tr>
              <tr><td class="text-muted ps-3 py-2 small">No. HP</td><td class="small"><?= e($p['no_hp']) ?></td></tr>
              <tr><td class="text-muted ps-3 py-2 small">Asal Sekolah</td><td class="small"><?= e($p['asal_sekolah']) ?></td></tr>
              <tr><td class="text-muted ps-3 py-2 small">Program Studi</td><td class="small"><?= e($p['prodi']) ?></td></tr>
              <?php if ($p['nilai_ujian']): ?>
              <tr><td class="text-muted ps-3 py-2 small">Nilai Ujian</td><td class="small fw-bold text-success"><?= $p['nilai_ujian'] ?></td></tr>
              <?php endif; ?>
              <tr><td class="text-muted ps-3 py-2 small">Terdaftar Sejak</td><td class="small"><?= date('d M Y', strtotime($p['created_at'])) ?></td></tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
