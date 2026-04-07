<?php
// ─── admin/users/edit.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/UserModel.php';

Auth::cekSession(); Auth::cekRole(['super_admin']);
$id  = Request::int('id','get');
$row = $id > 0 ? user_findById($id) : false;
if (!$row) Response::abort(404,'User tidak ditemukan.');
$pageTitle='Edit User'; $sidebarActive='users';
$breadcrumb=[['label'=>'Manajemen User','url'=>BASE_URL.'/admin/users/index.php'],['label'=>'Edit','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Edit User</h1></div><a href="index.php" class="btn btn-outline">← Kembali</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="form-card" style="max-width:560px;">
    <form method="POST" action="proses.php">
        <?= CSRF::input() ?><input type="hidden" name="aksi" value="edit"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
        <div class="form-card-body">
            <div class="form-group"><label class="form-label">Nama Lengkap <span class="required">*</span></label><input type="text" name="nama" class="form-control" required maxlength="100" value="<?= e($row['nama']) ?>"></div>
            <div class="form-row">
                <div class="form-group"><label class="form-label">Username</label><input type="text" class="form-control" value="<?= e($row['username']) ?>" disabled><p class="form-hint">Username tidak bisa diubah.</p></div>
                <div class="form-group"><label class="form-label">Email <span class="required">*</span></label><input type="email" name="email" class="form-control" required maxlength="100" value="<?= e($row['email']) ?>"></div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-control">
                        <option value="admin_data" <?= $row['role']==='admin_data'?'selected':'' ?>>Admin Data</option>
                        <option value="admin_operasional" <?= $row['role']==='admin_operasional'?'selected':'' ?>>Admin Operasional</option>
                        <option value="super_admin" <?= $row['role']==='super_admin'?'selected':'' ?>>Super Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="aktif" class="form-control">
                        <option value="1" <?= $row['aktif']?'selected':'' ?>>Aktif</option>
                        <option value="0" <?= !$row['aktif']?'selected':'' ?>>Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru</label>
                <input type="password" name="password" class="form-control" minlength="8" autocomplete="new-password">
                <p class="form-hint">Kosongkan jika tidak ingin mengubah password.</p>
            </div>
        </div>
        <div class="form-card-footer"><a href="index.php" class="btn btn-outline">Batal</a><button type="submit" class="btn btn-primary">💾 Simpan</button></div>
    </form>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
