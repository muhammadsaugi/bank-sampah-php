<?php
// ─── admin/kegiatan/index.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/KegiatanModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$search = Request::get('search',''); $status = Request::get('status','');
$data = kegiatan_getAll($search,$status);
$pageTitle='Kegiatan'; $sidebarActive='kegiatan';
$breadcrumb=[['label'=>'Kegiatan','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header">
    <div><h1 class="page-title">Kegiatan</h1></div>
    <a href="tambah.php" class="btn btn-primary">+ Tambah Kegiatan</a>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<form method="GET" class="filter-bar">
    <input type="text" name="search" class="form-control" style="width:220px;" value="<?= e($search) ?>" placeholder="Cari judul...">
    <select name="status" class="form-control" style="width:160px;">
        <option value="">Semua Status</option>
        <option value="akan_datang" <?= $status==='akan_datang'?'selected':'' ?>>Akan Datang</option>
        <option value="berlangsung" <?= $status==='berlangsung'?'selected':'' ?>>Berlangsung</option>
        <option value="selesai" <?= $status==='selesai'?'selected':'' ?>>Selesai</option>
    </select>
    <button type="submit" class="btn btn-secondary">🔍 Filter</button>
    <a href="index.php" class="btn btn-outline">Reset</a>
</form>
<div class="table-wrapper">
    <table class="data-table">
        <thead><tr><th>#</th><th>Judul</th><th>Jenis</th><th>Tanggal</th><th>Lokasi</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="7"><div class="empty-state"><div class="empty-state-icon">🎯</div><h3>Belum ada data kegiatan</h3></div></td></tr>
        <?php else: foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><strong><?= e($row['judul']) ?></strong></td>
                <td><?= e($row['jenis_kegiatan']) ?></td>
                <td><?= formatTanggal($row['tanggal']) ?><?= $row['tanggal_selesai']?' — '.formatTanggal($row['tanggal_selesai']):'' ?></td>
                <td><?= e($row['lokasi']) ?></td>
                <td><?= badgeStatus($row['status']) ?></td>
                <td class="action-cell">
                    <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-secondary">✏️ Edit</a>
                    <form method="POST" action="proses.php" class="inline-form" onsubmit="return confirm('Hapus kegiatan ini?')">
                        <?= CSRF::input() ?><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; endif ?>
        </tbody>
    </table>
</div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
