<?php
// ─── admin/bank-sampah/tambah.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';

Auth::cekSession();
Auth::cekRole(['admin_data', 'super_admin']);

$pageTitle     = 'Tambah Bank Sampah';
$sidebarActive = 'bank-sampah';
$breadcrumb    = [['label' => 'Bank Sampah', 'url' => BASE_URL . '/admin/bank-sampah/index.php'], ['label' => 'Tambah', 'url' => '']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Bank Sampah</h1>
        <p class="page-subtitle">Daftarkan bank sampah unit baru</p>
    </div>
    <a href="index.php" class="btn btn-outline">← Kembali</a>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<div class="form-card">
    <div class="form-card-header">
        <span class="form-card-title">🏦 Formulir Bank Sampah</span>
    </div>
    <form method="POST" action="proses.php">
        <?= CSRF::input() ?>
        <input type="hidden" name="aksi" value="tambah">

        <div class="form-card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Bank Sampah <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-control" required maxlength="150" placeholder="Contoh: BSU Maju Bersama">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Ketua <span class="required">*</span></label>
                    <input type="text" name="ketua" class="form-control" required maxlength="100" placeholder="Nama ketua/koordinator">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                <textarea name="alamat" class="form-control" required rows="2" placeholder="Jl. ..."></textarea>
            </div>
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control" maxlength="100" placeholder="Nama kelurahan">
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control" maxlength="100" placeholder="Nama kecamatan">
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Berdiri</label>
                    <input type="number" name="tahun_berdiri" class="form-control" min="2000" max="<?= date('Y') ?>" placeholder="<?= date('Y') ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Kontak</label>
                <input type="text" name="kontak" class="form-control" maxlength="20" placeholder="08xxxxxxxxxx">
            </div>
        </div>

        <div class="form-card-footer">
            <a href="index.php" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">💾 Simpan Bank Sampah</button>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
