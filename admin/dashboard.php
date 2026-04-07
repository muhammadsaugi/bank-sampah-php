<?php
// ─── admin/dashboard.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Auth.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/core/Response.php';
require_once dirname(__DIR__) . '/models/BankSampahModel.php';
require_once dirname(__DIR__) . '/models/NamaSampahModel.php';
require_once dirname(__DIR__) . '/models/RekapModel.php';
require_once dirname(__DIR__) . '/models/LaporanModel.php';

Auth::cekSession();
Auth::cekRole(['super_admin', 'admin_data', 'admin_operasional']);

$pageTitle    = 'Dashboard';
$sidebarActive = 'dashboard';
$breadcrumb   = [];

// Statistik
$jumlahBank    = bs_count();
$jumlahItem    = nama_count();
$jumlahRekap   = rekap_count();
$totalBerat    = rekap_totalBerat();
$totalHarga    = rekap_totalHarga();
$rekap6Bulan   = laporan_getSummary6Bulan();
$rekapTerbaru  = rekap_getAll(['limit' => 8]);

// Hitung persentase bar chart
$maxBerat = 0;
foreach ($rekap6Bulan as $r) {
    if ((float)$r['total_berat'] > $maxBerat) $maxBerat = (float)$r['total_berat'];
}

require_once dirname(__DIR__) . '/templates/admin/header.php';
require_once dirname(__DIR__) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__) . '/templates/admin/topbar.php';
?>

<!-- STAT CARDS -->
<div class="dashboard-stats">
    <div class="stat-card">
        <div class="stat-icon">🏦</div>
        <div class="stat-content">
            <div class="stat-label">Bank Sampah Aktif</div>
            <div class="stat-value"><?= e($jumlahBank) ?></div>
            <div class="stat-sub">Unit terdaftar &amp; aktif</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🏷️</div>
        <div class="stat-content">
            <div class="stat-label">Jenis Item Sampah</div>
            <div class="stat-value"><?= e($jumlahItem) ?></div>
            <div class="stat-sub">Total item dalam sistem</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">⚖️</div>
        <div class="stat-content">
            <div class="stat-label">Total Berat Terkumpul</div>
            <div class="stat-value"><?= number_format($totalBerat, 0, ',', '.') ?></div>
            <div class="stat-sub">Kilogram seluruh waktu</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">💰</div>
        <div class="stat-content">
            <div class="stat-label">Total Nilai Sampah</div>
            <div class="stat-value" style="font-size:1.3rem;"><?= formatRupiah($totalHarga) ?></div>
            <div class="stat-sub">Akumulasi seluruh waktu</div>
        </div>
    </div>
</div>

<!-- GRAFIK 6 BULAN -->
<?php if (!empty($rekap6Bulan)): ?>
<div class="chart-section" style="margin-bottom:1.75rem;">
    <div class="chart-title">📈 Rekap Berat Sampah — 6 Bulan Terakhir</div>
    <div class="bar-chart-admin">
        <?php foreach ($rekap6Bulan as $r):
            $val = $maxBerat > 0 ? round((float)$r['total_berat'] / $maxBerat * 100, 1) : 0;
        ?>
        <div class="bar-col-admin">
            <div class="bar-val-admin"><?= number_format((float)$r['total_berat'], 0, ',', '.') ?> kg</div>
            <div class="bar-admin" style="--val:<?= $val ?>"></div>
            <div class="bar-label-admin"><?= e($r['label']) ?></div>
        </div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>

<!-- REKAP TERBARU -->
<div class="card">
    <div class="card-header">
        <span class="card-title">📋 Rekap Sampah Terbaru</span>
        <a href="<?= BASE_URL ?>/admin/rekap-sampah/index.php" class="btn btn-sm btn-secondary">Lihat Semua</a>
    </div>
    <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
        <table class="data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Bank Sampah</th>
                    <th>Tanggal</th>
                    <th>Total Berat</th>
                    <th>Total Harga</th>
                    <th>Sumber</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rekapTerbaru)): ?>
                <tr><td colspan="6" class="text-center text-muted" style="padding:2rem;">Belum ada data rekap.</td></tr>
                <?php else: ?>
                <?php foreach ($rekapTerbaru as $i => $r): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><strong><?= e($r['nama_bank']) ?></strong></td>
                    <td><?= formatTanggal($r['tanggal']) ?></td>
                    <td class="font-mono"><?= number_format((float)$r['total_berat'], 2, ',', '.') ?> kg</td>
                    <td class="font-mono"><?= formatRupiah($r['total_harga']) ?></td>
                    <td><?= $r['sumber_data'] === 'manual' ? '<span class="badge badge-success">Manual</span>' : '<span class="badge badge-info">Import</span>' ?></td>
                </tr>
                <?php endforeach ?>
                <?php endif ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once dirname(__DIR__) . '/templates/admin/footer.php' ?>
