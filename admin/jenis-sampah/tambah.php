<?php
// ─── admin/jenis-sampah/tambah.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$pageTitle='Tambah Jenis Sampah'; $sidebarActive='jenis-sampah';
$breadcrumb=[['label'=>'Jenis Sampah','url'=>BASE_URL.'/admin/jenis-sampah/index.php'],['label'=>'Tambah','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Tambah Jenis Sampah</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card" style="max-width:560px;">
    <form method="POST" action="proses.php">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="tambah">
        <div class="form-card-body">
            <div class="form-group">
                <label class="form-label">Nama Jenis <span class="required">*</span></label>
                <input type="text" name="nama" class="form-control" required maxlength="100" placeholder="Plastik, Kertas, Logam...">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" class="form-control" maxlength="10" placeholder="PLT">
                </div>
                <div class="form-group">
                    <label class="form-label">Warna Penanda</label>
                    <input type="color" name="warna" class="form-control" value="#16A34A" style="height:42px;padding:4px;">
                </div>
            </div>
        </div>
        <div class="form-card-footer">
            <a href="index.php" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">💾 Simpan</button>
        </div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
