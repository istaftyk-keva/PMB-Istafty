-- Database PMB Sederhana
CREATE DATABASE IF NOT EXISTS pmb_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE pmb_db;

CREATE TABLE IF NOT EXISTS pendaftar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    no_pendaftaran VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    no_hp VARCHAR(15) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    asal_sekolah VARCHAR(150) NOT NULL,
    prodi ENUM('Teknik Informatika','Sistem Informasi','Manajemen','Akuntansi','Hukum','Kedokteran') NOT NULL,
    jalur ENUM('Reguler','Prestasi','Beasiswa') DEFAULT 'Reguler',
    password VARCHAR(255) NOT NULL,
    tahap ENUM('pendaftaran','seleksi','ujian','pengumuman','daftar_ulang','ospek','selesai','ditolak') DEFAULT 'pendaftaran',
    status_seleksi ENUM('menunggu','lulus','tidak_lulus') DEFAULT 'menunggu',
    nilai_ujian DECIMAL(5,2) DEFAULT NULL,
    status_pengumuman ENUM('menunggu','lulus','tidak_lulus') DEFAULT 'menunggu',
    status_bayar ENUM('belum','proses','lunas') DEFAULT 'belum',
    bukti_bayar VARCHAR(255) DEFAULT NULL,
    foto VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- Admin default: password = admin123
INSERT INTO admin (username, password, nama) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator PMB');

-- Contoh data pendaftar
INSERT INTO pendaftar (no_pendaftaran, nama, email, no_hp, tanggal_lahir, asal_sekolah, prodi, password, tahap, status_seleksi, nilai_ujian, status_pengumuman, status_bayar) VALUES
('PMB2025001', 'Budi Santoso', 'budi@email.com', '08123456789', '2005-03-15', 'SMA Negeri 1 Cirebon', 'Teknik Informatika', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'daftar_ulang', 'lulus', 85.50, 'lulus', 'belum'),
('PMB2025002', 'Siti Rahayu', 'siti@email.com', '08234567890', '2005-07-20', 'SMA Negeri 2 Bandung', 'Manajemen', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ujian', 'lulus', NULL, 'menunggu', 'belum'),
('PMB2025003', 'Ahmad Fauzi', 'ahmad@email.com', '08345678901', '2004-11-08', 'SMA Negeri 3 Jakarta', 'Sistem Informasi', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'seleksi', 'menunggu', NULL, 'menunggu', 'belum');
