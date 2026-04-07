<?php
// ─── public/galeri.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/core/Request.php';
require_once dirname(__DIR__) . '/models/GaleriModel.php';

$kategoriAktif = Request::get('kategori', '');
$kategoriList  = galeri_getKategoriList();
$data          = galeri_getAll($kategoriAktif);
$pageTitle     = 'Galeri';
$navActive     = 'galeri';

require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1>Galeri Foto</h1>
    <nav class="breadcrumb-pub">
        <a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a>
        <span class="sep">›</span><span>Galeri</span>
    </nav>
</div></div>

<section class="section">
    <div class="container">

        <!-- Filter kategori -->
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem;justify-content:center;">
            <a href="?kategori="
               class="btn <?= $kategoriAktif === '' ? 'btn-primary' : 'btn-outline' ?>">
                Semua
            </a>
            <?php foreach ($kategoriList as $kat): ?>
            <a href="?kategori=<?= urlencode($kat) ?>"
               class="btn <?= $kategoriAktif === $kat ? 'btn-primary' : 'btn-outline' ?>">
                <?= e($kat) ?>
            </a>
            <?php endforeach ?>
        </div>

        <?php if (empty($data)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">🖼️</div>
            <h3>Belum ada foto di galeri</h3>
            <?php if ($kategoriAktif): ?>
            <p>Tidak ada foto untuk kategori "<?= e($kategoriAktif) ?>".</p>
            <?php endif ?>
        </div>
        <?php else: ?>

        <!-- Grid galeri dengan CSS Lightbox (CSS :target trick — tanpa JS) -->
        <div class="galeri-grid">
        <?php foreach ($data as $foto): ?>
            <!-- Thumbnail yang bisa diklik untuk lightbox -->
            <a href="#foto-<?= (int)$foto['id'] ?>" class="galeri-item">
                <img src="<?= BASE_URL ?>/uploads/galeri/<?= e($foto['gambar']) ?>"
                     alt="<?= e($foto['judul']) ?>"
                     loading="lazy">
                <div class="galeri-item-overlay">
                    <div class="galeri-item-title"><?= e($foto['judul']) ?></div>
                </div>
            </a>

            <!-- Lightbox modal (CSS :target) -->
            <div id="foto-<?= (int)$foto['id'] ?>" class="lightbox">
                <div class="lightbox-inner">
                    <a href="#" class="lightbox-close" aria-label="Tutup">×</a>
                    <img src="<?= BASE_URL ?>/uploads/galeri/<?= e($foto['gambar']) ?>"
                         alt="<?= e($foto['judul']) ?>">
                    <div class="lightbox-caption">
                        <strong><?= e($foto['judul']) ?></strong>
                        <?php if ($foto['deskripsi']): ?>
                        — <?= e($foto['deskripsi']) ?>
                        <?php endif ?>
                        <span class="badge badge-info" style="margin-left:.5rem;"><?= e($foto['kategori']) ?></span>
                        <?php if ($foto['tanggal']): ?>
                        <span style="opacity:.6;font-size:12px;margin-left:.5rem;"><?= formatTanggal($foto['tanggal']) ?></span>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
        </div>

        <p style="text-align:center;color:var(--color-text-muted);font-size:13px;margin-top:1.5rem;">
            Menampilkan <?= count($data) ?> foto<?= $kategoriAktif ? ' — kategori ' . e($kategoriAktif) : '' ?>
        </p>

        <?php endif ?>

    </div>
</section>

<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
