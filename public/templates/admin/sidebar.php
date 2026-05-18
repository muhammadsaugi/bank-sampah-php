<?php
// ─── templates/admin/sidebar.php ───
// Navigasi sidebar dengan filter per role.
//
// ATURAN HAK AKSES MENU:
//   super_admin       → semua menu tampil
//   admin_data        → Data Master (bank, jenis, nama sampah) + Rekap + Konten Publik
//   admin_operasional → Harga Sampah + Laporan Harian + Laporan Bulanan
//
// Untuk mengubah hak akses menu:
//   - Cari bagian menu yang ingin diubah
//   - Ubah kondisi Auth::hasAnyRole([...]) sesuai kebutuhan

$sidebarActive ??= '';
$userName = Auth::getNama();
$userRole = Auth::getRole();
$initials = inisialNama($userName);
?>
<aside class="sidebar" id="sidebar">

    <!-- Brand -->
    <a href="<?= BASE_URL ?>/admin/dashboard.php" class="sidebar-brand">
        <div class="sidebar-brand-icon">♻</div>
        <div class="sidebar-brand-text">
            <div class="sidebar-brand-name">BSI Mojokerto</div>
            <div class="sidebar-brand-sub">Panel Admin</div>
        </div>
    </a>

    <nav class="sidebar-nav">

        <!-- ══ DASHBOARD — semua role ══ -->
        <a href="<?= BASE_URL ?>/admin/dashboard.php"
           class="sidebar-link <?= $sidebarActive === 'dashboard' ? 'active' : '' ?>">
            <span class="sidebar-icon">📊</span>
            <span class="sidebar-label">Dashboard</span>
        </a>

        <!-- ══ DATA MASTER — hanya admin_data dan super_admin ══ -->
        <?php if (Auth::hasAnyRole(['admin_data', 'super_admin'])): ?>
        <div class="sidebar-group-label">Data Master</div>

        <a href="<?= BASE_URL ?>/admin/bank-sampah/index.php"
           class="sidebar-link <?= $sidebarActive === 'bank-sampah' ? 'active' : '' ?>">
            <span class="sidebar-icon">🏦</span>
            <span class="sidebar-label">Bank Sampah</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/jenis-sampah/index.php"
           class="sidebar-link <?= $sidebarActive === 'jenis-sampah' ? 'active' : '' ?>">
            <span class="sidebar-icon">🗂️</span>
            <span class="sidebar-label">Jenis Sampah</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/nama-sampah/index.php"
           class="sidebar-link <?= $sidebarActive === 'nama-sampah' ? 'active' : '' ?>">
            <span class="sidebar-icon">🏷️</span>
            <span class="sidebar-label">Nama Sampah</span>
        </a>
        <?php endif ?>

        <!-- ══ HARGA SAMPAH — hanya admin_operasional dan super_admin ══
             [DIUBAH] admin_data dihapus dari sini                         -->
        <?php if (Auth::hasAnyRole(['admin_operasional', 'super_admin'])): ?>
        <div class="sidebar-group-label">Harga</div>

        <a href="<?= BASE_URL ?>/admin/harga-sampah/index.php"
           class="sidebar-link <?= $sidebarActive === 'harga-sampah' ? 'active' : '' ?>">
            <span class="sidebar-icon">💰</span>
            <span class="sidebar-label">Harga Sampah</span>
        </a>
        <?php endif ?>

        <!-- ══ TRANSAKSI ══ -->
        <div class="sidebar-group-label">Transaksi</div>

        <!-- Rekap Sampah — hanya admin_data dan super_admin
             [DIUBAH] admin_operasional dihapus dari sini -->
        <?php if (Auth::hasAnyRole(['admin_data', 'super_admin'])): ?>
        <a href="<?= BASE_URL ?>/admin/rekap-sampah/index.php"
           class="sidebar-link <?= $sidebarActive === 'rekap-sampah' ? 'active' : '' ?>">
            <span class="sidebar-icon">📋</span>
            <span class="sidebar-label">Rekap Sampah</span>
        </a>
        <?php endif ?>

        <!-- Import CSV — hanya admin_operasional dan super_admin (tetap sama) -->
        <?php if (Auth::hasAnyRole(['admin_operasional', 'super_admin'])): ?>
        <a href="<?= BASE_URL ?>/admin/rekap-sampah/import.php"
           class="sidebar-link <?= $sidebarActive === 'rekap-import' ? 'active' : '' ?>">
            <span class="sidebar-icon">📥</span>
            <span class="sidebar-label">Import CSV</span>
        </a>
        <?php endif ?>

        <!-- Laporan Harian — hanya admin_operasional dan super_admin
             [DIUBAH] admin_data dihapus dari sini -->
        <?php if (Auth::hasAnyRole(['admin_operasional', 'super_admin'])): ?>
        <a href="<?= BASE_URL ?>/admin/laporan/harian.php"
           class="sidebar-link <?= $sidebarActive === 'laporan-harian' ? 'active' : '' ?>">
            <span class="sidebar-icon">📅</span>
            <span class="sidebar-label">Laporan Harian</span>
        </a>

        <!-- Laporan Bulanan — hanya admin_operasional dan super_admin
             [DIUBAH] sudah sama seperti sebelumnya, tidak ada perubahan -->
        <a href="<?= BASE_URL ?>/admin/laporan/bulanan.php"
           class="sidebar-link <?= $sidebarActive === 'laporan-bulanan' ? 'active' : '' ?>">
            <span class="sidebar-icon">📈</span>
            <span class="sidebar-label">Laporan Bulanan</span>
        </a>
        <?php endif ?>

        <!-- ══ KONTEN PUBLIK — admin_data dan super_admin ══ -->
        <?php if (Auth::hasAnyRole(['admin_data', 'super_admin'])): ?>
        <div class="sidebar-group-label">Konten Publik</div>

        <a href="<?= BASE_URL ?>/admin/kegiatan/index.php"
           class="sidebar-link <?= $sidebarActive === 'kegiatan' ? 'active' : '' ?>">
            <span class="sidebar-icon">🎯</span>
            <span class="sidebar-label">Kegiatan</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/berita/index.php"
           class="sidebar-link <?= $sidebarActive === 'berita' ? 'active' : '' ?>">
            <span class="sidebar-icon">📰</span>
            <span class="sidebar-label">Berita</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/galeri/index.php"
           class="sidebar-link <?= $sidebarActive === 'galeri' ? 'active' : '' ?>">
            <span class="sidebar-icon">🖼️</span>
            <span class="sidebar-label">Galeri</span>
        </a>
        <?php endif ?>

        <!-- ══ PENGATURAN — hanya super_admin ══ -->
        <?php if (Auth::hasRole('super_admin')): ?>
        <div class="sidebar-group-label">Pengaturan</div>

        <a href="<?= BASE_URL ?>/admin/kelembagaan/profil.php"
           class="sidebar-link <?= $sidebarActive === 'kelembagaan' ? 'active' : '' ?>">
            <span class="sidebar-icon">🏛️</span>
            <span class="sidebar-label">Profil Lembaga</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/kelembagaan/struktur/index.php"
           class="sidebar-link <?= $sidebarActive === 'struktur' ? 'active' : '' ?>">
            <span class="sidebar-icon">🌿</span>
            <span class="sidebar-label">Struktur Organisasi</span>
        </a>

        <a href="<?= BASE_URL ?>/admin/users/index.php"
           class="sidebar-link <?= $sidebarActive === 'users' ? 'active' : '' ?>">
            <span class="sidebar-icon">👥</span>
            <span class="sidebar-label">Manajemen User</span>
        </a>
        <?php endif ?>

    </nav>

    <!-- Info user di bawah sidebar -->
    <div class="sidebar-user">
        <div class="sidebar-avatar"><?= e($initials) ?></div>
        <div class="sidebar-user-info">
            <div class="sidebar-user-name"><?= e($userName) ?></div>
            <div class="sidebar-user-role"><?= e(str_replace('_', ' ', ucfirst($userRole))) ?></div>
        </div>
    </div>

</aside>
