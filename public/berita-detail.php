<?php
// ─── public/berita-detail.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/core/Request.php';
require_once dirname(__DIR__) . '/core/Response.php';
require_once dirname(__DIR__) . '/models/BeritaModel.php';

$slug  = Request::get('slug', '');
$berita = $slug ? berita_findBySlug($slug) : false;

if (!$berita) {
    Response::abort(404, 'Berita tidak ditemukan atau belum dipublikasikan.');
}

$lainnya   = berita_getLainnya((int)$berita['id'], 3);
$pageTitle = $berita['judul'];
$navActive = 'berita';

require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1 style="font-size:clamp(1.2rem,2.5vw,1.6rem);"><?= e($berita['judul']) ?></h1>
    <nav class="breadcrumb-pub">
        <a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a>
        <span class="sep">›</span>
        <a href="<?= BASE_URL ?>/public/berita.php">Berita</a>
        <span class="sep">›</span>
        <span><?= e(truncate($berita['judul'], 40)) ?></span>
    </nav>
</div></div>

<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 300px;gap:2.5rem;align-items:start;">

            <!-- Konten utama -->
            <article>
                <!-- Meta -->
                <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-bottom:1.5rem;">
                    <?php if ($berita['kategori']): ?>
                    <span class="badge badge-success"><?= e($berita['kategori']) ?></span>
                    <?php endif ?>
                    <?php if ($berita['tags']): ?>
                    <?php foreach (explode(',', $berita['tags']) as $tag): ?>
                    <span class="badge badge-gray">#<?= e(trim($tag)) ?></span>
                    <?php endforeach ?>
                    <?php endif ?>
                </div>

                <!-- Foto cover -->
                <?php if ($berita['foto']): ?>
                <div style="margin-bottom:2rem;border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-md);">
                    <img src="<?= BASE_URL ?>/uploads/berita/<?= e($berita['foto']) ?>"
                         alt="<?= e($berita['judul']) ?>"
                         style="width:100%;max-height:420px;object-fit:cover;">
                </div>
                <?php endif ?>

                <!-- Isi berita -->
                <div class="article-content">
                    <?= sanitizeHtml($berita['isi'] ?? '<p>Konten berita tidak tersedia.</p>') ?>
                </div>

                <!-- Navigasi -->
                <div style="margin-top:2.5rem;padding-top:1.5rem;border-top:1px solid var(--color-border);">
                    <a href="<?= BASE_URL ?>/public/berita.php" class="btn btn-secondary">← Kembali ke Daftar Berita</a>
                </div>
            </article>

            <!-- Sidebar berita lainnya -->
            <aside>
                <?php if (!empty($lainnya)): ?>
                <div class="card">
                    <div class="card-header"><span class="card-title">📰 Berita Lainnya</span></div>
                    <div class="card-body" style="padding:0;">
                    <?php foreach ($lainnya as $l): ?>
                        <a href="<?= BASE_URL ?>/public/berita-detail.php?slug=<?= e($l['slug']) ?>"
                           style="display:flex;gap:.75rem;padding:.85rem 1rem;border-bottom:1px solid var(--color-border-light);text-decoration:none;transition:var(--transition);"
                           onmouseover="this.style.background='var(--color-primary-pale)'"
                           onmouseout="this.style.background=''">
                            <?php if ($l['foto']): ?>
                            <img src="<?= BASE_URL ?>/uploads/berita/<?= e($l['foto']) ?>"
                                 style="width:56px;height:44px;object-fit:cover;border-radius:var(--radius-sm);flex-shrink:0;">
                            <?php else: ?>
                            <div style="width:56px;height:44px;background:var(--color-primary-pale);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.2rem;flex-shrink:0;">📰</div>
                            <?php endif ?>
                            <div>
                                <div style="font-size:13px;font-weight:600;color:var(--color-text);line-height:1.4;"><?= e(truncate($l['judul'], 60)) ?></div>
                                <?php if ($l['kategori']): ?><span class="badge badge-success" style="font-size:10px;margin-top:3px;"><?= e($l['kategori']) ?></span><?php endif ?>
                            </div>
                        </a>
                    <?php endforeach ?>
                    </div>
                </div>
                <?php endif ?>

                <!-- Widget info BSI -->
                <div class="card" style="margin-top:1.25rem;background:var(--color-primary-pale);border-color:var(--color-primary-light);">
                    <div class="card-body">
                        <div style="font-size:2rem;margin-bottom:.5rem;">♻️</div>
                        <h3 style="font-size:14px;font-weight:700;color:var(--color-primary-dark);margin-bottom:.5rem;">BSI Kota Mojokerto</h3>
                        <p style="font-size:13px;color:var(--color-text-muted);line-height:1.6;margin-bottom:1rem;">
                            Bergabunglah dalam program pengelolaan sampah yang berkelanjutan.
                        </p>
                        <a href="<?= BASE_URL ?>/public/data-bank-sampah.php" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">
                            Cari Bank Sampah Terdekat
                        </a>
                    </div>
                </div>
            </aside>

        </div>
    </div>
</section>

<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
