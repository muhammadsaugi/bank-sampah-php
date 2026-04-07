<?php
// ─── public/kegiatan.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/core/Request.php';
require_once dirname(__DIR__) . '/models/KegiatanModel.php';

$status    = Request::get('status', '');
$data      = kegiatan_getAll('', $status);
$pageTitle = 'Kegiatan';
$navActive = 'kegiatan';

require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1>Kegiatan</h1>
    <nav class="breadcrumb-pub">
        <a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a>
        <span class="sep">›</span><span>Kegiatan</span>
    </nav>
</div></div>

<section class="section">
    <div class="container">

        <!-- Filter status -->
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;margin-bottom:2rem;justify-content:center;">
            <?php
            $statusOpts = ['' => 'Semua', 'akan_datang' => 'Akan Datang', 'berlangsung' => 'Berlangsung', 'selesai' => 'Selesai'];
            foreach ($statusOpts as $val => $label):
            ?>
            <a href="?status=<?= e($val) ?>"
               class="btn <?= $status === $val ? 'btn-primary' : 'btn-outline' ?>">
                <?= $label ?>
            </a>
            <?php endforeach ?>
        </div>

        <?php if (empty($data)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">🎯</div>
            <h3>Belum ada kegiatan</h3>
            <p>Pantau terus halaman ini untuk informasi kegiatan terbaru.</p>
        </div>
        <?php else: ?>
        <div class="pub-grid">
        <?php foreach ($data as $k): ?>
            <div class="pub-card">
                <?php if ($k['foto']): ?>
                <img src="<?= BASE_URL ?>/uploads/kegiatan/<?= e($k['foto']) ?>"
                     alt="<?= e($k['judul']) ?>" class="pub-card-img">
                <?php else: ?>
                <div class="pub-card-img-placeholder">🎯</div>
                <?php endif ?>
                <div class="pub-card-body">
                    <div class="pub-card-meta">
                        <span>📅 <?= formatTanggal($k['tanggal']) ?></span>
                        <?php if ($k['tanggal_selesai'] && $k['tanggal_selesai'] !== $k['tanggal']): ?>
                        <span>— <?= formatTanggal($k['tanggal_selesai']) ?></span>
                        <?php endif ?>
                        <?= badgeStatus($k['status']) ?>
                    </div>
                    <h3 class="pub-card-title"><?= e($k['judul']) ?></h3>
                    <div class="pub-card-meta" style="margin-bottom:.5rem;">
                        <span class="badge badge-gray">🏷️ <?= e($k['jenis_kegiatan']) ?></span>
                        <?php if ($k['lokasi']): ?>
                        <span style="font-size:12px;color:var(--color-text-muted);">📍 <?= e($k['lokasi']) ?></span>
                        <?php endif ?>
                    </div>
                    <p class="pub-card-desc"><?= e(truncate($k['deskripsi'] ?? '', 140)) ?></p>
                </div>
            </div>
        <?php endforeach ?>
        </div>
        <?php endif ?>

    </div>
</section>

<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
