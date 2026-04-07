<?php
// ─── public/rekapitulasi.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/core/Request.php';
require_once dirname(__DIR__) . '/models/RekapModel.php';
require_once dirname(__DIR__) . '/models/BankSampahModel.php';
require_once dirname(__DIR__) . '/models/LaporanModel.php';

$bulan   = Request::int('bulan', 'get') ?: (int)date('m');
$tahun   = Request::int('tahun', 'get') ?: (int)date('Y');
$idBank  = Request::int('bank',  'get');

$filter  = ['bulan' => $bulan, 'tahun' => $tahun];
if ($idBank > 0) $filter['id_bank_sampah'] = $idBank;

$data      = rekap_getAll($filter);
$grafik    = laporan_getStatistikBulanan($tahun);
$bankList  = bs_getAktif();
$tahunList = laporan_getTahunTersedia();

$totalBerat = array_sum(array_column($data, 'total_berat'));
$totalHarga = array_sum(array_column($data, 'total_harga'));
$maxBerat   = max(array_merge([1], array_column($grafik, 'total_berat')));

$namaBulan = [
    1=>'Januari',2=>'Februari',3=>'Maret',4=>'April',
    5=>'Mei',6=>'Juni',7=>'Juli',8=>'Agustus',
    9=>'September',10=>'Oktober',11=>'November',12=>'Desember',
];

$pageTitle = 'Rekapitulasi';
$navActive = 'rekap';

require_once dirname(__DIR__) . '/templates/public/header.php';
?>
<div class="page-banner"><div class="container">
    <h1>Rekapitulasi Sampah</h1>
    <nav class="breadcrumb-pub">
        <a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a>
        <span class="sep">›</span><span>Rekapitulasi</span>
    </nav>
</div></div>

<section class="section">
    <div class="container">

        <!-- Filter -->
        <form method="GET" style="display:flex;gap:.75rem;align-items:center;flex-wrap:wrap;padding:1rem 1.25rem;background:var(--color-surface);border-radius:var(--radius-md);border:1px solid var(--color-border);box-shadow:var(--shadow-sm);margin-bottom:2rem;">
            <label style="font-size:13px;font-weight:600;color:var(--color-text-muted);">Bulan:</label>
            <select name="bulan" class="form-control" style="width:130px;">
                <?php foreach ($namaBulan as $n => $nm): ?>
                <option value="<?= $n ?>" <?= $bulan==$n?'selected':'' ?>><?= $nm ?></option>
                <?php endforeach ?>
            </select>
            <label style="font-size:13px;font-weight:600;color:var(--color-text-muted);">Tahun:</label>
            <select name="tahun" class="form-control" style="width:100px;">
                <?php
                $tahunOpts = !empty($tahunList) ? array_column($tahunList,'tahun') : [date('Y')];
                foreach ($tahunOpts as $t): ?>
                <option value="<?= $t ?>" <?= $tahun==$t?'selected':'' ?>><?= $t ?></option>
                <?php endforeach ?>
            </select>
            <label style="font-size:13px;font-weight:600;color:var(--color-text-muted);">Bank Sampah:</label>
            <select name="bank" class="form-control" style="width:200px;">
                <option value="">Semua</option>
                <?php foreach ($bankList as $b): ?>
                <option value="<?= (int)$b['id'] ?>" <?= $idBank==$b['id']?'selected':'' ?>><?= e($b['nama']) ?></option>
                <?php endforeach ?>
            </select>
            <button type="submit" class="btn btn-primary">🔍 Tampilkan</button>
        </form>

        <!-- Ringkasan periode -->
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-bottom:2rem;">
            <div class="stat-pub-card">
                <div class="stat-pub-icon">📋</div>
                <div class="stat-pub-value"><?= count($data) ?></div>
                <div class="stat-pub-label">Transaksi — <?= e($namaBulan[$bulan]) ?> <?= $tahun ?></div>
            </div>
            <div class="stat-pub-card">
                <div class="stat-pub-icon">⚖️</div>
                <div class="stat-pub-value"><?= number_format($totalBerat,2,',','.') ?> kg</div>
                <div class="stat-pub-label">Total Berat Terkumpul</div>
            </div>
            <div class="stat-pub-card">
                <div class="stat-pub-icon">💰</div>
                <div class="stat-pub-value" style="font-size:1.3rem;"><?= formatRupiah($totalHarga) ?></div>
                <div class="stat-pub-label">Total Nilai Sampah</div>
            </div>
        </div>

        <!-- Grafik CSS Batang Tahunan -->
        <?php if (!empty($grafik)): ?>
        <div class="card" style="margin-bottom:2rem;padding:1.5rem;">
            <h3 style="font-size:14px;font-weight:700;color:var(--color-text);margin-bottom:1.25rem;">
                📈 Grafik Berat Sampah Tahun <?= $tahun ?>
            </h3>
            <div class="bar-chart">
                <?php foreach ($grafik as $g):
                    $pct = $maxBerat > 0 ? round((float)$g['total_berat'] / $maxBerat * 100, 1) : 0;
                ?>
                <div class="bar-col">
                    <div class="bar-val"><?= number_format((float)$g['total_berat'],0,',','.') ?></div>
                    <div class="bar" style="--val:<?= $pct ?>;<?= (int)$g['bulan']===$bulan ? 'background:var(--color-primary-dark);' : '' ?>"></div>
                    <div class="bar-label"><?= substr($namaBulan[(int)$g['bulan']]??'',0,3) ?></div>
                </div>
                <?php endforeach ?>
            </div>
        </div>
        <?php endif ?>

        <!-- Tabel rekap -->
        <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;">
            Data Rekap — <?= e($namaBulan[$bulan]) ?> <?= $tahun ?>
            <?php if ($idBank > 0): $b = bs_findById($idBank); ?> / <?= e($b['nama'] ?? '') ?><?php endif ?>
        </h3>

        <?php if (empty($data)): ?>
        <div class="alert alert-info">
            <span class="alert-icon">ℹ️</span>
            <div class="alert-body">Tidak ada data rekap pada periode <?= e($namaBulan[$bulan]) ?> <?= $tahun ?>.</div>
        </div>
        <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th><th>Tanggal</th><th>Bank Sampah</th>
                        <th>Total Berat</th><th>Total Nilai</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $i => $row): ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= formatTanggal($row['tanggal']) ?></td>
                    <td><strong><?= e($row['nama_bank']) ?></strong></td>
                    <td class="font-mono"><?= number_format((float)$row['total_berat'],2,',','.') ?> kg</td>
                    <td class="font-mono"><?= formatRupiah($row['total_harga']) ?></td>
                </tr>
                <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr style="background:var(--color-primary-pale);font-weight:700;">
                        <td colspan="3" style="padding:.75rem 1rem;text-align:right;color:var(--color-primary-mid);">TOTAL</td>
                        <td class="font-mono" style="padding:.75rem 1rem;"><?= number_format($totalBerat,2,',','.') ?> kg</td>
                        <td class="font-mono" style="padding:.75rem 1rem;color:var(--color-primary-mid);"><?= formatRupiah($totalHarga) ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php endif ?>

    </div>
</section>

<?php require_once dirname(__DIR__) . '/templates/public/footer.php' ?>
