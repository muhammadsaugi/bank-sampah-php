<?php
// ─── admin/nama-sampah/index.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/NamaSampahModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$data = nama_getAll();
$pageTitle='Nama Sampah'; $sidebarActive='nama-sampah';
$breadcrumb=[['label'=>'Nama Sampah','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header">
    <div><h1 class="page-title">Nama Sampah</h1><p class="page-subtitle">Daftar item sampah beserta harga terkini</p></div>
    <a href="tambah.php" class="btn btn-primary">+ Tambah Item</a>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<div class="table-wrapper">
    <table class="data-table">
        <thead><tr><th>#</th><th>Nama Item</th><th>Jenis</th><th>Satuan</th><th>Harga Saat Ini</th><th>Keterangan</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="7"><div class="empty-state"><div class="empty-state-icon">🏷️</div><h3>Belum ada data nama sampah</h3></div></td></tr>
        <?php else: foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><strong><?= e($row['nama']) ?></strong></td>
                <td><span class="badge" style="background:<?= e($row['warna_jenis']??'#ddd') ?>22;color:<?= e($row['warna_jenis']??'#666') ?>"><?= e($row['nama_jenis']) ?></span></td>
                <td><?= e($row['satuan']) ?></td>
                <td class="font-mono"><?= $row['harga_saat_ini'] > 0 ? formatRupiah($row['harga_saat_ini']) : '<span class="text-muted">Belum diset</span>' ?></td>
                <td><?= e(truncate($row['keterangan'] ?? '', 60)) ?></td>
                <td class="action-cell">
                    <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-secondary">✏️ Edit</a>
                    <form method="POST" action="proses.php" class="inline-form" onsubmit="return confirm('Hapus item ini?')">
                        <?= CSRF::input() ?>
                        <input type="hidden" name="aksi" value="hapus">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; endif ?>
        </tbody>
    </table>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
