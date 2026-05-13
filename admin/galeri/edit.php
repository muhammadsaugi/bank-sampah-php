<?php
// ─── admin/galeri/edit.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/GaleriModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);

$id = Request::int('id', 'get');
$row = $id > 0 ? galeri_findById($id) : false;

if (!$row) {
    Response::abort(404, 'Data galeri tidak ditemukan.');
}

$kategoriList = galeri_getKategoriList();
$pageTitle = 'Edit Foto Galeri'; 
$sidebarActive = 'galeri';
$breadcrumb = [
    ['label' => 'Galeri', 'url' => BASE_URL . '/admin/galeri/index.php'],
    ['label' => 'Edit', 'url' => '']
];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div><h1 class="page-title">Edit Foto Galeri</h1></div>
    <a href="index.php" class="btn btn-outline">← Kembali</a>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<div class="form-card" style="max-width:560px;">
    <form method="POST" action="proses.php" enctype="multipart/form-data">
        <?= CSRF::input() ?>
        <input type="hidden" name="aksi" value="edit">
        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

        <div class="form-body">
            <div class="form-group">
                <label class="form-label">Judul Foto <span class="required">*</span></label>
                <input type="text" name="judul" class="form-control" required value="<?= e($row['judul']) ?>" maxlength="200">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <select name="kategori" class="form-control">
                        <?php foreach ($kategoriList as $k): ?>
                            <option value="<?= e($k) ?>" <?= $row['kategori'] === $k ? 'selected' : '' ?>><?= e($k) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="<?= e($row['tanggal']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="2"><?= e($row['deskripsi']) ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Ganti Foto (Kosongkan jika tidak diubah)</label>
                <div style="margin-bottom: 1rem;">
                    <small class="text-muted">Foto saat ini:</small><br>
                    <img src="<?= BASE_URL ?>/uploads/galeri/<?= e($row['gambar']) ?>" style="height: 100px; border-radius: 8px; margin-top: 5px; border: 1px solid #ddd;">
                </div>
                <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="previewUpload(this,'preview-foto')">
                <img id="preview-foto" class="upload-preview-img" style="display:none; margin-top: 10px;">
                <p class="form-hint">Maksimal 2MB. Format: JPG, PNG, WebP</p>
            </div>
        </div>

        <div class="form-card-footer">
            <a href="index.php" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>