<?php
// ─── admin/kelembagaan/struktur/index.php ───
require_once dirname(__DIR__, 3) . '/config/app.php';
require_once dirname(__DIR__, 3) . '/config/session.php';
require_once dirname(__DIR__, 3) . '/config/database.php';
require_once dirname(__DIR__, 3) . '/core/Auth.php';
require_once dirname(__DIR__, 3) . '/core/Helper.php';
require_once dirname(__DIR__, 3) . '/core/CSRF.php';
require_once dirname(__DIR__, 3) . '/core/Response.php';
require_once dirname(__DIR__, 3) . '/models/StrukturModel.php';

Auth::cekSession();
Auth::cekRole(['super_admin']);

$data          = struktur_getAll();
$pageTitle     = 'Struktur Organisasi';
$sidebarActive = 'struktur';
$breadcrumb    = [['label' => 'Struktur Organisasi', 'url' => '']];

require_once dirname(__DIR__, 3) . '/templates/admin/header.php';
require_once dirname(__DIR__, 3) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 3) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Struktur Organisasi</h1>
        <p class="page-subtitle">Kelola bagan/foto struktur organisasi BSI</p>
    </div>
    <a href="tambah.php" class="btn btn-primary">+ Upload Struktur</a>
</div>

<?php require_once dirname(__DIR__, 3) . '/templates/admin/flash.php' ?>

<?php if (empty($data)): ?>
    <div class="empty-state">
        <div class="empty-state-icon">🌿</div>
        <h3>Belum ada data struktur organisasi</h3>
        <p>Klik tombol Upload untuk menambahkan bagan struktur.</p>
    </div>
<?php else: ?>
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr><th>#</th><th>Preview</th><th>Judul</th><th>Periode</th><th>Urutan</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php foreach ($data as $i => $row): ?>
        <tr>
            <td><?= $i + 1 ?></td>
            <td>
                <img src="<?= BASE_URL ?>/uploads/struktur/<?= e($row['gambar']) ?>"
                     alt="<?= e($row['judul']) ?>"
                     style="height:48px;width:72px;object-fit:cover;border-radius:var(--radius-sm);border:1px solid var(--color-border);">
            </td>
            <td>
                <strong><?= e($row['judul']) ?></strong>
                <?php if ($row['keterangan']): ?>
                <br><small class="text-muted"><?= e(truncate($row['keterangan'], 60)) ?></small>
                <?php endif ?>
            </td>
            <td><?= e($row['periode']) ?: '-' ?></td>
            <td><?= (int)$row['urutan'] ?></td>
            <td>
                <span class="badge <?= $row['aktif'] ? 'badge-success' : 'badge-danger' ?>">
                    <?= $row['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                </span>
            </td>
            <td class="action-cell">
                <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-secondary">✏️ Edit</a>
                <form method="POST" action="proses.php" class="inline-form"
                      onsubmit="return confirm('Hapus struktur organisasi ini?')">
                    <?= CSRF::input() ?>
                    <input type="hidden" name="aksi" value="hapus">
                    <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                    <button class="btn btn-sm btn-danger">🗑️</button>
                </form>
            </td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php endif ?>

<?php require_once dirname(__DIR__, 3) . '/templates/admin/footer.php' ?>
