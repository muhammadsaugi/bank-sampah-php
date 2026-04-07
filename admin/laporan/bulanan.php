<?php
// ─── admin/laporan/bulanan.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/models/LaporanModel.php';
require_once dirname(__DIR__, 2) . '/models/BankSampahModel.php';

Auth::cekSession();
Auth::cekRole(['admin_operasional', 'super_admin']);

$bulan    = Request::int('bulan', 'get') ?: (int)date('m');
$tahun    = Request::int('tahun', 'get') ?: (int)date('Y');
$idBank   = Request::int('bank', 'get');
$data     = laporan_getBulanan($bulan, $tahun, $idBank);
$grafik   = laporan_getStatistikBulanan($tahun);
$perJenis = laporan_getStatistikPerJenis($bulan, $tahun);
$bankList = bs_getAktif();
$tahunList = laporan_getTahunTersedia();

$namaBulan = [1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',5=>'Mei',6=>'Juni',
              7=>'Juli',8=>'Agustus',9=>'September',10=>'Oktober',11=>'November',12=>'Desember'];

$totalBerat = array_sum(array_column($data, 'total_berat'));
$totalHarga = array_sum(array_column($data, 'total_harga'));
$maxBerat   = max(array_merge([1], array_column($grafik, 'total_berat')));

