<?php
// ─── admin/berita/tambah.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$pageTitle='Tulis Berita'; $sidebarActive='berita';
$breadcrumb=[['label'=>'Berita','url'=>BASE_URL.'/admin/berita/index.php'],['label'=>'Tambah','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Tulis Berita Baru</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card">
    <form method="POST" action="proses.php" enctype="multipart/form-data">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="tambah">
        <div class="form-card-body">
            <div class="form-group"><label class="form-label">Judul Berita <span class="required">*</span></label><input type="text" name="judul" class="form-control" required maxlength="255" placeholder="Judul berita menarik..."></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Kategori</label><input type="text" name="kategori" class="form-control" maxlength="100" placeholder="Kelembagaan, Edukasi..."></div>
                <div class="form-group"><label class="form-label">Tags</label><input type="text" name="tags" class="form-control" maxlength="255" placeholder="tag1, tag2, tag3"></div>
            </div>
            <div class="form-group"><label class="form-label">Isi Berita</label><textarea name="isi" class="form-control" rows="10" placeholder="Tulis isi berita di sini. Mendukung tag HTML dasar: p, strong, em, ul, ol, li."></textarea></div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Foto Cover</label>
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="previewUpload(this,'preview-foto')">
                    <img id="preview-foto" class="upload-preview-img" style="display:none;">
                    <p class="form-hint">Opsional. Maks 2MB.</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft">Draft</option>
                        <option value="publish">Publish</option>
                    </select>
                    <p class="form-hint">Draft tidak tampil di publik.</p>
                </div>
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan Berita</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
