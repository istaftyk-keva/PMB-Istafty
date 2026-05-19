<?php
require_once __DIR__ . '/config/config.php';
$title = 'Beranda';
include __DIR__ . '/includes/header.php';
?>
<div class="container py-5">

  <!-- Hero -->
  <div class="rounded-4 text-white p-5 mb-4" style="background:linear-gradient(135deg,#1a3c6e,#2557a7);">
    <div class="row align-items-center">
      <div class="col-md-8">
        <h1 class="fw-bold mb-2">Penerimaan Mahasiswa Baru 2025</h1>
        <p class="mb-3 opacity-75">Daftarkan diri Anda secara online, mudah dan transparan. Pantau status pendaftaran kapan saja.</p>
        <div class="d-flex gap-2 flex-wrap">
          <a href="daftar.php" class="btn btn-warning fw-bold"><i class="bi bi-pencil-square me-2"></i>Daftar Sekarang</a>
          <a href="cek_status.php" class="btn btn-outline-light"><i class="bi bi-search me-2"></i>Cek Status</a>
        </div>
      </div>
      <div class="col-md-4 text-center d-none d-md-block">
        <i class="bi bi-mortarboard-fill" style="font-size:100px;opacity:.3;"></i>
      </div>
    </div>
  </div>

  <!-- Alur -->
  <div class="card mb-4">
    <div class="card-header p-3"><i class="bi bi-list-ol me-2 text-primary"></i>Alur Pendaftaran</div>
    <div class="card-body">
      <div class="row g-3 text-center">
        <?php
        $alur = [
          ['bi-pc-display',   '1. Pendaftaran Online', 'Isi formulir & upload dokumen'],
          ['bi-file-check',   '2. Seleksi Berkas',     'Verifikasi dokumen oleh panitia'],
          ['bi-pen',          '3. Ujian Masuk',         'Tes tertulis atau online'],
          ['bi-megaphone',    '4. Pengumuman',          'Hasil seleksi diumumkan'],
          ['bi-cash-coin',    '5. Daftar Ulang',        'Konfirmasi & bayar UKT'],
          ['bi-people',       '6. Ospek',               'Orientasi kampus'],
        ];
        foreach ($alur as [$icon,$title,$desc]):
        ?>
        <div class="col-md-2 col-4">
          <div class="p-3">
            <i class="bi <?= $icon ?>" style="font-size:32px;color:#1a3c6e;"></i>
            <div class="fw-bold mt-2" style="font-size:13px;"><?= $title ?></div>
            <div class="text-muted" style="font-size:11px;"><?= $desc ?></div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <!-- Info Gelombang -->
  <div class="row g-3">
    <div class="col-md-4">
      <div class="card border-0 text-white h-100" style="background:#1a3c6e;">
        <div class="card-body text-center p-4">
          <i class="bi bi-calendar-check" style="font-size:36px;opacity:.7;"></i>
          <h5 class="fw-bold mt-2 mb-1">Gelombang II</h5>
          <div class="opacity-75 small">Pendaftaran Dibuka</div>
          <div class="fw-bold mt-2">01 Apr – 31 Mei 2025</div>
          <a href="daftar.php" class="btn btn-warning btn-sm mt-3 fw-bold w-100">Daftar Sekarang</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body text-center p-4">
          <i class="bi bi-book" style="font-size:36px;color:#1a3c6e;"></i>
          <h5 class="fw-bold mt-2 mb-3">Program Studi</h5>
          <?php foreach(['Teknik Informatika','Sistem Informasi','Manajemen','Akuntansi','Hukum','Kedokteran'] as $p): ?>
          <div class="badge bg-light text-dark border me-1 mb-1"><?= $p ?></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body p-4">
          <h6 class="fw-bold mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Kontak PMB</h6>
          <div class="small text-muted">
            <div class="mb-2"><i class="bi bi-telephone me-2"></i>(0231) 123-456</div>
            <div class="mb-2"><i class="bi bi-envelope me-2"></i>pmb@uhb.ac.id</div>
            <div class="mb-2"><i class="bi bi-whatsapp me-2"></i>+62 812 3456 7890</div>
            <div class="mb-2"><i class="bi bi-clock me-2"></i>Sen–Jum 08:00–16:00</div>
          </div>
          <a href="cek_status.php" class="btn btn-outline-primary btn-sm w-100 mt-2">
            <i class="bi bi-search me-1"></i>Cek Status Pendaftaran
          </a>
        </div>
      </div>
    </div>
  </div>

</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
