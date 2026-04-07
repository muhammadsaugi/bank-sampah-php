<?php
// ─── admin/berita/index.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/BeritaModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$search = Request::get('search',''); $status = Request::get('status','');
$data = berita_getAll($search,$status);
$pageTitle='Berita'; $sidebarActive='berita';
$breadcrumb=[['label'=>'Berita','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Berita</h1></div><a href="tambah.php" class="btn btn-primary">+ Tulis Berita</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<form method="GET" class="filter-bar">
    <input type="text" name="search" class="form-control" style="width:220px;" value="<?= e($search) ?>" placeholder="Cari judul...">
    <select name="status" class="form-control" style="width:140px;">
        <option value="">Semua</option>
        <option value="publish" <?= $status==='publish'?'selected':'' ?>>Publish</option>
        <option value="draft" <?= $status==='draft'?'selected':'' ?>>Draft</option>
    </select>
    <button type="submit" class="btn btn-secondary">🔍</button>
    <a href="index.php" class="btn btn-outline">Reset</a>
</form>
<div class="table-wrapper">
    <table class="data-table">
        <thead><tr><th>#</th><th>Judul</th><th>Kategori</th><th>Tags</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="6"><div class="empty-state"><div class="empty-state-icon">📰</div><h3>Belum ada berita</h3></div></td></tr>
        <?php else: foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><strong><?= e(truncate($row['judul'],60)) ?></strong><br><small class="text-muted"><?= e($row['slug']) ?></small></td>
                <td><?= e($row['kategori']) ?></td>
                <td><?= e(truncate($row['tags'],40)) ?></td>
                <td><?= badgeStatus($row['status']) ?></td>
                <td class="action-cell">
                    <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-secondary">✏️ Edit</a>
                    <form method="POST" action="proses.php" class="inline-form" onsubmit="return confirm('Hapus berita ini?')">
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
