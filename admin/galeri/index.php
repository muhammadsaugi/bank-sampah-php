<?php
// ─── admin/galeri/index.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/GaleriModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$kategori = Request::get('kategori','');
$data = galeri_getAll($kategori);
$kategoriList = galeri_getKategoriList();
$pageTitle='Galeri'; $sidebarActive='galeri';
$breadcrumb=[['label'=>'Galeri','url'=>'']];
require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>
<div class="page-header"><div><h1 class="page-title">Galeri Foto</h1></div><a href="tambah.php" class="btn btn-primary">+ Upload Foto</a></div>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>
<form method="GET" class="filter-bar">
    <label>Kategori:</label>
    <select name="kategori" class="form-control" style="width:180px;">
        <option value="">Semua Kategori</option>
        <?php foreach ($kategoriList as $k): ?><option value="<?= e($k) ?>" <?= $kategori===$k?'selected':'' ?>><?= e($k) ?></option><?php endforeach ?>
    </select>
    <button type="submit" class="btn btn-secondary">🔍</button>
    <a href="index.php" class="btn btn-outline">Reset</a>
</form>
<?php if (empty($data)): ?>
    <div class="empty-state"><div class="empty-state-icon">🖼️</div><h3>Belum ada foto di galeri</h3></div>
<?php else: ?>
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:1rem;">
    <?php foreach ($data as $row): ?>
    <div class="card" style="overflow:hidden;">
        <div style="aspect-ratio:4/3;overflow:hidden;background:var(--color-primary-pale);">
            <img src="<?= BASE_URL ?>/uploads/galeri/<?= e($row['gambar']) ?>" alt="<?= e($row['judul']) ?>" style="width:100%;height:100%;object-fit:cover;">
        </div>
        <div style="padding:.75rem;">
            <div style="font-weight:600;font-size:13.5px;margin-bottom:.25rem;"><?= e(truncate($row['judul'],40)) ?></div>
            <div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:.35rem;">
                <span class="badge badge-info"><?= e($row['kategori']) ?></span>
                <div style="display:flex;gap:.35rem;">
                    <form method="POST" action="proses.php" onsubmit="return confirm('Hapus foto ini?')">
                        <?= CSRF::input() ?><input type="hidden" name="aksi" value="hapus"><input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>
<?php endif ?>
<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
