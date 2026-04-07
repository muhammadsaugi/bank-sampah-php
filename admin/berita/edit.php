<?php
// ─── admin/berita/edit.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/BeritaModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$id = Request::int('id','get');
$row = $id > 0 ? berita_findById($id) : false;
if (!$row) Response::abort(404,'Berita tidak ditemukan.');
$pageTitle='Edit Berita'; $sidebarActive='berita';
$breadcrumb=[['label'=>'Berita','url'=>BASE_URL.'/admin/berita/index.php'],['label'=>'Edit','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Edit Berita</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card">
    <form method="POST" action="proses.php" enctype="multipart/form-data">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="edit"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
        <div class="form-card-body">
            <div class="form-group"><label class="form-label">Judul <span class="required">*</span></label><input type="text" name="judul" class="form-control" required maxlength="255" value="<?= e($row['judul']) ?>"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Kategori</label><input type="text" name="kategori" class="form-control" maxlength="100" value="<?= e($row['kategori']) ?>"></div>
                <div class="form-group"><label class="form-label">Tags</label><input type="text" name="tags" class="form-control" maxlength="255" value="<?= e($row['tags']) ?>"></div>
            </div>
            <div class="form-group"><label class="form-label">Isi Berita</label><textarea name="isi" class="form-control" rows="10"><?= e($row['isi']) ?></textarea></div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Foto Cover Baru</label>
                    <?php if ($row['foto']): ?><img src="<?= BASE_URL ?>/uploads/berita/<?= e($row['foto']) ?>" style="max-height:120px;border-radius:var(--radius);margin-bottom:.5rem;display:block;"><?php endif ?>
                    <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="previewUpload(this,'preview-foto')">
                    <img id="preview-foto" class="upload-preview-img" style="display:none;">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft" <?= $row['status']==='draft'?'selected':'' ?>>Draft</option>
                        <option value="publish" <?= $row['status']==='publish'?'selected':'' ?>>Publish</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
