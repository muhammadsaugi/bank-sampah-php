<?php
// ─── admin/bank-sampah/index.php ───
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

$search        = Request::get('search', '');
$data          = bs_getAll($search);
$pageTitle     = 'Bank Sampah';
$sidebarActive = 'bank-sampah';
$breadcrumb    = [['label' => 'Bank Sampah', 'url' => '']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Daftar Bank Sampah</h1>
        <p class="page-subtitle">Kelola data bank sampah unit yang terdaftar</p>
    </div>
    <a href="tambah.php" class="btn btn-primary">+ Tambah Bank Sampah</a>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<form method="GET" class="search-form">
    <input type="text" name="search" class="form-control" value="<?= e($search) ?>" placeholder="Cari nama, ketua, atau kelurahan...">
    <button type="submit" class="btn btn-secondary">🔍 Cari</button>
    <?php if ($search): ?><a href="index.php" class="btn btn-outline">Reset</a><?php endif ?>
</form>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:40px">#</th>
                <th>Nama Bank Sampah</th>
                <th>Kelurahan / Kecamatan</th>
                <th>Ketua</th>
                <th>Kontak</th>
                <th>Berdiri</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="8"><div class="empty-state"><div class="empty-state-icon">🏦</div><h3>Belum ada data bank sampah</h3><p>Klik tombol Tambah untuk menambahkan data baru.</p></div></td></tr>
        <?php else: ?>
            <?php foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><strong><?= e($row['nama']) ?></strong><br><small class="text-muted"><?= e($row['alamat']) ?></small></td>
                <td><?= e($row['kelurahan']) ?><?= $row['kecamatan'] ? ' / ' . e($row['kecamatan']) : '' ?></td>
                <td><?= e($row['ketua']) ?></td>
                <td><?= e($row['kontak']) ?></td>
                <td><?= $row['tahun_berdiri'] ? e($row['tahun_berdiri']) : '-' ?></td>
                <td>
                    <span class="badge <?= $row['aktif'] ? 'badge-success' : 'badge-danger' ?>">
                        <?= $row['aktif'] ? 'Aktif' : 'Nonaktif' ?>
                    </span>
                </td>
                <td class="action-cell">
                    <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-secondary">✏️ Edit</a>
                    <form method="POST" action="proses.php" class="inline-form" onsubmit="return confirm('Toggle status bank sampah ini?')">
                        <?= CSRF::input() ?>
                        <input type="hidden" name="aksi" value="toggle">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button class="btn btn-sm btn-outline"><?= $row['aktif'] ? '🔴 Nonaktifkan' : '🟢 Aktifkan' ?></button>
                    </form>
                    <form method="POST" action="proses.php" class="inline-form" onsubmit="return confirm('Hapus bank sampah ini? Data rekap terkait tidak dapat dihapus.')">
                        <?= CSRF::input() ?>
                        <input type="hidden" name="aksi" value="hapus">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button class="btn btn-sm btn-danger">🗑️ Hapus</button>
                    </form>
                </td>
            </tr>
            <?php endforeach ?>
        <?php endif ?>
        </tbody>
    </table>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
