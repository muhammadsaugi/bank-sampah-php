<?php
// ─── admin/bank-sampah/edit.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/BankSampahModel.php';

Auth::cekSession();
Auth::cekRole(['admin_data', 'super_admin']);

$id   = Request::int('id', 'get');
$row  = $id > 0 ? bs_findById($id) : false;
if (!$row) Response::abort(404, 'Bank sampah tidak ditemukan.');

$pageTitle     = 'Edit Bank Sampah';
$sidebarActive = 'bank-sampah';
$breadcrumb    = [['label' => 'Bank Sampah', 'url' => BASE_URL . '/admin/bank-sampah/index.php'], ['label' => 'Edit', 'url' => '']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div><h1 class="page-title">Edit Bank Sampah</h1></div>
    <a href="index.php" class="btn btn-outline">← Kembali</a>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<div class="form-card">
    <form method="POST" action="proses.php">
        <?= CSRF::input() ?>
        <input type="hidden" name="aksi" value="edit">
        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">

        <div class="form-card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Nama Bank Sampah <span class="required">*</span></label>
                    <input type="text" name="nama" class="form-control" required maxlength="150" value="<?= e($row['nama']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Ketua <span class="required">*</span></label>
                    <input type="text" name="ketua" class="form-control" required maxlength="100" value="<?= e($row['ketua']) ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                <textarea name="alamat" class="form-control" required rows="2"><?= e($row['alamat']) ?></textarea>
            </div>
            <div class="form-row-3">
                <div class="form-group">
                    <label class="form-label">Kelurahan</label>
                    <input type="text" name="kelurahan" class="form-control" maxlength="100" value="<?= e($row['kelurahan']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Kecamatan</label>
                    <input type="text" name="kecamatan" class="form-control" maxlength="100" value="<?= e($row['kecamatan']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Tahun Berdiri</label>
                    <input type="number" name="tahun_berdiri" class="form-control" min="2000" max="<?= date('Y') ?>" value="<?= e($row['tahun_berdiri']) ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Nomor Kontak</label>
                <input type="text" name="kontak" class="form-control" maxlength="20" value="<?= e($row['kontak']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="aktif" class="form-control">
                    <option value="1" <?= $row['aktif'] ? 'selected' : '' ?>>Aktif</option>
                    <option value="0" <?= !$row['aktif'] ? 'selected' : '' ?>>Nonaktif</option>
                </select>
            </div>
        </div>

        <div class="form-card-footer">
            <a href="index.php" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
