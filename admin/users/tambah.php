<?php
// ─── admin/users/tambah.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';

Auth::cekSession(); Auth::cekRole(['super_admin']);
$pageTitle='Tambah User'; $sidebarActive='users';
$breadcrumb=[['label'=>'Manajemen User','url'=>BASE_URL.'/admin/users/index.php'],['label'=>'Tambah','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Tambah User Admin</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card" style="max-width:560px;">
    <form method="POST" action="proses.php">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="tambah">
        <div class="form-card-body">
            <div class="form-group"><label class="form-label">Nama Lengkap <span class="required">*</span></label><input type="text" name="nama" class="form-control" required maxlength="100"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Username <span class="required">*</span></label><input type="text" name="username" class="form-control" required maxlength="50" autocomplete="off"></div>
                <div class="form-group"><label class="form-label">Email <span class="required">*</span></label><input type="email" name="email" class="form-control" required maxlength="100"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Password <span class="required">*</span></label><input type="password" name="password" class="form-control" required minlength="8" autocomplete="new-password"><p class="form-hint">Minimal 8 karakter.</p></div>
                <div class="form-group">
                    <label class="form-label">Role <span class="required">*</span></label>
                    <select name="role" class="form-control" required>
                        <option value="admin_data">Admin Data</option>
                        <option value="admin_operasional">Admin Operasional</option>
                        <option value="super_admin">Super Admin</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan User</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
