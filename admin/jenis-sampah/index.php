<?php
// ─── admin/jenis-sampah/index.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/JenisSampahModel.php';

Auth::cekSession();
Auth::cekRole(['admin_data', 'super_admin']);

$data          = jenis_getAll();
$pageTitle     = 'Jenis Sampah';
$sidebarActive = 'jenis-sampah';
$breadcrumb    = [['label' => 'Jenis Sampah', 'url' => '']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div><h1 class="page-title">Jenis Sampah</h1><p class="page-subtitle">Kategori utama pengelompokan sampah</p></div>
    <a href="tambah.php" class="btn btn-primary">+ Tambah Jenis</a>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr><th>#</th><th>Nama Jenis</th><th>Kode</th><th>Warna</th><th>Jumlah Item</th><th>Dibuat Oleh</th><th>Aksi</th></tr>
        </thead>
        <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="7"><div class="empty-state"><div class="empty-state-icon">🗂️</div><h3>Belum ada jenis sampah</h3></div></td></tr>
        <?php else: ?>
            <?php foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><strong><?= e($row['nama']) ?></strong></td>
                <td><span class="badge badge-gray"><?= e($row['kode']) ?></span></td>
                <td>
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <span style="width:20px;height:20px;border-radius:50%;background:<?= e($row['warna']) ?>;display:inline-block;border:1px solid var(--color-border)"></span>
                        <span class="font-mono" style="font-size:12px;"><?= e($row['warna']) ?></span>
                    </div>
                </td>
                <td><span class="badge badge-info"><?= e($row['jumlah_item']) ?> item</span></td>
                <td><?= e($row['nama_petugas'] ?? '-') ?></td>
                <td class="action-cell">
                    <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-secondary">✏️ Edit</a>
                    <form method="POST" action="proses.php" class="inline-form" onsubmit="return confirm('Hapus jenis sampah ini? Semua nama sampah di bawahnya juga akan terhapus!')">
                        <?= CSRF::input() ?>
                        <input type="hidden" name="aksi" value="hapus">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </td>
            </tr>
            <?php endforeach ?>
        <?php endif ?>
        </tbody>
    </table>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
