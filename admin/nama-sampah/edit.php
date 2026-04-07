<?php
// ─── admin/nama-sampah/edit.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/NamaSampahModel.php';
require_once dirname(__DIR__, 2) . '/models/JenisSampahModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$id = Request::int('id','get');
$row = $id > 0 ? nama_findById($id) : false;
if (!$row) Response::abort(404,'Item tidak ditemukan.');
$jenisList = jenis_getAll();
$pageTitle='Edit Nama Sampah'; $sidebarActive='nama-sampah';
$breadcrumb=[['label'=>'Nama Sampah','url'=>BASE_URL.'/admin/nama-sampah/index.php'],['label'=>'Edit','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Edit Item Sampah</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card" style="max-width:560px;">
    <form method="POST" action="proses.php">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="edit"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
        <div class="form-card-body">
            <div class="form-group">
                <label class="form-label">Jenis Sampah <span class="required">*</span></label>
                <select name="id_jenis" class="form-control" required>
                    <?php foreach ($jenisList as $j): ?>
                    <option value="<?= (int)$j['id'] ?>" <?= $j['id']==$row['id_jenis']?'selected':'' ?>><?= e($j['nama']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Nama Item <span class="required">*</span></label><input type="text" name="nama" class="form-control" required maxlength="150" value="<?= e($row['nama']) ?>"></div>
                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <select name="satuan" class="form-control">
                        <?php foreach(['Kg','Unit','Liter','Buah'] as $s): ?><option value="<?= $s ?>" <?= $row['satuan']===$s?'selected':'' ?>><?= $s ?></option><?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group"><label class="form-label">Keterangan</label><textarea name="keterangan" class="form-control" rows="2"><?= e($row['keterangan']) ?></textarea></div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
