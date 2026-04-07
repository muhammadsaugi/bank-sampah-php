<?php
// ─── public/berita.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/models/BeritaModel.php';

$data      = berita_getPublish(12);
$pageTitle = 'Berita';
$navActive = 'berita';

require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1>Berita &amp; Informasi</h1>
    <nav class="breadcrumb-pub">
        <a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a>
        <span class="sep">›</span><span>Berita</span>
    </nav>
</div></div>

<section class="section">
    <div class="container">

        <?php if (empty($data)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📰</div>
            <h3>Belum ada berita yang dipublikasikan</h3>
        </div>
        <?php else: ?>
        <div class="pub-grid">
        <?php foreach ($data as $b): ?>
            <div class="pub-card">
                <?php if ($b['foto']): ?>
                <img src="<?= BASE_URL ?>/uploads/berita/<?= e($b['foto']) ?>"
                     alt="<?= e($b['judul']) ?>" class="pub-card-img">
                <?php else: ?>
                <div class="pub-card-img-placeholder">📰</div>
                <?php endif ?>
                <div class="pub-card-body">
                    <div class="pub-card-meta">
                        <?php if ($b['kategori']): ?>
                        <span class="badge badge-success"><?= e($b['kategori']) ?></span>
                        <?php endif ?>
                        <?php if ($b['tags']): ?>
                        <?php foreach (array_slice(explode(',', $b['tags']), 0, 2) as $tag): ?>
                        <span class="badge badge-gray"><?= e(trim($tag)) ?></span>
                        <?php endforeach ?>
                        <?php endif ?>
                    </div>
                    <h3 class="pub-card-title">
                        <a href="<?= BASE_URL ?>/public/berita-detail.php?slug=<?= e($b['slug']) ?>">
                            <?= e($b['judul']) ?>
                        </a>
                    </h3>
                    <p class="pub-card-desc">
                        <?= e(truncate(strip_tags($b['isi'] ?? ''), 130)) ?>
                    </p>
                    <div class="pub-card-footer">
                        <a href="<?= BASE_URL ?>/public/berita-detail.php?slug=<?= e($b['slug']) ?>"
                           class="btn btn-sm btn-secondary">
                            Baca Selengkapnya →
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
        </div>
        <?php endif ?>

    </div>
</section>

<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
