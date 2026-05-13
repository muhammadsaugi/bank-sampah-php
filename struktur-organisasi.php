<?php
// ─── public/struktur-organisasi.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/models/StrukturModel.php';

$data      = struktur_getAktif();
$pageTitle = 'Struktur Organisasi';
$navActive = 'struktur';
require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1>Struktur Organisasi</h1>
    <nav class="breadcrumb-pub"><a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a><span class="sep">›</span><span>Struktur Organisasi</span></nav>
</div></div>

<section class="section">
    <div class="container">
        <?php if (empty($data)): ?>
        <div class="empty-state"><div class="empty-state-icon">🌿</div><h3>Data struktur organisasi belum tersedia</h3></div>
        <?php else: foreach ($data as $s): ?>
        <div class="card" style="margin-bottom:2rem;">
            <div class="card-header">
                <div>
                    <h2 style="font-family:var(--font-display);font-size:1.25rem;"><?= e($s['judul']) ?></h2>
                    <?php if ($s['periode']): ?><span class="badge badge-success">Periode <?= e($s['periode']) ?></span><?php endif ?>
                </div>
            </div>
            <div class="card-body" style="text-align:center;">
                <img src="<?= BASE_URL ?>/uploads/struktur/<?= e($s['gambar']) ?>"
                     alt="<?= e($s['judul']) ?>"
                     style="max-width:100%;border-radius:var(--radius-md);box-shadow:var(--shadow);">
                <?php if ($s['keterangan']): ?><p style="margin-top:1rem;color:var(--color-text-muted);font-size:14px;"><?= e($s['keterangan']) ?></p><?php endif ?>
            </div>
        </div>
        <?php endforeach; endif ?>
    </div>
</section>
<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
