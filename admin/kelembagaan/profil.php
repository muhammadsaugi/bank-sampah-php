<?php
// ─── admin/kelembagaan/profil.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/ProfilModel.php';

Auth::cekSession();
Auth::cekRole(['super_admin']);

$profil        = profil_get();
$pageTitle     = 'Profil Kelembagaan';
$sidebarActive = 'kelembagaan';
$breadcrumb    = [['label' => 'Profil Kelembagaan', 'url' => '']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Profil Kelembagaan</h1>
        <p class="page-subtitle">Informasi resmi Bank Sampah Induk yang tampil di halaman publik</p>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<form method="POST" action="proses-profil.php" enctype="multipart/form-data">
    <?= CSRF::input() ?>

    <!-- Informasi Dasar -->
    <div class="form-card" style="margin-bottom:1.25rem;">
        <div class="form-card-header"><span class="form-card-title">🏛️ Informasi Dasar</span></div>
        <div class="form-card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Lembaga <span class="required">*</span></label>
                    <input type="text" name="nama_lembaga" class="form-control" required maxlength="200"
                           value="<?= e($profil['nama_lembaga']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Tagline</label>
                    <input type="text" name="tagline" class="form-control" maxlength="255"
                           value="<?= e($profil['tagline']) ?>"
                           placeholder="Slogan singkat lembaga...">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat</label>
                <textarea name="deskripsi" class="form-control" rows="4"
                          placeholder="Deskripsi umum tentang lembaga..."><?= e($profil['deskripsi']) ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tahun Berdiri</label>
                    <input type="number" name="tahun_berdiri" class="form-control"
                           min="1990" max="<?= date('Y') ?>"
                           value="<?= e($profil['tahun_berdiri']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Logo Lembaga</label>
                    <?php if (!empty($profil['logo'])): ?>
                    <div style="margin-bottom:.5rem;">
                        <img src="<?= BASE_URL ?>/uploads/profil/<?= e($profil['logo']) ?>"
                             style="max-height:60px;border-radius:var(--radius);border:1px solid var(--color-border);">
                    </div>
                    <?php endif ?>
                    <input type="file" name="logo" class="form-control"
                           accept=".jpg,.jpeg,.png,.webp"
                           onchange="previewUpload(this,'preview-logo')">
                    <img id="preview-logo" class="upload-preview-img" style="display:none;max-height:60px;">
                </div>
            </div>
        </div>
    </div>

    <!-- Visi & Misi -->
    <div class="form-card" style="margin-bottom:1.25rem;">
        <div class="form-card-header"><span class="form-card-title">🎯 Visi &amp; Misi</span></div>
        <div class="form-card-body">
            <div class="form-group">
                <label class="form-label">Visi</label>
                <textarea name="visi" class="form-control" rows="3"
                          placeholder="Pernyataan visi lembaga..."><?= e($profil['visi']) ?></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Misi</label>
                <textarea name="misi" class="form-control" rows="6"
                          placeholder="Tulis setiap poin misi di baris baru..."><?= e($profil['misi']) ?></textarea>
                <p class="form-hint">Tulis setiap poin misi di baris terpisah (Enter).</p>
            </div>
        </div>
    </div>

    <!-- Kontak -->
    <div class="form-card" style="margin-bottom:1.25rem;">
        <div class="form-card-header"><span class="form-card-title">📞 Kontak &amp; Lokasi</span></div>
        <div class="form-card-body">
            <div class="form-group">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2"><?= e($profil['alamat']) ?></textarea>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="telepon" class="form-control" maxlength="20"
                           value="<?= e($profil['telepon']) ?>" placeholder="(0321) xxxxxx">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" maxlength="100"
                           value="<?= e($profil['email']) ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Website</label>
                <input type="url" name="website" class="form-control" maxlength="255"
                       value="<?= e($profil['website']) ?>" placeholder="https://...">
            </div>
        </div>
    </div>

    <div style="display:flex;justify-content:flex-end;gap:.75rem;">
        <button type="submit" class="btn btn-primary">💾 Simpan Profil Kelembagaan</button>
    </div>
</form>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
