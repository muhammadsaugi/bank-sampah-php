<?php
// ─── admin/galeri/tambah.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/models/GaleriModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$pageTitle='Upload Foto Galeri'; $sidebarActive='galeri';
$breadcrumb=[['label'=>'Galeri','url'=>BASE_URL.'/admin/galeri/index.php'],['label'=>'Upload','url'=>'']];
$kategoriList = galeri_getKategoriList();
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Upload Foto Galeri</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card" style="max-width:560px;">
    <form method="POST" action="proses.php" enctype="multipart/form-data">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="tambah">
        <div class="form-card-body">
            <div class="form-group"><label class="form-label">Judul Foto <span class="required">*</span></label><input type="text" name="judul" class="form-control" required maxlength="200"></div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-control">
                        <?php foreach ($kategoriList as $k): ?><option value="<?= e($k) ?>"><?= e($k) ?></option><?php endforeach ?>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Tanggal</label><input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>"></div>
            </div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="2"></textarea></div>
            <div class="form-group">
                <label class="form-label">File Foto <span class="required">*</span></label>
                <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp" required onchange="previewUpload(this,'preview-foto')">
                <img id="preview-foto" class="upload-preview-img" style="display:none;">
                <p class="form-hint">Maksimal 2MB. Format: JPG, PNG, WebP</p>
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">📤 Upload</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
