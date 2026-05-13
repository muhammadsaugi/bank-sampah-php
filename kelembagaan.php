<?php
// ─── public/kelembagaan.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/models/ProfilModel.php';

$profil    = profil_get();
$pageTitle = 'Profil Kelembagaan';
$navActive = 'kelembagaan';
require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1>Profil Kelembagaan</h1>
    <nav class="breadcrumb-pub"><a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a><span class="sep">›</span><span>Profil Kelembagaan</span></nav>
</div></div>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:2fr 1fr;gap:3rem;align-items:start;">
            <div>
                <h2 style="font-family:var(--font-display);color:var(--color-primary-dark);font-size:1.8rem;margin-bottom:1rem;"><?= e($profil['nama_lembaga']) ?></h2>
                <?php if ($profil['tagline']): ?><p style="font-size:1.1rem;color:var(--color-primary);font-style:italic;margin-bottom:1.5rem;">"<?= e($profil['tagline']) ?>"</p><?php endif ?>
                <div style="font-size:15px;line-height:1.8;color:var(--color-text);"><?= nl2br(e($profil['deskripsi'] ?: 'Bank Sampah Induk (BSI) Kota Mojokerto adalah lembaga pengelola sampah terpadu yang bertugas mengkoordinasikan jaringan bank sampah unit di seluruh wilayah Kota Mojokerto.')) ?></div>
            </div>
            <div>
                <div class="card" style="padding:1.5rem;">
                    <h3 style="font-size:14px;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.06em;margin-bottom:1rem;">Informasi Kontak</h3>
                    <?php if ($profil['alamat']): ?><div style="display:flex;gap:.5rem;margin-bottom:.75rem;font-size:14px;"><span>📍</span><span><?= nl2br(e($profil['alamat'])) ?></span></div><?php endif ?>
                    <?php if ($profil['telepon']): ?><div style="display:flex;gap:.5rem;margin-bottom:.75rem;font-size:14px;"><span>📞</span><span><?= e($profil['telepon']) ?></span></div><?php endif ?>
                    <?php if ($profil['email']): ?><div style="display:flex;gap:.5rem;margin-bottom:.75rem;font-size:14px;"><span>✉️</span><span><?= e($profil['email']) ?></span></div><?php endif ?>
                    <?php if ($profil['tahun_berdiri']): ?><div style="display:flex;gap:.5rem;font-size:14px;"><span>🗓️</span><span>Berdiri tahun <?= e($profil['tahun_berdiri']) ?></span></div><?php endif ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
