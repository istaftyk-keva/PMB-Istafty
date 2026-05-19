<?php
// Konfigurasi Database - SESUAIKAN INI
define('DB_HOST', 'localhost');
define('DB_NAME', 'pmb_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// Konfigurasi App - SESUAIKAN jika nama folder berbeda
define('APP_URL', 'http://localhost/PMB');

// Path upload (otomatis)
define('UPLOAD_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR);

date_default_timezone_set('Asia/Jakarta');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi database
function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        try {
            $pdo = new PDO(
                "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
                DB_USER, DB_PASS,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
            );
        } catch (PDOException $e) {
            die("<div style='font-family:sans-serif;padding:20px;background:#fee;border:1px solid red;margin:20px;border-radius:8px;'>
                <strong>Koneksi Database Gagal!</strong><br>
                Pastikan MySQL aktif dan setting di <code>config/config.php</code> sudah benar.<br>
                Error: " . $e->getMessage() . "
            </div>");
        }
    }
    return $pdo;
}

// Helper functions
function e($s): string {
    return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect($url): void {
    header("Location: $url");
    exit;
}

function flash($type, $msg): void {
    $_SESSION['flash'] = ['type' => $type, 'msg' => $msg];
}

function getFlash(): ?array {
    if (!empty($_SESSION['flash'])) {
        $f = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $f;
    }
    return null;
}

function isAdmin(): bool {
    return !empty($_SESSION['admin_id']);
}

function isPeserta(): bool {
    return !empty($_SESSION['peserta_id']);
}

function generateNoPendaftaran(): string {
    $count = db()->query("SELECT COUNT(*) FROM pendaftar")->fetchColumn();
    return 'PMB' . date('Y') . str_pad((int)$count + 1, 3, '0', STR_PAD_LEFT);
}

function badgeTahap($tahap): string {
    $map = [
        'pendaftaran'  => ['primary',   'Pendaftaran'],
        'seleksi'      => ['warning',   'Seleksi Berkas'],
        'ujian'        => ['info',      'Ujian Masuk'],
        'pengumuman'   => ['secondary', 'Pengumuman'],
        'daftar_ulang' => ['success',   'Daftar Ulang'],
        'ospek'        => ['dark',      'Ospek'],
        'selesai'      => ['success',   'Selesai'],
        'ditolak'      => ['danger',    'Ditolak'],
    ];
    [$cls, $lbl] = $map[$tahap] ?? ['secondary', ucfirst($tahap)];
    return "<span class='badge bg-{$cls}'>{$lbl}</span>";
}
