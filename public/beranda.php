<?php
// ─── public/beranda.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/models/ProfilModel.php';
require_once dirname(__DIR__) . '/models/BankSampahModel.php';
require_once dirname(__DIR__) . '/models/RekapModel.php';
require_once dirname(__DIR__) . '/models/KegiatanModel.php';
require_once dirname(__DIR__) . '/models/BeritaModel.php';

$profil   = profil_get();
$statBank = bs_count();
$statRekap = rekap_count();
$statBerat = rekap_totalBerat();
$statHarga = rekap_totalHarga();
$kegiatan = kegiatan_getPublik(3);
$berita   = berita_getPublish(3);

$pageTitle = 'Beranda';
$navActive = 'beranda';

require_once dirname(__DIR__) . '/templates/public/header.php';
?>

<!-- ═══ HERO ═══ -->
<section class="hero">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-badge">♻️ &nbsp; Bank Sampah Induk Kota Mojokerto</div>
        <h1 class="hero-title">
            Mengelola Sampah,<br>
            <span>Membangun Nilai</span>
        </h1>
        <p class="hero-tagline">
            <?= e($profil['tagline'] ?: 'Sistem informasi terpadu Bank Sampah Induk Kota Mojokerto untuk pengelolaan sampah yang berkelanjutan dan memberdayakan masyarakat.') ?>
        </p>
        <div class="hero-actions">
            <a href="<?= BASE_URL ?>/public/data-bank-sampah.php" class="btn btn-primary btn-lg">Lihat Data Bank Sampah</a>
            <a href="<?= BASE_URL ?>/public/rekapitulasi.php" class="btn btn-outline-white btn-lg">Rekapitulasi</a>
        </div>
    </div>
    <a href="#statistik" class="hero-scroll" aria-label="Scroll ke bawah">↓</a>
</section>

<!-- ═══ STATISTIK ═══ -->
<div class="container" id="statistik">
    <div class="stats-grid">
        <div class="stat-pub-card">
            <div class="stat-pub-icon">🏦</div>
            <div class="stat-pub-value"><?= $statBank ?></div>
            <div class="stat-pub-label">Bank Sampah Aktif</div>
        </div>
        <div class="stat-pub-card">
            <div class="stat-pub-icon">📋</div>
            <div class="stat-pub-value"><?= number_format($statRekap, 0, ',', '.') ?></div>
            <div class="stat-pub-label">Total Transaksi</div>
        </div>
        <div class="stat-pub-card">
            <div class="stat-pub-icon">⚖️</div>
            <div class="stat-pub-value"><?= number_format($statBerat / 1000, 1, ',', '.') ?> ton</div>
            <div class="stat-pub-label">Sampah Terkelola</div>
        </div>
        <div class="stat-pub-card">
            <div class="stat-pub-icon">💰</div>
            <div class="stat-pub-value" style="font-size:1.4rem;"><?= formatRupiah($statHarga) ?></div>
            <div class="stat-pub-label">Total Nilai Sampah</div>
        </div>
    </div>
</div>

<!-- ═══ TENTANG ═══ -->
<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;">
            <div>
                <span class="section-label">Tentang Kami</span>
                <h2 class="section-title" style="text-align:left;"><?= e($profil['nama_lembaga']) ?></h2>
                <p style="color:var(--color-text-muted);line-height:1.8;margin-bottom:1.5rem;">
                    <?= e(truncate($profil['deskripsi'] ?? 'Bank Sampah Induk (BSI) Kota Mojokerto adalah lembaga pengelola sampah terpadu yang mengkoordinasikan jaringan bank sampah unit di seluruh wilayah Kota Mojokerto.', 350)) ?>
                </p>
                <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                    <a href="<?= BASE_URL ?>/public/kelembagaan.php" class="btn btn-primary">Profil Lembaga</a>
                    <a href="<?= BASE_URL ?>/public/visi-misi.php" class="btn btn-secondary">Visi &amp; Misi</a>
                </div>
            </div>
            <div style="background:var(--color-primary-pale);border-radius:var(--radius-lg);padding:2.5rem;border:1px solid var(--color-primary-light);">
                <div style="font-size:3rem;margin-bottom:1rem;">♻️</div>
                <h3 style="font-family:var(--font-display);color:var(--color-primary-dark);margin-bottom:1rem;">Mengapa Daur Ulang?</h3>
                <?php
                $poinPoin = [
                    '🌱 Mengurangi volume sampah di TPA',
                    '💡 Menciptakan nilai ekonomi dari sampah',
                    '🤝 Memberdayakan komunitas lokal',
                    '🌍 Menjaga lingkungan untuk generasi mendatang',
                ];
                foreach ($poinPoin as $p): ?>
                <p style="font-size:14px;color:var(--color-text);margin-bottom:.6rem;"><?= $p ?></p>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</section>

