<?php
session_start();
if (!isset($_SESSION['step'])) {
    $_SESSION['step'] = 1;
}
$current_step = $_SESSION['step'];

$steps = [
    1 => ['title' => 'Pendaftaran Online', 'en' => 'Online Registration', 'icon' => 'bi-laptop', 'color' => '#1a6fc4'],
    2 => ['title' => 'Seleksi Berkas', 'en' => 'Document Review', 'icon' => 'bi-folder-check', 'color' => '#c47a1a'],
    3 => ['title' => 'Ujian Masuk', 'en' => 'Entrance Exam', 'icon' => 'bi-pencil-square', 'color' => '#1a6fc4'],
    4 => ['title' => 'Pengumuman Hasil', 'en' => 'Results Announcement', 'icon' => 'bi-megaphone', 'color' => '#c47a1a'],
    5 => ['title' => 'Daftar Ulang', 'en' => 'Re-enrollment & Payment', 'icon' => 'bi-credit-card', 'color' => '#1a6fc4'],
    6 => ['title' => 'Orientasi (OSPEK)', 'en' => 'Campus Orientation', 'icon' => 'bi-mortarboard', 'color' => '#c47a1a'],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>PMB — Penerimaan Mahasiswa Baru</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root {
    --navy: #0b1f4a;
    --gold: #d4960a;
    --gold-light: #f5c842;
    --blue: #1a6fc4;
    --blue-light: #4a9fe8;
    --cream: #fdf8f0;
    --white: #ffffff;
    --gray-soft: #f4f6fb;
    --text-dark: #0d1b2a;
    --text-muted: #6b7a99;
    --shadow-lg: 0 20px 60px rgba(11,31,74,0.15);
    --shadow-card: 0 8px 32px rgba(11,31,74,0.10);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: var(--cream);
    color: var(--text-dark);
    overflow-x: hidden;
}

/* ─── HERO ─── */
.hero {
    background: linear-gradient(135deg, var(--navy) 0%, #162d5e 50%, #1a4080 100%);
    position: relative;
    overflow: hidden;
    padding: 0;
}

.hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background-image:
        radial-gradient(circle at 20% 50%, rgba(212,150,10,0.15) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(26,111,196,0.25) 0%, transparent 40%),
        url("data:image/svg+xml,%3Csvg width='60' height='60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h60v60H0z' fill='none'/%3E%3Ccircle cx='30' cy='30' r='1' fill='rgba(255,255,255,0.04)'/%3E%3C/svg%3E");
    background-size: auto, auto, 60px 60px;
}

.hero-content {
    position: relative;
    z-index: 2;
    padding: 80px 0 60px;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: rgba(212,150,10,0.15);
    border: 1px solid rgba(212,150,10,0.4);
    color: var(--gold-light);
    padding: 8px 20px;
    border-radius: 50px;
    font-size: 0.8rem;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    margin-bottom: 24px;
    animation: fadeInDown 0.6s ease both;
}

.hero h1 {
    font-family: 'Playfair Display', serif;
    font-size: clamp(2.4rem, 5vw, 4rem);
    font-weight: 900;
    color: var(--white);
    line-height: 1.15;
    animation: fadeInUp 0.7s ease 0.1s both;
}

.hero h1 span {
    background: linear-gradient(90deg, var(--gold-light), var(--gold));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.hero-sub {
    color: rgba(255,255,255,0.7);
    font-size: 1.05rem;
    max-width: 520px;
    margin-top: 16px;
    line-height: 1.7;
    animation: fadeInUp 0.7s ease 0.2s both;
}

.hero-stats {
    display: flex;
    gap: 32px;
    margin-top: 40px;
    animation: fadeInUp 0.7s ease 0.3s both;
}

.stat-item { text-align: center; }
.stat-num {
    font-size: 2rem;
    font-weight: 800;
    color: var(--gold-light);
    line-height: 1;
}
.stat-label {
    font-size: 0.75rem;
    color: rgba(255,255,255,0.6);
    margin-top: 4px;
    font-weight: 500;
}

.hero-graphic {
    position: relative;
    animation: fadeInRight 0.8s ease 0.2s both;
}

.floating-card {
    background: rgba(255,255,255,0.08);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 20px;
    padding: 28px 32px;
    color: white;
}

.step-bubble {
    width: 56px; height: 56px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    margin-bottom: 12px;
    flex-shrink: 0;
}

.mini-step {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid rgba(255,255,255,0.08);
    animation: none;
    transition: all 0.3s;
}
.mini-step:last-child { border-bottom: none; }
.mini-step-num {
    width: 28px; height: 28px;
    border-radius: 50%;
    background: rgba(212,150,10,0.3);
    border: 1px solid rgba(212,150,10,0.5);
    color: var(--gold-light);
    font-size: 0.75rem;
    font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.mini-step-text { font-size: 0.85rem; color: rgba(255,255,255,0.85); font-weight: 500; }

/* ─── PROGRESS TRACKER ─── */
.progress-section {
    background: var(--white);
    border-bottom: 1px solid rgba(11,31,74,0.08);
    position: sticky;
    top: 0;
    z-index: 100;
    box-shadow: 0 4px 24px rgba(11,31,74,0.08);
}

.step-tracker {
    display: flex;
    align-items: center;
    padding: 20px 0;
    overflow-x: auto;
    scrollbar-width: none;
}
.step-tracker::-webkit-scrollbar { display: none; }

.tracker-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    min-width: 100px;
    cursor: pointer;
    transition: all 0.3s;
    text-decoration: none;
}

.tracker-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.2rem;
    transition: all 0.3s;
    background: var(--gray-soft);
    color: var(--text-muted);
    border: 2px solid transparent;
}

.tracker-item.active .tracker-icon {
    background: var(--navy);
    color: white;
    border-color: var(--gold);
    box-shadow: 0 8px 20px rgba(11,31,74,0.3);
    transform: translateY(-3px);
}

.tracker-item.done .tracker-icon {
    background: #e8f5e9;
    color: #2e7d32;
    border-color: #a5d6a7;
}

.tracker-label {
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--text-muted);
    text-align: center;
    line-height: 1.3;
}

