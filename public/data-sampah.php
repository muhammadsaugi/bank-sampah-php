<?php
// ─── public/data-sampah.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/core/Request.php';
require_once dirname(__DIR__) . '/models/JenisSampahModel.php';
require_once dirname(__DIR__) . '/models/NamaSampahModel.php';

$jenisList = jenis_getAll();
$pageTitle = 'Data Sampah';
$navActive = 'data-sampah';

// Tab aktif dari GET atau default ke jenis pertama
$activeTab = Request::int('jenis', 'get');
if ($activeTab <= 0 && !empty($jenisList)) {
    $activeTab = (int)$jenisList[0]['id'];
}

require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1>Data Sampah &amp; Harga</h1>
    <nav class="breadcrumb-pub">
        <a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a>
        <span class="sep">›</span><span>Data Sampah</span>
    </nav>
</div></div>

<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Katalog Sampah</span>
            <h2 class="section-title">Daftar Jenis &amp; Harga Sampah</h2>
            <p class="section-desc">Harga sampah yang berlaku saat ini di Bank Sampah Induk Kota Mojokerto.</p>
        </div>

        <?php if (empty($jenisList)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">🗂️</div>
            <h3>Data sampah belum tersedia</h3>
        </div>
        <?php else: ?>

        <!-- Tab labels — filter via GET (PHP-rendered, CSS toggle) -->
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem;">
            <?php foreach ($jenisList as $j): ?>
            <a href="?jenis=<?= (int)$j['id'] ?>"
               class="btn <?= $activeTab === (int)$j['id'] ? 'btn-primary' : 'btn-outline' ?>"
               style="<?= $activeTab === (int)$j['id'] ? '' : 'border-color:' . e($j['warna']) . ';color:' . e($j['warna']) . ';' ?>">
                <?= e($j['nama']) ?>
            </a>
            <?php endforeach ?>
        </div>

        <!-- Konten per tab -->
        <?php foreach ($jenisList as $j):
            if ((int)$j['id'] !== $activeTab) continue;
            $items = nama_getByJenis((int)$j['id']);
        ?>
        <div>
            <div style="display:flex;align-items:center;gap:.75rem;margin-bottom:1.25rem;">
                <span style="width:14px;height:14px;border-radius:50%;background:<?= e($j['warna']) ?>;display:inline-block;flex-shrink:0;"></span>
                <h3 style="font-size:1.1rem;font-weight:700;color:var(--color-text);"><?= e($j['nama']) ?></h3>
                <span class="badge badge-info"><?= count($items) ?> item</span>
            </div>

            <?php if (empty($items)): ?>
            <div class="alert alert-info"><span class="alert-icon">ℹ️</span><div class="alert-body">Belum ada item sampah untuk jenis ini.</div></div>
            <?php else: ?>
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Item</th>
                            <th>Satuan</th>
                            <th>Harga Beli (per satuan)</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($items as $idx => $item): ?>
                    <tr>
                        <td><?= $idx + 1 ?></td>
                        <td><strong><?= e($item['nama']) ?></strong></td>
                        <td><?= e($item['satuan']) ?></td>
                        <td>
                            <?php if ((float)$item['harga_saat_ini'] > 0): ?>
                            <strong class="font-mono" style="color:var(--color-primary);">
                                <?= formatRupiah($item['harga_saat_ini']) ?>
                            </strong>
                            <?php else: ?>
                            <span class="badge badge-gray">Belum diset</span>
                            <?php endif ?>
                        </td>
                        <td style="font-size:13px;color:var(--color-text-muted);">
                            <?= e($item['keterangan'] ?: '-') ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <?php endif ?>
        </div>
        <?php endforeach ?>

        <div class="alert alert-info" style="margin-top:2rem;">
            <span class="alert-icon">ℹ️</span>
            <div class="alert-body">
                Harga yang tertera adalah harga beli BSI dari bank sampah unit. Harga dapat berubah sewaktu-waktu sesuai kondisi pasar.
                Untuk informasi terkini, hubungi BSI Mojokerto langsung.
            </div>
        </div>

        <?php endif ?>
    </div>
</section>

<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
