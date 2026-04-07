<?php
// ─── public/data-bank-sampah.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/core/Request.php';
require_once dirname(__DIR__) . '/models/BankSampahModel.php';

$search    = Request::get('search', '');
$data      = bs_getAll($search, true); // aktif saja
$pageTitle = 'Data Bank Sampah';
$navActive = 'bank-sampah';
require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1>Data Bank Sampah</h1>
    <nav class="breadcrumb-pub"><a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a><span class="sep">›</span><span>Data Bank Sampah</span></nav>
</div></div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Jaringan BSI</span>
            <h2 class="section-title">Daftar Bank Sampah Unit</h2>
            <p class="section-desc">Bank sampah unit aktif yang terdaftar dalam jaringan BSI Kota Mojokerto. Total <strong><?= count($data) ?></strong> unit.</p>
        </div>

        <form method="GET" style="display:flex;gap:.5rem;max-width:400px;margin:0 auto 2rem;">
            <input type="text" name="search" class="form-control" value="<?= e($search) ?>" placeholder="Cari nama, ketua, atau wilayah...">
            <button type="submit" class="btn btn-primary">🔍</button>
            <?php if ($search): ?><a href="data-bank-sampah.php" class="btn btn-outline">Reset</a><?php endif ?>
        </form>

        <?php if (empty($data)): ?>
        <div class="empty-state"><div class="empty-state-icon">🏦</div><h3>Tidak ada data yang cocok</h3></div>
        <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead><tr><th>#</th><th>Nama Bank Sampah</th><th>Alamat</th><th>Ketua</th><th>Kontak</th><th>Berdiri</th></tr></thead>
                <tbody>
                <?php foreach ($data as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= e($row['nama']) ?></strong><?php if ($row['kelurahan']): ?><br><small class="text-muted">Kel. <?= e($row['kelurahan']) ?><?= $row['kecamatan'] ? ', Kec. ' . e($row['kecamatan']) : '' ?></small><?php endif ?></td>
                    <td><?= e($row['alamat']) ?></td>
                    <td><?= e($row['ketua']) ?></td>
                    <td><?= e($row['kontak']) ?: '-' ?></td>
                    <td><?= $row['tahun_berdiri'] ? e($row['tahun_berdiri']) : '-' ?></td>
                </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <?php endif ?>
    </div>
</section>
<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