.tracker-item.active .tracker-label { color: var(--navy); }
.tracker-item.done .tracker-label { color: #2e7d32; }

.tracker-connector {
    flex: 1;
    height: 2px;
    background: #e0e8f5;
    min-width: 20px;
    position: relative;
    top: -12px;
}
.tracker-connector.done { background: linear-gradient(90deg, #a5d6a7, #81c784); }

/* ─── MAIN CONTENT ─── */
.main-content { padding: 60px 0 80px; }

/* ─── STEP CARD ─── */
.step-card {
    background: white;
    border-radius: 28px;
    overflow: hidden;
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(11,31,74,0.06);
    display: none;
    animation: cardSlideIn 0.5s ease both;
}
.step-card.active { display: block; }

@keyframes cardSlideIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.step-card-header {
    padding: 40px 48px 36px;
    position: relative;
    overflow: hidden;
}

.step-card-header::before {
    content: '';
    position: absolute;
    right: -40px; top: -40px;
    width: 200px; height: 200px;
    border-radius: 50%;
    background: rgba(255,255,255,0.08);
}

.step-number-badge {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    background: rgba(255,255,255,0.15);
    border: 1px solid rgba(255,255,255,0.3);
    padding: 8px 16px;
    border-radius: 50px;
    color: white;
    font-size: 0.8rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    margin-bottom: 16px;
}

.step-card-header h2 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    color: white;
    font-weight: 900;
    margin-bottom: 8px;
}

.step-card-header .subtitle {
    color: rgba(255,255,255,0.75);
    font-size: 0.95rem;
}

.step-card-body { padding: 48px; }

/* ─── FORM STYLES ─── */
.form-label {
    font-weight: 600;
    font-size: 0.85rem;
    color: var(--navy);
    margin-bottom: 8px;
    letter-spacing: 0.02em;
}

.form-control, .form-select {
    border: 1.5px solid #e0e8f5;
    border-radius: 12px;
    padding: 12px 16px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.92rem;
    transition: all 0.2s;
    background: var(--gray-soft);
    color: var(--text-dark);
}

.form-control:focus, .form-select:focus {
    border-color: var(--blue);
    box-shadow: 0 0 0 4px rgba(26,111,196,0.1);
    background: white;
    outline: none;
}

.upload-zone {
    border: 2px dashed #c5d5e8;
    border-radius: 16px;
    padding: 40px;
    text-align: center;
    background: var(--gray-soft);
    cursor: pointer;
    transition: all 0.3s;
    position: relative;
}
.upload-zone:hover {
    border-color: var(--blue);
    background: rgba(26,111,196,0.04);
}
.upload-zone input[type=file] {
    position: absolute; inset: 0; opacity: 0; cursor: pointer;
}
.upload-icon {
    font-size: 2.5rem;
    color: var(--blue);
    margin-bottom: 12px;
}

/* ─── BUTTONS ─── */
.btn-primary-custom {
    background: linear-gradient(135deg, var(--navy), #1a3a6e);
    color: white;
    border: none;
    padding: 14px 36px;
    border-radius: 14px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 6px 20px rgba(11,31,74,0.25);
    text-decoration: none;
}
.btn-primary-custom:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(11,31,74,0.35);
    color: white;
}

.btn-gold {
    background: linear-gradient(135deg, var(--gold), #b8820a);
    color: white;
    border: none;
    padding: 14px 36px;
    border-radius: 14px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 700;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 6px 20px rgba(212,150,10,0.3);
    text-decoration: none;
}
.btn-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 30px rgba(212,150,10,0.45);
    color: white;
}

.btn-outline-custom {
    background: transparent;
    color: var(--navy);
    border: 2px solid var(--navy);
    padding: 12px 28px;
    border-radius: 14px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    transition: all 0.3s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
}
.btn-outline-custom:hover {
    background: var(--navy);
    color: white;
}

/* ─── INFO BOX ─── */
.info-box {
    background: linear-gradient(135deg, #e8f4fd, #f0f8ff);
    border-left: 4px solid var(--blue);
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 28px;
}
.info-box i { color: var(--blue); margin-right: 8px; }

.warning-box {
    background: linear-gradient(135deg, #fff8e8, #fffbf0);
    border-left: 4px solid var(--gold);
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 28px;
}
.warning-box i { color: var(--gold); margin-right: 8px; }

.success-box {
    background: linear-gradient(135deg, #e8f5e9, #f1f8f2);
    border-left: 4px solid #2e7d32;
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 28px;
}
.success-box i { color: #2e7d32; margin-right: 8px; }

/* ─── REQUIREMENT LIST ─── */
.req-list { list-style: none; padding: 0; }
.req-list li {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #f0f4fb;
    font-size: 0.92rem;
}
.req-list li:last-child { border-bottom: none; }
.req-list li .req-icon {
    width: 28px; height: 28px;
    border-radius: 8px;
    background: #e8f4fd;
    color: var(--blue);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    font-size: 0.85rem;
}

/* ─── EXAM SCHEDULE ─── */
.exam-card {
    border: 1.5px solid #e0e8f5;
    border-radius: 16px;
    padding: 24px;
    transition: all 0.3s;
    cursor: pointer;
    position: relative;
}
.exam-card:hover {
    border-color: var(--blue);
    box-shadow: 0 8px 24px rgba(26,111,196,0.12);
    transform: translateY(-2px);
}
.exam-card.selected {
    border-color: var(--blue);
    background: linear-gradient(135deg, #f0f7ff, #e8f4fd);
}
.exam-card .selected-badge {
    display: none;
    position: absolute;
    top: 12px; right: 12px;
}
.exam-card.selected .selected-badge { display: flex; }

/* ─── RESULT CARD ─── */
.result-card {
    border-radius: 24px;
    overflow: hidden;
    border: 1.5px solid #e0e8f5;
}
.result-header {
    background: linear-gradient(135deg, #0b1f4a, #1a3a6e);
    padding: 40px;
    text-align: center;
    color: white;
}
.result-avatar {
    width: 90px; height: 90px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--gold), #f5c842);
    display: flex; align-items: center; justify-content: center;
    font-size: 2.5rem;
    margin: 0 auto 16px;
    font-weight: 900;
    color: var(--navy);
}
.result-body { padding: 36px; background: white; }
.result-detail {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 0;
    border-bottom: 1px solid #f0f4fb;
    font-size: 0.9rem;
}
.result-detail:last-child { border-bottom: none; }
.result-detail .label { color: var(--text-muted); font-weight: 500; }
.result-detail .value { font-weight: 700; color: var(--navy); }

/* ─── PAYMENT ─── */
.payment-method {
    border: 2px solid #e0e8f5;
    border-radius: 16px;
    padding: 20px 24px;
    cursor: pointer;
    transition: all 0.3s;
    display: flex; align-items: center; gap: 16px;
}
.payment-method:hover { border-color: var(--blue); }
.payment-method.selected { border-color: var(--blue); background: #f0f7ff; }
.payment-icon {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem;
    background: var(--gray-soft);
    flex-shrink: 0;
}

/* ─── SUCCESS PAGE ─── */
.success-hero {
    text-align: center;
    padding: 60px 40px;
}
.success-icon-wrap {
    width: 120px; height: 120px;
    border-radius: 50%;
    background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
    display: flex; align-items: center; justify-content: center;
    font-size: 3.5rem;
    margin: 0 auto 28px;
    animation: successPop 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) both;
}
@keyframes successPop {
    from { transform: scale(0); opacity: 0; }
    to { transform: scale(1); opacity: 1; }
}

.welcome-card {
    background: linear-gradient(135deg, var(--navy), #1a4080);
    border-radius: 24px;
    padding: 48px;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}
.welcome-card::before {
    content: '🎓';
    position: absolute;
    font-size: 8rem;
    opacity: 0.05;
    top: -20px; right: -20px;
}

/* ─── SIDEBAR ─── */
.sidebar-card {
    background: white;
    border-radius: 20px;
    padding: 28px;
    box-shadow: var(--shadow-card);
    border: 1px solid rgba(11,31,74,0.06);
    margin-bottom: 24px;
}
.sidebar-card h6 {
    font-weight: 700;
    color: var(--navy);
    margin-bottom: 16px;
    font-size: 0.9rem;
    letter-spacing: 0.02em;
    text-transform: uppercase;
}

.timeline-mini { position: relative; padding-left: 24px; }
.timeline-mini::before {
    content: '';
    position: absolute;
    left: 8px; top: 0; bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, var(--blue), var(--gold));
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -20px; top: 4px;
    width: 10px; height: 10px;
    border-radius: 50%;
    background: var(--blue);
    border: 2px solid white;
    box-shadow: 0 0 0 2px var(--blue);
}
.timeline-item.done::before { background: #2e7d32; box-shadow: 0 0 0 2px #2e7d32; }
.timeline-item .t-title { font-size: 0.82rem; font-weight: 700; color: var(--navy); }
.timeline-item .t-date { font-size: 0.75rem; color: var(--text-muted); margin-top: 2px; }

/* ─── ANIMATIONS ─── */
@keyframes fadeInDown {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
@keyframes fadeInRight {
    from { opacity: 0; transform: translateX(30px); }
    to { opacity: 1; transform: translateX(0); }
}

/* ─── FOOTER ─── */
footer {
    background: var(--navy);
    color: rgba(255,255,255,0.6);
    padding: 40px 0;
    font-size: 0.85rem;
    text-align: center;
}
footer a { color: var(--gold-light); text-decoration: none; }

/* ─── RESPONSIVE ─── */
@media (max-width: 768px) {
    .hero-stats { gap: 20px; }
    .step-card-header { padding: 28px 24px 24px; }
    .step-card-body { padding: 28px 24px; }
    .hero-content { padding: 50px 0 40px; }
}
</style>
</head>
<body>

<!-- ─── HERO ─── -->
<section class="hero">
  <div class="container">
    <div class="hero-content">
      <div class="row align-items-center g-5">
        <div class="col-lg-6">
          <div class="hero-badge">
            <i class="bi bi-star-fill"></i>
            Penerimaan Mahasiswa Baru 2025/2026
          </div>
          <h1>
            Mulai Perjalanan<br>
            <span>Akademik Anda</span><br>
            Bersama Kami
          </h1>
          <p class="hero-sub">
            Ikuti proses penerimaan mahasiswa baru secara online, cepat, dan transparan.
            Dari pendaftaran hingga orientasi kampus.
          </p>
          <div class="hero-stats">
            <div class="stat-item">
              <div class="stat-num">12K+</div>
              <div class="stat-label">Pendaftar</div>
            </div>
            <div class="stat-item">
              <div class="stat-num">48</div>
              <div class="stat-label">Program Studi</div>
            </div>
            <div class="stat-item">
              <div class="stat-num">6</div>
              <div class="stat-label">Tahapan</div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="hero-graphic">
            <div class="floating-card">
              <div class="d-flex align-items-center gap-3 mb-24" style="margin-bottom:20px">
                <div class="step-bubble" style="background:rgba(212,150,10,0.2)">📋</div>
                <div>
                  <div style="font-weight:700;font-size:1rem">Alur Pendaftaran</div>
                  <div style="font-size:0.8rem;opacity:0.6">6 Tahap Proses PMB</div>
                </div>
              </div>
              <?php foreach($steps as $num => $step): ?>
              <div class="mini-step">
                <div class="mini-step-num"><?=$num?></div>
                <div class="mini-step-text"><?=$step['title']?></div>
                <?php if($num < $current_step): ?>
                  <span class="ms-auto" style="color:#81c784;font-size:0.85rem">✓</span>
                <?php elseif($num == $current_step): ?>
                  <span class="ms-auto" style="color:var(--gold-light);font-size:0.75rem;font-weight:600">Aktif</span>
                <?php endif; ?>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ─── STICKY PROGRESS ─── -->
<section class="progress-section">
  <div class="container">
    <div class="step-tracker">
      <?php foreach($steps as $num => $step):
        $class = 'tracker-item';
        if ($num < $current_step) $class .= ' done';
        elseif ($num == $current_step) $class .= ' active';
      ?>
        <a href="?step=<?=$num?>" class="<?=$class?>">
          <div class="tracker-icon">
            <?php if($num < $current_step): ?>
              <i class="bi bi-check-lg"></i>
            <?php else: ?>
              <i class="bi <?=$step['icon']?>"></i>
            <?php endif; ?>
          </div>
          <div class="tracker-label"><?=$step['title']?></div>
        </a>
        <?php if($num < count($steps)): ?>
        <div class="tracker-connector <?=($num < $current_step ? 'done' : '')?>"></div>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ─── MAIN CONTENT ─── -->
<section class="main-content">
  <div class="container">
    <div class="row g-4">
      <!-- STEP CARDS -->
      <div class="col-lg-8">

        <?php
        // Handle step navigation
        if (isset($_GET['step'])) {
            $requested = (int)$_GET['step'];
            if ($requested >= 1 && $requested <= 6) {
                $_SESSION['step'] = $requested;
                $current_step = $requested;
            }
        }
        ?>

        <!-- ── STEP 1: ONLINE REGISTRATION ── -->
        <div class="step-card <?=($current_step==1?'active':'')?>">
          <div class="step-card-header" style="background:linear-gradient(135deg,#0b1f4a,#1a3a6e)">
            <div class="step-number-badge"><i class="bi bi-laptop"></i> Langkah 1 dari 6</div>
            <h2>Pendaftaran Online</h2>
            <p class="subtitle">Isi formulir pendaftaran dan unggah dokumen yang diperlukan</p>
          </div>
          <div class="step-card-body">
            <div class="info-box">
              <i class="bi bi-info-circle-fill"></i>
              <strong>Pastikan data yang Anda isi sudah benar.</strong> Data tidak dapat diubah setelah pengiriman.
            </div>

            <form action="process.php" method="POST" enctype="multipart/form-data">
              <input type="hidden" name="step" value="1">
              <div class="row g-4">
                <div class="col-md-6">
                  <label class="form-label">Nama Lengkap *</label>
                  <input type="text" class="form-control" placeholder="Masukkan nama lengkap sesuai KTP" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Nomor Induk Kependudukan (NIK) *</label>
                  <input type="text" class="form-control" placeholder="16 digit NIK" maxlength="16" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tanggal Lahir *</label>
                  <input type="date" class="form-control" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Jenis Kelamin *</label>
                  <select class="form-select" required>
                    <option value="">-- Pilih --</option>
                    <option>Laki-laki</option>
                    <option>Perempuan</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Email Aktif *</label>
                  <input type="email" class="form-control" placeholder="nama@email.com" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Nomor WhatsApp *</label>
                  <input type="tel" class="form-control" placeholder="08xx-xxxx-xxxx" required>
                </div>
                <div class="col-12">
                  <label class="form-label">Program Studi Pilihan *</label>
                  <select class="form-select" required>
                    <option value="">-- Pilih Program Studi --</option>
                    <optgroup label="Fakultas Teknik">
                      <option>Teknik Informatika</option>
                      <option>Sistem Informasi</option>
                      <option>Teknik Sipil</option>
                      <option>Teknik Elektro</option>
                    </optgroup>
                    <optgroup label="Fakultas Ekonomi & Bisnis">
                      <option>Manajemen</option>
                      <option>Akuntansi</option>
                      <option>Ekonomi Pembangunan</option>
                    </optgroup>
                    <optgroup label="Fakultas Hukum">
                      <option>Ilmu Hukum</option>
                    </optgroup>
                    <optgroup label="Fakultas Kedokteran">
                      <option>Pendidikan Dokter</option>
                      <option>Farmasi</option>
                    </optgroup>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Asal Sekolah *</label>
                  <input type="text" class="form-control" placeholder="Nama SMA/SMK/MA asal" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Tahun Lulus *</label>
                  <select class="form-select" required>
                    <option value="">-- Pilih Tahun --</option>
                    <option>2025</option><option>2024</option><option>2023</option><option>2022</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label">Rata-rata Nilai Rapor *</label>
                  <input type="number" class="form-control" placeholder="cth: 85.50" min="0" max="100" step="0.01" required>
                </div>

                <div class="col-12"><hr style="border-color:#e0e8f5"></div>

                <div class="col-12">
                  <label class="form-label">Upload Foto 3x4 (JPG/PNG, max 2MB) *</label>
                  <div class="upload-zone">
                    <input type="file" accept="image/*">
                    <div class="upload-icon"><i class="bi bi-image"></i></div>
                    <div style="font-weight:600;color:var(--navy);margin-bottom:6px">Klik atau seret foto ke sini</div>
                    <div style="font-size:0.82rem;color:var(--text-muted)">JPG, PNG maksimal 2MB</div>
                  </div>
                </div>
                <div class="col-12">
                  <label class="form-label">Upload Ijazah/SKL *</label>
                  <div class="upload-zone">
                    <input type="file" accept=".pdf,.jpg,.png">
                    <div class="upload-icon"><i class="bi bi-file-earmark-text"></i></div>
                    <div style="font-weight:600;color:var(--navy);margin-bottom:6px">Upload Ijazah atau SKL</div>
                    <div style="font-size:0.82rem;color:var(--text-muted)">PDF, JPG, PNG maksimal 5MB</div>
                  </div>
                </div>
              </div>

              <div class="d-flex justify-content-end mt-5">
                <a href="?step=2" class="btn-primary-custom">
                  Lanjut ke Seleksi Berkas <i class="bi bi-arrow-right"></i>
                </a>
              </div>
            </form>
          </div>
        </div>

        <!-- ── STEP 2: DOCUMENT REVIEW ── -->
        <div class="step-card <?=($current_step==2?'active':'')?>">
          <div class="step-card-header" style="background:linear-gradient(135deg,#8a5a00,#c47a1a)">
            <div class="step-number-badge"><i class="bi bi-folder-check"></i> Langkah 2 dari 6</div>
            <h2>Seleksi Berkas</h2>
            <p class="subtitle">Tim kami sedang memeriksa kelengkapan dan keabsahan dokumen Anda</p>
          </div>
          <div class="step-card-body">
            <div class="warning-box">
              <i class="bi bi-clock-history"></i>
              <strong>Proses verifikasi memerlukan 3–5 hari kerja.</strong> Anda akan mendapat notifikasi via email dan WhatsApp.
            </div>

            <h6 style="font-weight:700;color:var(--navy);margin-bottom:16px">Dokumen yang Diperlukan</h6>
            <ul class="req-list mb-4">
              <li>
                <div class="req-icon"><i class="bi bi-image"></i></div>
                <div>
                  <strong>Foto 3x4 Terbaru</strong><br>
                  <span style="font-size:0.82rem;color:var(--text-muted)">Background merah/biru, berpakaian rapi</span>
                </div>
                <span class="ms-auto badge bg-success">✓ Diterima</span>
              </li>
              <li>
                <div class="req-icon"><i class="bi bi-file-earmark-text"></i></div>
                <div>
                  <strong>Ijazah / Surat Keterangan Lulus</strong><br>
                  <span style="font-size:0.82rem;color:var(--text-muted)">Dilegalisir oleh sekolah</span>
                </div>
                <span class="ms-auto badge bg-success">✓ Diterima</span>
              </li>
              <li>
                <div class="req-icon"><i class="bi bi-card-text"></i></div>
                <div>
                  <strong>Kartu Identitas (KTP/KK)</strong><br>
                  <span style="font-size:0.82rem;color:var(--text-muted)">Scan jelas dan terbaca</span>
                </div>
                <span class="ms-auto badge" style="background:#fff3cd;color:#856404">⏳ Review</span>
              </li>
              <li>
                <div class="req-icon"><i class="bi bi-file-earmark-bar-graph"></i></div>
                <div>
                  <strong>Transkrip Nilai / Rapor</strong><br>
                  <span style="font-size:0.82rem;color:var(--text-muted)">Semester 1–6 SMA</span>
                </div>
                <span class="ms-auto badge" style="background:#fff3cd;color:#856404">⏳ Review</span>
              </li>
              <li>
                <div class="req-icon"><i class="bi bi-award"></i></div>
                <div>
                  <strong>Sertifikat Prestasi (Opsional)</strong><br>
                  <span style="font-size:0.82rem;color:var(--text-muted)">Nilai tambah seleksi</span>
                </div>
                <span class="ms-auto badge bg-secondary">– Tidak Diunggah</span>
              </li>
            </ul>

            <div class="row g-3">
              <div class="col-md-4">
                <div style="background:linear-gradient(135deg,#e8f5e9,#f1f8f2);border-radius:16px;padding:20px;text-align:center">
                  <div style="font-size:1.8rem;font-weight:800;color:#2e7d32">2/5</div>
                  <div style="font-size:0.82rem;color:var(--text-muted)">Dokumen Disetujui</div>
                </div>
              </div>
              <div class="col-md-4">
                <div style="background:linear-gradient(135deg,#fff8e8,#fffbf0);border-radius:16px;padding:20px;text-align:center">
                  <div style="font-size:1.8rem;font-weight:800;color:var(--gold)">2/5</div>
                  <div style="font-size:0.82rem;color:var(--text-muted)">Sedang Diverifikasi</div>
                </div>
              </div>
              <div class="col-md-4">
                <div style="background:linear-gradient(135deg,#f3f4f6,#fafafa);border-radius:16px;padding:20px;text-align:center">
                  <div style="font-size:1.8rem;font-weight:800;color:#9e9e9e">1/5</div>
                  <div style="font-size:0.82rem;color:var(--text-muted)">Belum Diunggah</div>
                </div>
              </div>
            </div>

            <div class="d-flex justify-content-between mt-5">
              <a href="?step=1" class="btn-outline-custom"><i class="bi bi-arrow-left"></i> Kembali</a>
              <a href="?step=3" class="btn-primary-custom">Lanjut ke Ujian Masuk <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- ── STEP 3: ENTRANCE EXAM ── -->
        <div class="step-card <?=($current_step==3?'active':'')?>">
          <div class="step-card-header" style="background:linear-gradient(135deg,#0b1f4a,#1a3a6e)">
            <div class="step-number-badge"><i class="bi bi-pencil-square"></i> Langkah 3 dari 6</div>
            <h2>Ujian Masuk</h2>
            <p class="subtitle">Pilih jadwal ujian dan selesaikan asesmen tertulis & online</p>
          </div>
          <div class="step-card-body">
            <div class="info-box">
              <i class="bi bi-info-circle-fill"></i>
              Ujian terdiri dari <strong>Tes Potensi Akademik (TPA)</strong>, <strong>Bahasa Inggris</strong>, dan <strong>Tes Minat-Bakat</strong>. Durasi total: <strong>3 jam</strong>.
            </div>

            <h6 style="font-weight:700;color:var(--navy);margin-bottom:16px">Pilih Jadwal Ujian</h6>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <div class="exam-card selected" onclick="selectExam(this)">
                  <div class="selected-badge">
                    <span class="badge" style="background:var(--blue)"><i class="bi bi-check"></i> Dipilih</span>
                  </div>
                  <div style="font-weight:700;color:var(--navy);margin-bottom:4px">Senin, 14 Juli 2025</div>
                  <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:8px">08:00 – 11:00 WIB</div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success">Tersedia</span>
                    <span style="font-size:0.8rem;color:var(--text-muted)">248 kursi tersisa</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="exam-card" onclick="selectExam(this)">
                  <div class="selected-badge">
                    <span class="badge" style="background:var(--blue)"><i class="bi bi-check"></i> Dipilih</span>
                  </div>
                  <div style="font-weight:700;color:var(--navy);margin-bottom:4px">Rabu, 16 Juli 2025</div>
                  <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:8px">13:00 – 16:00 WIB</div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success">Tersedia</span>
                    <span style="font-size:0.8rem;color:var(--text-muted)">187 kursi tersisa</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="exam-card" onclick="selectExam(this)">
                  <div class="selected-badge">
                    <span class="badge" style="background:var(--blue)"><i class="bi bi-check"></i> Dipilih</span>
                  </div>
                  <div style="font-weight:700;color:var(--navy);margin-bottom:4px">Sabtu, 19 Juli 2025</div>
                  <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:8px">08:00 – 11:00 WIB</div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success">Tersedia</span>
                    <span style="font-size:0.8rem;color:var(--text-muted)">310 kursi tersisa</span>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="exam-card" onclick="selectExam(this)">
                  <div class="selected-badge">
                    <span class="badge" style="background:var(--blue)"><i class="bi bi-check"></i> Dipilih</span>
                  </div>
                  <div style="font-weight:700;color:var(--navy);margin-bottom:4px">Senin, 21 Juli 2025</div>
                  <div style="font-size:0.85rem;color:var(--text-muted);margin-bottom:8px">13:00 – 16:00 WIB</div>
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge" style="background:#ffecb3;color:#856404">Hampir Penuh</span>
                    <span style="font-size:0.8rem;color:var(--text-muted)">42 kursi tersisa</span>
                  </div>
                </div>
              </div>
            </div>

            <h6 style="font-weight:700;color:var(--navy);margin-bottom:12px">Materi Ujian</h6>
            <div class="row g-3 mb-4">
              <?php
              $subjects = [
                ['Tes Potensi Akademik','60 menit, 50 soal','bi-brain','#1a6fc4','#e8f4fd'],
                ['Bahasa Inggris','45 menit, 40 soal','bi-translate','#2e7d32','#e8f5e9'],
                ['Tes Minat & Bakat','30 menit, 30 soal','bi-stars','#8a5a00','#fff8e8'],
              ];
              foreach($subjects as $s): ?>
              <div class="col-md-4">
                <div style="background:<?=$s[4]?>;border-radius:14px;padding:20px;border:1px solid rgba(0,0,0,0.05)">
                  <i class="bi <?=$s[2]?>" style="font-size:1.6rem;color:<?=$s[3]?>"></i>
                  <div style="font-weight:700;color:var(--navy);margin-top:10px;margin-bottom:4px;font-size:0.9rem"><?=$s[0]?></div>
                  <div style="font-size:0.8rem;color:var(--text-muted)"><?=$s[1]?></div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>

            <div class="d-flex justify-content-between mt-4">
              <a href="?step=2" class="btn-outline-custom"><i class="bi bi-arrow-left"></i> Kembali</a>
              <a href="?step=4" class="btn-primary-custom">Konfirmasi Jadwal <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- ── STEP 4: RESULTS ── -->
        <div class="step-card <?=($current_step==4?'active':'')?>">
          <div class="step-card-header" style="background:linear-gradient(135deg,#8a5a00,#c47a1a)">
            <div class="step-number-badge"><i class="bi bi-megaphone"></i> Langkah 4 dari 6</div>
            <h2>Pengumuman Hasil</h2>
            <p class="subtitle">Hasil seleksi penerimaan mahasiswa baru</p>
          </div>
          <div class="step-card-body">
            <div class="success-box mb-4">
              <i class="bi bi-trophy-fill" style="color:#f5c842"></i>
              <strong>🎉 Selamat! Anda Dinyatakan LULUS Seleksi!</strong>
            </div>

            <div class="result-card mb-4">
              <div class="result-header">
                <div class="result-avatar">A</div>
                <h4 style="font-weight:800;margin-bottom:4px">Ahmad Fauzi Ramadhan</h4>
                <div style="opacity:0.7;font-size:0.9rem">PMB-2025-0001847</div>
                <div class="mt-3">
                  <span style="background:rgba(245,200,66,0.2);border:1px solid rgba(245,200,66,0.5);color:#f5c842;padding:8px 24px;border-radius:50px;font-size:0.85rem;font-weight:700">
                    ✓ DITERIMA
                  </span>
                </div>
              </div>
              <div class="result-body">
                <div class="result-detail">
                  <span class="label">Program Studi</span>
                  <span class="value">Teknik Informatika</span>
                </div>
                <div class="result-detail">
                  <span class="label">Nilai TPA</span>
                  <span class="value" style="color:#2e7d32">87.5 / 100</span>
                </div>
                <div class="result-detail">
                  <span class="label">Nilai Bahasa Inggris</span>
                  <span class="value" style="color:#2e7d32">82.0 / 100</span>
                </div>
                <div class="result-detail">
                  <span class="label">Nilai Minat-Bakat</span>
                  <span class="value" style="color:#2e7d32">90.0 / 100</span>
                </div>
                <div class="result-detail">
                  <span class="label">Total Nilai</span>
                  <span class="value" style="font-size:1.1rem;color:var(--blue)">86.5 / 100</span>
                </div>
                <div class="result-detail">
                  <span class="label">Peringkat</span>
                  <span class="value">#23 dari 1.247 peserta</span>
                </div>
                <div class="result-detail">
                  <span class="label">Batas Daftar Ulang</span>
                  <span class="value" style="color:#d32f2f">28 Juli 2025</span>
                </div>
              </div>
            </div>

            <div class="warning-box">
              <i class="bi bi-exclamation-triangle-fill"></i>
              <strong>Penting!</strong> Segera lakukan daftar ulang sebelum <strong>28 Juli 2025</strong>.
              Keterlambatan akan mengakibatkan pembatalan penerimaan.
            </div>

            <div class="d-flex justify-content-between">
              <a href="?step=3" class="btn-outline-custom"><i class="bi bi-arrow-left"></i> Kembali</a>
              <div class="d-flex gap-3">
                <a href="#" class="btn-outline-custom"><i class="bi bi-download"></i> Unduh Surat</a>
                <a href="?step=5" class="btn-gold">Lanjut Daftar Ulang <i class="bi bi-arrow-right"></i></a>
              </div>
            </div>
          </div>
        </div>

        <!-- ── STEP 5: RE-ENROLLMENT & PAYMENT ── -->
        <div class="step-card <?=($current_step==5?'active':'')?>">
          <div class="step-card-header" style="background:linear-gradient(135deg,#0b1f4a,#1a3a6e)">
            <div class="step-number-badge"><i class="bi bi-credit-card"></i> Langkah 5 dari 6</div>
            <h2>Daftar Ulang & Pembayaran</h2>
            <p class="subtitle">Selesaikan proses registrasi ulang dan pembayaran biaya kuliah</p>
          </div>
          <div class="step-card-body">
            <!-- Summary -->
            <div style="background:var(--gray-soft);border-radius:16px;padding:24px;margin-bottom:28px">
              <div style="font-weight:700;color:var(--navy);margin-bottom:16px;font-size:0.95rem">Rincian Biaya</div>
              <?php
              $fees = [
                ['Uang Pangkal (Sekali Bayar)', 'Rp 5.000.000'],
                ['SPP Semester I', 'Rp 3.500.000'],
                ['Biaya Praktikum', 'Rp 800.000'],
                ['Biaya OSPEK', 'Rp 350.000'],
                ['Asuransi Mahasiswa', 'Rp 150.000'],
              ];
              foreach($fees as $f): ?>
              <div class="d-flex justify-content-between py-2" style="border-bottom:1px solid #e0e8f5;font-size:0.9rem">
                <span style="color:var(--text-muted)"><?=$f[0]?></span>
                <span style="font-weight:600;color:var(--navy)"><?=$f[1]?></span>
              </div>
              <?php endforeach; ?>
              <div class="d-flex justify-content-between pt-3 mt-1">
                <span style="font-weight:700;color:var(--navy)">Total Pembayaran</span>
                <span style="font-weight:800;color:var(--blue);font-size:1.1rem">Rp 9.800.000</span>
              </div>
            </div>

            <h6 style="font-weight:700;color:var(--navy);margin-bottom:16px">Metode Pembayaran</h6>
            <div class="row g-3 mb-4">
              <?php
              $methods = [
                ['bi-bank', '#1a6fc4', 'Transfer Bank', 'BCA, BNI, BRI, Mandiri'],
                ['bi-phone', '#2e7d32', 'QRIS', 'Scan QR Code'],
                ['bi-credit-card-2-front', '#8a5a00', 'Virtual Account', 'Otomatis aktif'],
                ['bi-building', '#5e35b1', 'Bayar di Kasir', 'Kampus lt. 1'],
              ];
              foreach($methods as $i => $m): ?>
              <div class="col-md-6">
                <div class="payment-method <?=($i==0?'selected':'')?>" onclick="selectPayment(this)">
                  <div class="payment-icon" style="background:<?=$m[2]?>18;color:<?=$m[2]?>">
                    <i class="bi <?=$m[0]?>"></i>
                  </div>
                  <div>
                    <div style="font-weight:700;color:var(--navy);font-size:0.9rem"><?=$m[2]?></div>
                    <div style="font-size:0.8rem;color:var(--text-muted)"><?=$m[3]?></div>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
            </div>

            <!-- Bank Transfer Detail -->
            <div style="background:linear-gradient(135deg,#e8f4fd,#f0f8ff);border-radius:16px;padding:24px;margin-bottom:28px">
              <div style="font-weight:700;color:var(--navy);margin-bottom:16px">Detail Transfer Bank BCA</div>
              <div class="row g-3">
                <div class="col-md-6">
                  <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:4px">Nama Rekening</div>
                  <div style="font-weight:700;color:var(--navy)">Universitas Nusantara</div>
                </div>
                <div class="col-md-6">
                  <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:4px">Nomor Rekening</div>
                  <div style="font-weight:700;color:var(--navy);font-size:1.1rem">1234-5678-90 <button onclick="navigator.clipboard.writeText('1234567890')" class="btn btn-sm" style="background:var(--blue);color:white;border-radius:6px;padding:2px 8px;font-size:0.7rem"><i class="bi bi-copy"></i></button></div>
                </div>
                <div class="col-md-6">
                  <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:4px">Kode Unik</div>
                  <div style="font-weight:700;color:#d32f2f;font-size:1.1rem">Rp 9.800.847</div>
                </div>
                <div class="col-md-6">
                  <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:4px">Batas Pembayaran</div>
                  <div style="font-weight:700;color:#d32f2f">28 Juli 2025, 23:59</div>
                </div>
              </div>
            </div>

            <h6 style="font-weight:700;color:var(--navy);margin-bottom:12px">Upload Bukti Pembayaran</h6>
            <div class="upload-zone mb-4">
              <input type="file" accept="image/*,.pdf">
              <div class="upload-icon"><i class="bi bi-receipt"></i></div>
              <div style="font-weight:600;color:var(--navy);margin-bottom:6px">Upload Bukti Transfer</div>
              <div style="font-size:0.82rem;color:var(--text-muted)">JPG, PNG, PDF maksimal 5MB</div>
            </div>

            <div class="d-flex justify-content-between">
              <a href="?step=4" class="btn-outline-custom"><i class="bi bi-arrow-left"></i> Kembali</a>
              <a href="?step=6" class="btn-gold">Konfirmasi Pembayaran <i class="bi bi-arrow-right"></i></a>
            </div>
          </div>
        </div>

        <!-- ── STEP 6: ORIENTATION ── -->
        <div class="step-card <?=($current_step==6?'active':'')?>">
          <div class="step-card-header" style="background:linear-gradient(135deg,#1a5c00,#2e7d32)">
            <div class="step-number-badge"><i class="bi bi-mortarboard"></i> Langkah 6 dari 6</div>
            <h2>Orientasi Kampus (OSPEK)</h2>
            <p class="subtitle">Selamat datang di keluarga besar universitas kami!</p>
          </div>
          <div class="step-card-body">
            <div class="success-hero">
              <div class="success-icon-wrap">🎓</div>
              <h3 style="font-family:'Playfair Display',serif;font-size:2rem;color:var(--navy);margin-bottom:8px">
                Selamat Datang, Mahasiswa Baru!
              </h3>
              <p style="color:var(--text-muted);max-width:460px;margin:0 auto">
                Anda resmi menjadi bagian dari keluarga besar Universitas Nusantara. 
                Jadilah mahasiswa yang berprestasi dan berkarakter!
              </p>
            </div>

            <div class="welcome-card mb-4">
              <h5 style="font-weight:800;margin-bottom:4px">Ahmad Fauzi Ramadhan</h5>
              <div style="opacity:0.7;margin-bottom:16px">Teknik Informatika • Angkatan 2025</div>
              <div style="background:rgba(255,255,255,0.1);border-radius:12px;padding:16px 20px;display:inline-block">
                <div style="font-size:0.8rem;opacity:0.7;margin-bottom:4px">NIM Mahasiswa</div>
                <div style="font-size:1.6rem;font-weight:800;letter-spacing:0.1em">2025-TI-1847</div>
              </div>
              <div class="mt-3" style="font-size:0.85rem;opacity:0.6">🏆 Peringkat 23 dari 1.247 peserta</div>
            </div>

            <h6 style="font-weight:700;color:var(--navy);margin-bottom:16px">Jadwal Orientasi Mahasiswa Baru</h6>
            <?php
            $ospek = [
              ['Hari 1 – Pembukaan OSPEK', '4 Agustus 2025, 07:00 WIB', 'Auditorium Utama', 'Upacara pembukaan, pengenalan pimpinan universitas', 'bi-flag', '#1a6fc4'],
              ['Hari 2 – Pengenalan Fakultas', '5 Agustus 2025, 08:00 WIB', 'Gedung Masing-masing Fakultas', 'Orientasi program studi, dosen, dan fasilitas', 'bi-building', '#8a5a00'],
              ['Hari 3 – Pengenalan UKM', '6 Agustus 2025, 08:00 WIB', 'Lapangan Kampus', 'Pameran unit kegiatan mahasiswa', 'bi-people', '#2e7d32'],
              ['Hari 4 – Campus Tour & Penutupan', '7 Agustus 2025, 08:00 WIB', 'Seluruh Area Kampus', 'Tur kampus dan malam keakraban', 'bi-map', '#5e35b1'],
            ];
            foreach($ospek as $o): ?>
            <div style="display:flex;gap:16px;margin-bottom:20px;padding:20px;background:var(--gray-soft);border-radius:16px;border:1px solid rgba(11,31,74,0.06)">
              <div style="width:44px;height:44px;border-radius:12px;background:<?=$o[5]?>18;color:<?=$o[5]?>;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0">
                <i class="bi <?=$o[4]?>"></i>
              </div>
              <div>
                <div style="font-weight:700;color:var(--navy);font-size:0.92rem;margin-bottom:4px"><?=$o[0]?></div>
                <div style="font-size:0.8rem;color:var(--blue);margin-bottom:2px"><i class="bi bi-clock me-1"></i><?=$o[1]?></div>
                <div style="font-size:0.8rem;color:var(--text-muted);margin-bottom:4px"><i class="bi bi-geo-alt me-1"></i><?=$o[2]?></div>
                <div style="font-size:0.82rem;color:var(--text-dark)"><?=$o[3]?></div>
              </div>
            </div>
            <?php endforeach; ?>

            <div class="info-box mt-2">
              <i class="bi bi-check-circle-fill"></i>
              <strong>Proses pendaftaran selesai!</strong> Kartu mahasiswa dan akun portal akademik akan aktif pada <strong>1 Agustus 2025</strong>.
            </div>

            <div class="d-flex justify-content-between mt-4">
              <a href="?step=5" class="btn-outline-custom"><i class="bi bi-arrow-left"></i> Kembali</a>
              <div class="d-flex gap-3">
                <a href="#" class="btn-outline-custom"><i class="bi bi-download"></i> Unduh Kartu Mhs</a>
                <a href="?step=1" class="btn-primary-custom" onclick="<?php session_destroy(); ?>"><i class="bi bi-house"></i> Kembali ke Awal</a>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /col-lg-8 -->

      <!-- SIDEBAR -->
      <div class="col-lg-4">
        <!-- Timeline -->
        <div class="sidebar-card">
          <h6><i class="bi bi-list-check me-2" style="color:var(--blue)"></i>Tahapan Pendaftaran</h6>
          <div class="timeline-mini">
            <?php foreach($steps as $num => $step): ?>
            <div class="timeline-item <?=($num < $current_step ? 'done' : '')?>">
              <div class="t-title"><?=$step['title']?></div>
              <div class="t-date"><?=$step['en']?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Important Dates -->
        <div class="sidebar-card">
          <h6><i class="bi bi-calendar3 me-2" style="color:var(--gold)"></i>Tanggal Penting</h6>
          <?php
          $dates = [
            ['1 Jun – 10 Jul 2025', 'Masa Pendaftaran', '#1a6fc4'],
            ['11 – 20 Jul 2025', 'Seleksi Berkas', '#8a5a00'],
            ['14 – 21 Jul 2025', 'Ujian Masuk', '#1a6fc4'],
            ['25 Jul 2025', 'Pengumuman Hasil', '#2e7d32'],
            ['26 – 28 Jul 2025', 'Daftar Ulang', '#8a5a00'],
            ['4 – 7 Agt 2025', 'OSPEK', '#5e35b1'],
          ];
          foreach($dates as $d): ?>
          <div style="display:flex;gap:12px;margin-bottom:14px;align-items:flex-start">
            <div style="width:8px;height:8px;border-radius:50%;background:<?=$d[2]?>;margin-top:5px;flex-shrink:0"></div>
            <div>
              <div style="font-size:0.8rem;font-weight:700;color:var(--navy)"><?=$d[0]?></div>
              <div style="font-size:0.78rem;color:var(--text-muted)"><?=$d[1]?></div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <!-- Contact -->
        <div class="sidebar-card">
          <h6><i class="bi bi-headset me-2" style="color:var(--navy)"></i>Butuh Bantuan?</h6>
          <p style="font-size:0.85rem;color:var(--text-muted);margin-bottom:16px">Tim kami siap membantu Anda selama proses pendaftaran</p>
          <div class="d-flex flex-column gap-2">
            <a href="https://wa.me/6281234567890" style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:#e8f5e9;border-radius:12px;text-decoration:none;color:var(--navy);font-size:0.85rem;font-weight:600">
              <i class="bi bi-whatsapp" style="color:#25d366;font-size:1.2rem"></i> WhatsApp: 0812-3456-7890
            </a>
            <a href="mailto:pmb@universitas.ac.id" style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:#e8f4fd;border-radius:12px;text-decoration:none;color:var(--navy);font-size:0.85rem;font-weight:600">
              <i class="bi bi-envelope" style="color:var(--blue);font-size:1.2rem"></i> pmb@universitas.ac.id
            </a>
            <div style="display:flex;align-items:center;gap:10px;padding:12px 16px;background:var(--gray-soft);border-radius:12px;font-size:0.85rem;color:var(--text-muted)">
              <i class="bi bi-clock" style="font-size:1.2rem"></i> Sen–Jum, 08:00–16:00 WIB
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="container">
    <div style="font-size:1.5rem;margin-bottom:12px">🎓</div>
    <div style="color:rgba(255,255,255,0.8);font-weight:600;margin-bottom:6px">Universitas Nusantara</div>
    <div>Sistem Penerimaan Mahasiswa Baru 2025/2026</div>
    <div class="mt-2">
      <a href="#">Panduan Pendaftaran</a> &nbsp;·&nbsp;
      <a href="#">FAQ</a> &nbsp;·&nbsp;
      <a href="#">Kebijakan Privasi</a>
    </div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function selectExam(el) {
  document.querySelectorAll('.exam-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
}
function selectPayment(el) {
  document.querySelectorAll('.payment-method').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
}

// Upload zone label update
document.querySelectorAll('.upload-zone input[type=file]').forEach(input => {
  input.addEventListener('change', function() {
    const zone = this.closest('.upload-zone');
    if (this.files.length > 0) {
      zone.style.borderColor = 'var(--blue)';
      zone.style.background = 'rgba(26,111,196,0.04)';
      zone.querySelector('div:not(.upload-icon)').textContent = '✓ ' + this.files[0].name;
    }
  });
});
</script>
</body>
</html>
