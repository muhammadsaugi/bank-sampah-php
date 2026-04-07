<?php
// ─── admin/kegiatan/edit.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/KegiatanModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$id  = Request::int('id','get');
$row = $id > 0 ? kegiatan_findById($id) : false;
if (!$row) Response::abort(404,'Kegiatan tidak ditemukan.');
$pageTitle='Edit Kegiatan'; $sidebarActive='kegiatan';
$breadcrumb=[['label'=>'Kegiatan','url'=>BASE_URL.'/admin/kegiatan/index.php'],['label'=>'Edit','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Edit Kegiatan</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card">
    <form method="POST" action="proses.php" enctype="multipart/form-data">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="edit"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
        <div class="form-card-body">
            <div class="form-group"><label class="form-label">Judul <span class="required">*</span></label><input type="text" name="judul" class="form-control" required maxlength="255" value="<?= e($row['judul']) ?>"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Jenis <span class="required">*</span></label><input type="text" name="jenis_kegiatan" class="form-control" required maxlength="100" value="<?= e($row['jenis_kegiatan']) ?>"></div>
                <div class="form-group"><label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <?php foreach(['akan_datang'=>'Akan Datang','berlangsung'=>'Berlangsung','selesai'=>'Selesai'] as $v=>$l): ?>
                        <option value="<?= $v ?>" <?= $row['status']===$v?'selected':'' ?>><?= $l ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Tanggal Mulai <span class="required">*</span></label><input type="date" name="tanggal" class="form-control" required value="<?= e($row['tanggal']) ?>"></div>
                <div class="form-group"><label class="form-label">Tanggal Selesai</label><input type="date" name="tanggal_selesai" class="form-control" value="<?= e($row['tanggal_selesai']) ?>"></div>
            </div>
            <div class="form-group"><label class="form-label">Lokasi</label><input type="text" name="lokasi" class="form-control" maxlength="255" value="<?= e($row['lokasi']) ?>"></div>
            <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="deskripsi" class="form-control" rows="4"><?= e($row['deskripsi']) ?></textarea></div>
            <div class="form-group">
                <label class="form-label">Foto Baru (kosongkan jika tidak diubah)</label>
                <?php if ($row['foto']): ?><img src="<?= BASE_URL ?>/uploads/kegiatan/<?= e($row['foto']) ?>" style="max-height:120px;border-radius:var(--radius);margin-bottom:.5rem;display:block;"><?php endif ?>
                <input type="file" name="foto" class="form-control" accept=".jpg,.jpeg,.png,.webp" onchange="previewUpload(this,'preview-foto')">
                <img id="preview-foto" class="upload-preview-img" style="display:none;">
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
