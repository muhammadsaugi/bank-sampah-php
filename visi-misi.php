<?php
// ─── public/visi-misi.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/models/ProfilModel.php';

$profil    = profil_get();
$misiList  = array_filter(array_map('trim', explode("\n", $profil['misi'] ?? '')));
$pageTitle = 'Visi & Misi';
$navActive = 'visi-misi';
require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1>Visi &amp; Misi</h1>
    <nav class="breadcrumb-pub"><a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a><span class="sep">›</span><span>Visi &amp; Misi</span></nav>
</div></div>

<section class="section">
    <div class="container" style="max-width:800px;">
        <div class="visi-box">
            <h2>🎯 Visi</h2>
            <p><?= e($profil['visi'] ?: 'Terwujudnya pengelolaan sampah yang berwawasan lingkungan, berkelanjutan, dan memberdayakan masyarakat Kota Mojokerto.') ?></p>
        </div>

        <h2 style="font-family:var(--font-display);color:var(--color-primary-dark);margin-bottom:1.25rem;">🌱 Misi</h2>
        <?php if (!empty($misiList)): ?>
        <div class="misi-list">
            <?php foreach (array_values($misiList) as $idx => $poin): ?>
            <div class="misi-item">
                <div class="misi-num"><?= $idx + 1 ?></div>
                <div class="misi-text"><?= e($poin) ?></div>
            </div>
            <?php endforeach ?>
        </div>
        <?php else: ?>
        <p class="text-muted">Misi belum diisi. Silakan update profil lembaga.</p>
        <?php endif ?>
    </div>
</section>
<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
