<?php
// ─── admin/nama-sampah/tambah.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/JenisSampahModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$jenisList = jenis_getAll();
$pageTitle='Tambah Nama Sampah'; $sidebarActive='nama-sampah';
$breadcrumb=[['label'=>'Nama Sampah','url'=>BASE_URL.'/admin/nama-sampah/index.php'],['label'=>'Tambah','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Tambah Item Sampah</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card" style="max-width:560px;">
    <form method="POST" action="proses.php">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="tambah">
        <div class="form-card-body">
            <div class="form-group">
                <label class="form-label">Jenis Sampah <span class="required">*</span></label>
                <select name="id_jenis" class="form-control" required>
                    <option value="">-- Pilih Jenis --</option>
                    <?php foreach ($jenisList as $j): ?>
                    <option value="<?= (int)$j['id'] ?>"><?= e($j['nama']) ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Item <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-control" required maxlength="150" placeholder="Botol PET Bening">
                </div>
                <div class="form-group">
                    <label class="form-label">Satuan</label>
                    <select name="satuan" class="form-control">
                        <option value="Kg">Kg</option>
                        <option value="Unit">Unit</option>
                        <option value="Liter">Liter</option>
                        <option value="Buah">Buah</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="2" placeholder="Deskripsi singkat item ini..."></textarea>
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