<!-- ═══ KEGIATAN TERBARU ═══ -->
<?php if (!empty($kegiatan)): ?>
<section class="section section-alt">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Kegiatan</span>
            <h2 class="section-title">Kegiatan Terkini</h2>
            <p class="section-desc">Informasi kegiatan sosialisasi, pelatihan, dan program lingkungan dari BSI Kota Mojokerto.</p>
        </div>
        <div class="pub-grid">
        <?php foreach ($kegiatan as $k): ?>
            <div class="pub-card">
                <?php if ($k['foto']): ?>
                <img src="<?= BASE_URL ?>/uploads/kegiatan/<?= e($k['foto']) ?>" class="pub-card-img" alt="<?= e($k['judul']) ?>">
                <?php else: ?>
                <div class="pub-card-img-placeholder">🎯</div>
                <?php endif ?>
                <div class="pub-card-body">
                    <div class="pub-card-meta">
                        <span>📅 <?= formatTanggal($k['tanggal']) ?></span>
                        <?= badgeStatus($k['status']) ?>
                    </div>
                    <h3 class="pub-card-title"><?= e($k['judul']) ?></h3>
                    <p class="pub-card-desc"><?= e(truncate($k['deskripsi'] ?? '', 100)) ?></p>
                    <div class="pub-card-footer">
                        <span class="badge badge-gray">📍 <?= e($k['lokasi'] ?: 'BSI Mojokerto') ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
        </div>
        <div style="text-align:center;margin-top:2rem;">
            <a href="<?= BASE_URL ?>/public/kegiatan.php" class="btn btn-secondary">Lihat Semua Kegiatan</a>
        </div>
    </div>
</section>
<?php endif ?>

<!-- ═══ BERITA TERBARU ═══ -->
<?php if (!empty($berita)): ?>
<section class="section">
    <div class="container">
        <div class="section-header">
            <span class="section-label">Berita</span>
            <h2 class="section-title">Berita &amp; Informasi</h2>
            <p class="section-desc">Kabar terbaru seputar pengelolaan sampah, harga, dan program BSI Kota Mojokerto.</p>
        </div>
        <div class="pub-grid">
        <?php foreach ($berita as $b): ?>
            <div class="pub-card">
                <?php if ($b['foto']): ?>
                <img src="<?= BASE_URL ?>/uploads/berita/<?= e($b['foto']) ?>" class="pub-card-img" alt="<?= e($b['judul']) ?>">
                <?php else: ?>
                <div class="pub-card-img-placeholder">📰</div>
                <?php endif ?>
                <div class="pub-card-body">
                    <div class="pub-card-meta">
                        <?php if ($b['kategori']): ?><span class="badge badge-success"><?= e($b['kategori']) ?></span><?php endif ?>
                    </div>
                    <h3 class="pub-card-title">
                        <a href="<?= BASE_URL ?>/public/berita-detail.php?slug=<?= e($b['slug']) ?>"><?= e($b['judul']) ?></a>
                    </h3>
                    <p class="pub-card-desc"><?= e(truncate(strip_tags($b['isi'] ?? ''), 120)) ?></p>
                    <div class="pub-card-footer">
                        <a href="<?= BASE_URL ?>/public/berita-detail.php?slug=<?= e($b['slug']) ?>" class="btn btn-sm btn-secondary">Baca Selengkapnya →</a>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
        </div>
        <div style="text-align:center;margin-top:2rem;">
            <a href="<?= BASE_URL ?>/public/berita.php" class="btn btn-secondary">Lihat Semua Berita</a>
        </div>
    </div>
</section>
<?php endif ?>

<!-- ═══ CTA ═══ -->
<section class="section" style="background:linear-gradient(135deg,var(--color-primary-dark),var(--color-primary-mid));">
    <div class="container" style="text-align:center;">
        <div style="font-size:3rem;margin-bottom:1rem;">♻️</div>
        <h2 style="font-family:var(--font-display);font-size:clamp(1.6rem,3vw,2.2rem);color:white;margin-bottom:1rem;">
            Bergabunglah dalam Gerakan Daur Ulang
        </h2>
        <p style="color:rgba(255,255,255,.75);max-width:560px;margin:0 auto 2rem;line-height:1.7;">
            Temukan bank sampah unit terdekat di wilayah Anda dan mulai berkontribusi dalam pengelolaan sampah yang bertanggung jawab.
        </p>
        <div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
            <a href="<?= BASE_URL ?>/public/data-bank-sampah.php" class="btn btn-white btn-lg">🗺️ Temukan Bank Sampah Terdekat</a>
            <a href="<?= BASE_URL ?>/public/data-sampah.php" class="btn btn-outline-white btn-lg">Lihat Daftar Sampah</a>
        </div>
    </div>
</section>

<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
