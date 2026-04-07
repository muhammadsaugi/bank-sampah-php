<?php
// ─── admin/kelembagaan/struktur/edit.php ───
require_once dirname(__DIR__, 3) . '/config/app.php';
require_once dirname(__DIR__, 3) . '/config/session.php';
require_once dirname(__DIR__, 3) . '/config/database.php';
require_once dirname(__DIR__, 3) . '/core/Auth.php';
require_once dirname(__DIR__, 3) . '/core/Helper.php';
require_once dirname(__DIR__, 3) . '/core/CSRF.php';
require_once dirname(__DIR__, 3) . '/core/Request.php';
require_once dirname(__DIR__, 3) . '/core/Response.php';
require_once dirname(__DIR__, 3) . '/models/StrukturModel.php';

Auth::cekSession(); Auth::cekRole(['super_admin']);
$id  = Request::int('id','get');
$row = $id > 0 ? struktur_findById($id) : false;
if (!$row) Response::abort(404,'Struktur tidak ditemukan.');
$pageTitle='Edit Struktur'; $sidebarActive='struktur';
$breadcrumb=[['label'=>'Struktur Organisasi','url'=>BASE_URL.'/admin/kelembagaan/struktur/index.php'],['label'=>'Edit','url'=>'']];
require_once dirname(__DIR__, 3) . '/templates/admin/header.php';
require_once dirname(__DIR__, 3) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 3) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Edit Struktur Organisasi</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 3) . '/templates/admin/flash.php' ?>
<div class="form-card" style="max-width:560px;">
    <form method="POST" action="proses.php" enctype="multipart/form-data">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="edit"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
        <div class="form-card-body">
            <div class="form-group"><label class="form-label">Judul <span class="required">*</span></label><input type="text" name="judul" class="form-control" required maxlength="200" value="<?= e($row['judul']) ?>"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Periode</label><input type="text" name="periode" class="form-control" maxlength="30" value="<?= e($row['periode']) ?>"></div>
                <div class="form-group"><label class="form-label">Urutan</label><input type="number" name="urutan" class="form-control" min="0" max="99" value="<?= (int)$row['urutan'] ?>"></div>
            </div>
            <div class="form-group"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"><?= e($row['keterangan']) ?></textarea></div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="aktif" class="form-control">
                    <option value="1" <?= $row['aktif']?'selected':'' ?>>Aktif</option>
                    <option value="0" <?= !$row['aktif']?'selected':'' ?>>Nonaktif</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Gambar Baru (kosongkan jika tidak diubah)</label>
                <img src="<?= BASE_URL ?>/uploads/struktur/<?= e($row['gambar']) ?>" style="max-height:100px;border-radius:var(--radius);margin-bottom:.5rem;display:block;">
                <input type="file" name="gambar" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="previewUpload(this,'preview-str')">
                <img id="preview-str" class="upload-preview-img" style="display:none;">
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 3) . '/templates/admin/footer.php' ?>
