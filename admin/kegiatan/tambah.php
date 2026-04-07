<?php
// ─── admin/kegiatan/tambah.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$pageTitle='Tambah Kegiatan'; $sidebarActive='kegiatan';
$breadcrumb=[['label'=>'Kegiatan','url'=>BASE_URL.'/admin/kegiatan/index.php'],['label'=>'Tambah','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Tambah Kegiatan</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card">
    <form method="POST" action="proses.php" enctype="multipart/form-data">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="tambah">
        <div class="form-card-body">
            <div class="form-group"><label class="form-label">Judul Kegiatan <span class="required">*</span></label><input type="text" name="judul" class="form-control" required maxlength="255"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Jenis Kegiatan <span class="required">*</span></label><input type="text" name="jenis_kegiatan" class="form-control" required maxlength="100" placeholder="Sosialisasi, Pelatihan, Pameran..."></div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="akan_datang">Akan Datang</option>
                        <option value="berlangsung">Berlangsung</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Tanggal Mulai <span class="required">*</span></label><input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>"></div>
                <div class="form-group"><label class="form-label">Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control"></div>
            </div>
            <div class="form-group"><label class="form-label">Lokasi</label><input type="text" name="lokasi" class="form-control" maxlength="255"></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="4"></textarea></div>
            <div class="form-group">
                <label class="form-label">Foto Kegiatan</label>
                <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="previewUpload(this,'preview-foto')">
                <img id="preview-foto" class="upload-preview-img" style="display:none;">
                <p class="form-hint">Opsional. Maksimal 2MB. Format: JPG, PNG, WebP</p>
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