$pageTitle     = 'Laporan Bulanan';
$sidebarActive = 'laporan-bulanan';
$breadcrumb    = [['label' => 'Laporan Bulanan', 'url' => '']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div><h1 class="page-title">Laporan Bulanan</h1><p class="page-subtitle">Rekapitulasi bulanan seluruh bank sampah</p></div>
</div>

<!-- Filter -->
<form method="GET" class="filter-bar">
    <label>Bulan:</label>
    <select name="bulan" class="form-control" style="width:130px;">
        <?php foreach ($namaBulan as $n => $nm): ?>
        <option value="<?= $n ?>" <?= $bulan==$n?'selected':'' ?>><?= $nm ?></option>
        <?php endforeach ?>
    </select>
    <label>Tahun:</label>
    <select name="tahun" class="form-control" style="width:100px;">
        <?php
        $tahunOptions = !empty($tahunList) ? array_column($tahunList,'tahun') : [date('Y')];
        foreach ($tahunOptions as $t): ?>
        <option value="<?= $t ?>" <?= $tahun==$t?'selected':'' ?>><?= $t ?></option>
        <?php endforeach ?>
    </select>
    <label>Bank:</label>
    <select name="bank" class="form-control" style="width:180px;">
        <option value="">Semua</option>
        <?php foreach ($bankList as $b): ?>
        <option value="<?= (int)$b['id'] ?>" <?= $idBank==$b['id']?'selected':'' ?>><?= e($b['nama']) ?></option>
        <?php endforeach ?>
    </select>
    <button type="submit" class="btn btn-secondary">🔍 Tampilkan</button>
</form>

<!-- Statistik ringkasan -->
<div style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
    <div class="stat-card" style="flex:1;padding:1rem 1.25rem;">
        <div class="stat-icon">📅</div>
        <div class="stat-content"><div class="stat-label">Periode</div><div style="font-weight:700;"><?= e($namaBulan[$bulan]) ?> <?= $tahun ?></div></div>
    </div>
    <div class="stat-card" style="flex:1;padding:1rem 1.25rem;">
        <div class="stat-icon">🏦</div>
        <div class="stat-content"><div class="stat-label">Bank Aktif</div><div class="stat-value" style="font-size:1.5rem;"><?= count($data) ?></div></div>
    </div>
    <div class="stat-card" style="flex:1;padding:1rem 1.25rem;">
        <div class="stat-icon">⚖️</div>
        <div class="stat-content"><div class="stat-label">Total Berat</div><div class="stat-value" style="font-size:1.3rem;"><?= number_format($totalBerat,2,',','.') ?> kg</div></div>
    </div>
    <div class="stat-card" style="flex:1;padding:1rem 1.25rem;">
        <div class="stat-icon">💰</div>
        <div class="stat-content"><div class="stat-label">Total Nilai</div><div class="stat-value" style="font-size:1.1rem;"><?= formatRupiah($totalHarga) ?></div></div>
    </div>
</div>

<!-- Grafik tren tahunan -->
<?php if (!empty($grafik)): ?>
<div class="chart-section" style="margin-bottom:1.5rem;">
    <div class="chart-title">📈 Tren Berat Sampah Tahun <?= $tahun ?> (per Bulan)</div>
    <div class="bar-chart-admin">
    <?php foreach ($grafik as $g):
        $pct = $maxBerat > 0 ? round((float)$g['total_berat'] / $maxBerat * 100, 1) : 0;
    ?>
        <div class="bar-col-admin">
            <div class="bar-val-admin"><?= number_format((float)$g['total_berat'],0,',','.') ?></div>
            <div class="bar-admin<?= (int)$g['bulan'] === (int)$bulan ? ' is-current-month' : '' ?>" style="--val:<?= $pct ?>;<?= (int)$g['bulan'] === (int)$bulan ? 'background:var(--color-primary-dark);' : '' ?>"></div>
            <div class="bar-label-admin"><?= substr($namaBulan[$g['bulan']]??'',0,3) ?></div>
        </div>
    <?php endforeach ?>
    </div>
</div>
<?php endif ?>

<!-- Tabel per bank sampah -->
<div class="table-wrapper" style="margin-bottom:1.5rem;">
    <table class="data-table">
        <thead><tr><th>#</th><th>Bank Sampah</th><th>Jumlah Transaksi</th><th>Total Berat</th><th>Total Nilai</th><th>% Kontribusi</th></tr></thead>
        <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="6" class="text-center text-muted" style="padding:2rem;">Tidak ada data pada periode ini.</td></tr>
        <?php else: foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><strong><?= e($row['nama_bank']) ?></strong></td>
                <td class="text-center"><?= e($row['jumlah_rekap']) ?></td>
                <td class="font-mono"><?= number_format((float)$row['total_berat'],2,',','.') ?> kg</td>
                <td class="font-mono"><?= formatRupiah($row['total_harga']) ?></td>
                <td>
                    <?php $pct = persen((float)$row['total_berat'], $totalBerat); ?>
                    <div style="display:flex;align-items:center;gap:.5rem;">
                        <div style="flex:1;height:6px;background:var(--color-border);border-radius:99px;overflow:hidden;">
                            <div style="width:<?= $pct ?>%;height:100%;background:var(--color-primary);border-radius:99px;"></div>
                        </div>
                        <span style="font-size:12px;font-weight:700;color:var(--color-primary-mid);"><?= $pct ?>%</span>
                    </div>
                </td>
            </tr>
        <?php endforeach; endif ?>
        </tbody>
    </table>
</div>

<!-- Per jenis sampah -->
<?php if (!empty($perJenis)): ?>
<div class="card">
    <div class="card-header"><span class="card-title">🗂️ Rincian per Jenis Sampah</span></div>
    <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
        <table class="data-table">
            <thead><tr><th>Jenis Sampah</th><th>Total Berat</th><th>Total Nilai</th><th>% Porsi</th></tr></thead>
            <tbody>
            <?php $totalBeratJenis = array_sum(array_column($perJenis,'total_berat')); ?>
            <?php foreach ($perJenis as $j): ?>
            <tr>
                <td><span class="badge" style="background:<?= e($j['warna']??'#ddd') ?>22;color:<?= e($j['warna']??'#666') ?>"><?= e($j['nama_jenis']) ?></span></td>
                <td class="font-mono"><?= number_format((float)$j['total_berat'],2,',','.') ?> kg</td>
                <td class="font-mono"><?= formatRupiah($j['total_harga']) ?></td>
                <td><?= persen((float)$j['total_berat'], $totalBeratJenis) ?>%</td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif ?>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
