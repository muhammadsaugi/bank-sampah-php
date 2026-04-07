<?php
// ─── templates/public/header.php ───
// Partial header halaman publik: DOCTYPE, meta, CSS, navbar.
// Variabel yang dibutuhkan: $pageTitle (set di halaman pemanggil)

defined('BASE_URL') || require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';

$pageTitle ??= APP_NAME;
$navActive ??= '';

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= e(APP_NAME) ?> — Sistem Informasi Bank Sampah Induk Kota Mojokerto">
    <meta name="theme-color" content="#16a34a">
    <title><?= e($pageTitle) ?> — <?= e(APP_NAME) ?></title>

    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/reset.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/public.css">

    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>♻️</text></svg>">
</head>
<body>

<!-- Mobile nav toggle (CSS checkbox trick) -->
<input type="checkbox" id="nav-toggle" class="nav-toggle-checkbox" hidden>

<nav class="navbar">
    <div class="navbar-inner">
        <!-- Brand -->
        <a href="<?= BASE_URL ?>/public/beranda.php" class="navbar-brand">
            <div class="navbar-logo-icon">♻</div>
            <div class="navbar-logo-text">
                BSI Mojokerto
                <span class="navbar-logo-sub">Bank Sampah Induk</span>
            </div>
        </a>

        <!-- Hamburger label (mobile) -->
        <label for="nav-toggle" class="nav-hamburger" aria-label="Toggle navigasi">
            <span></span><span></span><span></span>
        </label>

        <!-- Menu -->
        <div class="navbar-menu">
            <a href="<?= BASE_URL ?>/public/beranda.php"
               class="nav-link <?= $navActive === 'beranda' ? 'active' : '' ?>">Beranda</a>

            <!-- Dropdown Kelembagaan -->
            <div class="dropdown">
                <a href="#" class="nav-link <?= in_array($navActive, ['kelembagaan','visi-misi','struktur']) ? 'active' : '' ?>">
                    Kelembagaan ▾
                </a>
                <div class="dropdown-menu">
                    <a href="<?= BASE_URL ?>/public/kelembagaan.php">Profil Lembaga</a>
                    <a href="<?= BASE_URL ?>/public/visi-misi.php">Visi &amp; Misi</a>
                    <a href="<?= BASE_URL ?>/public/struktur-organisasi.php">Struktur Organisasi</a>
                </div>
            </div>

            <a href="<?= BASE_URL ?>/public/data-bank-sampah.php"
               class="nav-link <?= $navActive === 'bank-sampah' ? 'active' : '' ?>">Data Bank Sampah</a>

            <a href="<?= BASE_URL ?>/public/data-sampah.php"
               class="nav-link <?= $navActive === 'data-sampah' ? 'active' : '' ?>">Data Sampah</a>

            <a href="<?= BASE_URL ?>/public/rekapitulasi.php"
               class="nav-link <?= $navActive === 'rekap' ? 'active' : '' ?>">Rekapitulasi</a>

            <a href="<?= BASE_URL ?>/public/kegiatan.php"
               class="nav-link <?= $navActive === 'kegiatan' ? 'active' : '' ?>">Kegiatan</a>

            <a href="<?= BASE_URL ?>/public/berita.php"
               class="nav-link <?= $navActive === 'berita' ? 'active' : '' ?>">Berita</a>

            <a href="<?= BASE_URL ?>/public/galeri.php"
               class="nav-link <?= $navActive === 'galeri' ? 'active' : '' ?>">Galeri</a>

            <a href="<?= BASE_URL ?>/auth/login.php" class="navbar-login-btn">Login Admin</a>
        </div>
    </div>
</nav>
