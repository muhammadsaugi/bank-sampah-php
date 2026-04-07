<?php
// ─── admin/kelembagaan/struktur/tambah.php ───
require_once dirname(__DIR__, 3) . '/config/app.php';
require_once dirname(__DIR__, 3) . '/config/session.php';
require_once dirname(__DIR__, 3) . '/core/Auth.php';
require_once dirname(__DIR__, 3) . '/core/Helper.php';
require_once dirname(__DIR__, 3) . '/core/CSRF.php';
require_once dirname(__DIR__, 3) . '/core/Response.php';

Auth::cekSession(); Auth::cekRole(['super_admin']);
$pageTitle='Upload Struktur Organisasi'; $sidebarActive='struktur';
$breadcrumb=[['label'=>'Struktur Organisasi','url'=>BASE_URL.'/admin/kelembagaan/struktur/index.php'],['label'=>'Upload','url'=>'']];
require_once dirname(__DIR__, 3) . '/templates/admin/header.php';
require_once dirname(__DIR__, 3) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 3) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Upload Struktur Organisasi</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 3) . '/templates/admin/flash.php' ?>
<div class="form-card" style="max-width:560px;">
    <form method="POST" action="proses.php" enctype="multipart/form-data">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="tambah">
        <div class="form-card-body">
            <div class="form-group"><label class="form-label">Judul <span class="required">*</span></label><input type="text" name="judul" class="form-control" required maxlength="200" placeholder="Struktur Organisasi BSI 2024-2026"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Periode</label><input type="text" name="periode" class="form-control" maxlength="30" placeholder="2024-2026"></div>
                <div class="form-group"><label class="form-label">Urutan Tampil</label><input type="number" name="urutan" class="form-control" min="0" max="99" value="0"></div>
            </div>
            <div class="form-group"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"></textarea></div>
            <div class="form-group">
                <label class="form-label">File Gambar Struktur <span class="required">*</span></label>
                <input type="file"
                       name="gambar"
                       class="form-control"
                       accept=".jpg,.jpeg,.png,.webp"
                       required
                       data-preview-target="preview-str">
                <img id="preview-str" class="upload-preview-img" style="display:none;">
                <p class="form-hint">Maksimal 2MB. Disarankan format landscape (JPG/PNG).</p>
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 3) . '/templates/admin/footer.php' ?>
