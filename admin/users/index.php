<?php
// ─── admin/users/index.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/UserModel.php';

Auth::cekSession();
Auth::cekRole(['super_admin']);

$data          = user_getAll();
$pageTitle     = 'Manajemen User';
$sidebarActive = 'users';
$breadcrumb    = [['label' => 'Manajemen User', 'url' => '']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Manajemen User</h1>
        <p class="page-subtitle">Kelola akun admin sistem BSI</p>
    </div>
    <a href="tambah.php" class="btn btn-primary">+ Tambah User</a>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th><th>Nama</th><th>Username</th><th>Email</th>
                <th>Role</th><th>Status</th><th>Login Terakhir</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="8"><div class="empty-state"><div class="empty-state-icon">👥</div><h3>Belum ada user</h3></div></td></tr>
        <?php else: foreach ($data as $i => $row):
            $isSelf = $row['id'] === Auth::getUserId();
        ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td>
                <div style="display:flex;align-items:center;gap:.5rem;">
                    <div style="width:32px;height:32px;border-radius:50%;background:var(--color-primary);color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">
                        <?= e(inisialNama($row['nama'])) ?>
                    </div>
                    <strong><?= e($row['nama']) ?></strong>
                    <?php if ($isSelf): ?><span class="badge badge-info" style="font-size:10px;">Anda</span><?php endif ?>
                </div>
            </td>
            <td class="font-mono"><?= e($row['username']) ?></td>
            <td><?= e($row['email']) ?></td>
            <td><?= badgeRole($row['role']) ?></td>
            <td>
                <span class="badge <?= $row['aktif'] ? 'badge-success' : 'badge-danger' ?>">
                    <?= $row['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                </span>
            </td>
            <td style="font-size:12.5px;color:var(--color-text-muted);">
                <?= $row['last_login'] ? formatTanggal(substr($row['last_login'],0,10),'d M Y') . ' ' . substr($row['last_login'],11,5) : '-' ?>
            </td>
            <td class="action-cell">
                <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-secondary">✏️ Edit</a>
                <?php if (!$isSelf): ?>
                <form method="POST" action="proses.php" class="inline-form"
                      onsubmit="return confirm('Hapus user <?= e($row['username']) ?>?')">
                    <?= CSRF::input() ?>
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                    <button class="btn btn-sm btn-danger">🗑️</button>
                </form>
                <?php endif ?>
            </td>
        </tr>
        <?php endforeach; endif ?>
        </tbody>
    </table>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
