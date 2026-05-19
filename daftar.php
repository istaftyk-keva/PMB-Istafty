<?php
require_once __DIR__ . '/config/config.php';
$title = 'Formulir Pendaftaran';
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama         = trim($_POST['nama'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $no_hp        = trim($_POST['no_hp'] ?? '');
    $tanggal_lahir= $_POST['tanggal_lahir'] ?? '';
    $asal_sekolah = trim($_POST['asal_sekolah'] ?? '');
    $prodi        = $_POST['prodi'] ?? '';
    $jalur        = $_POST['jalur'] ?? 'Reguler';
    $password     = $_POST['password'] ?? '';
    $password2    = $_POST['password2'] ?? '';

    if (!$nama)          $errors[] = 'Nama wajib diisi.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email tidak valid.';
    if (!$no_hp)         $errors[] = 'No. HP wajib diisi.';
    if (!$tanggal_lahir) $errors[] = 'Tanggal lahir wajib diisi.';
    if (!$asal_sekolah)  $errors[] = 'Asal sekolah wajib diisi.';
    if (!$prodi)         $errors[] = 'Pilih program studi.';
    if (strlen($password) < 6) $errors[] = 'Password minimal 6 karakter.';
    if ($password !== $password2) $errors[] = 'Konfirmasi password tidak cocok.';

    if (empty($errors)) {
        // Cek duplikat email
        $cek = db()->prepare("SELECT id FROM pendaftar WHERE email = ?");
        $cek->execute([$email]);
        if ($cek->fetch()) {
            $errors[] = 'Email sudah terdaftar.';
        }
    }

    // Upload foto
    $foto = null;
    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['jpg','jpeg','png'])) {
            $errors[] = 'Foto harus berformat JPG/PNG.';
        } elseif ($_FILES['foto']['size'] > 2*1024*1024) {
            $errors[] = 'Ukuran foto maksimal 2MB.';
        } else {
            $filename = 'foto_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['foto']['tmp_name'], UPLOAD_PATH . $filename);
            $foto = $filename;
        }
    }

    if (empty($errors)) {
        $no = generateNoPendaftaran();
        $stmt = db()->prepare("INSERT INTO pendaftar (no_pendaftaran, nama, email, no_hp, tanggal_lahir, asal_sekolah, prodi, jalur, password, foto) VALUES (?,?,?,?,?,?,?,?,?,?)");
        $stmt->execute([$no, $nama, $email, $no_hp, $tanggal_lahir, $asal_sekolah, $prodi, $jalur, password_hash($password, PASSWORD_DEFAULT), $foto]);
        flash('success', "Pendaftaran berhasil! Nomor Anda: <strong>$no</strong>. Simpan nomor ini.");
        redirect('login.php');
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="container py-4" style="max-width:700px;">
  <div class="card">
    <div class="card-header p-3">
      <i class="bi bi-pencil-square me-2 text-primary"></i>Formulir Pendaftaran Online
    </div>
    <div class="card-body p-4">

      <?php if ($errors): ?>
      <div class="alert alert-danger py-2">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <?= implode('<br>', array_map('e', $errors)) ?>
      </div>
      <?php endif; ?>

      <form method="post" enctype="multipart/form-data">
        <div class="row g-3">
          <div class="col-md-8">
            <label class="form-label fw-semibold">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" value="<?= e($_POST['nama'] ?? '') ?>" required>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Foto (JPG/PNG)</label>
            <input type="file" name="foto" class="form-control" accept="image/*">
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="<?= e($_POST['email'] ?? '') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">No. HP <span class="text-danger">*</span></label>
            <input type="tel" name="no_hp" class="form-control" placeholder="08xx" value="<?= e($_POST['no_hp'] ?? '') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Tanggal Lahir <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_lahir" class="form-control" value="<?= e($_POST['tanggal_lahir'] ?? '') ?>" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Asal Sekolah <span class="text-danger">*</span></label>
            <input type="text" name="asal_sekolah" class="form-control" value="<?= e($_POST['asal_sekolah'] ?? '') ?>" required>
          </div>
          <div class="col-md-8">
            <label class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
            <select name="prodi" class="form-select" required>
              <option value="">-- Pilih --</option>
              <?php foreach(['Teknik Informatika','Sistem Informasi','Manajemen','Akuntansi','Hukum','Kedokteran'] as $p): ?>
              <option value="<?= $p ?>" <?= ($_POST['prodi'] ?? '')===$p?'selected':'' ?>><?= $p ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-semibold">Jalur</label>
            <select name="jalur" class="form-select">
              <?php foreach(['Reguler','Prestasi','Beasiswa'] as $j): ?>
              <option value="<?= $j ?>"><?= $j ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
            <input type="password" name="password" class="form-control" placeholder="Min. 6 karakter" required>
          </div>
          <div class="col-md-6">
            <label class="form-label fw-semibold">Konfirmasi Password <span class="text-danger">*</span></label>
            <input type="password" name="password2" class="form-control" required>
          </div>
          <div class="col-12">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" id="setuju" required>
              <label class="form-check-label small" for="setuju">Saya menyatakan data yang saya isi adalah benar dan dapat dipertanggungjawabkan.</label>
            </div>
          </div>
          <div class="col-12 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="bi bi-send me-2"></i>Submit Pendaftaran</button>
            <a href="index.php" class="btn btn-outline-secondary">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
  <div class="text-center mt-3 small text-muted">
    Sudah pernah mendaftar? <a href="login.php">Login di sini</a>
  </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
