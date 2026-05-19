<?php require_once __DIR__ . '/../config/config.php'; if(!isAdmin()) redirect(APP_URL.'/admin/login.php'); redirect(APP_URL.'/admin/pendaftar.php?tahap=daftar_ulang');
