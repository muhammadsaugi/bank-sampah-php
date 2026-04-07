<?php
// ─── admin/laporan/harian.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/models/LaporanModel.php';
require_once dirname(__DIR__, 2) . '/models/BankSampahModel.php';

Auth::cekSession();

$tanggal  = Request::get('tanggal', date('Y-m-d'));
$idBank   = Request::int('bank', 'get');
$data     = laporan_getHarian($tanggal, $idBank);
$detail   = laporan_getDetailHarian($tanggal);
$bankList = bs_getAktif();

$totalBerat = array_sum(array_column($data, 'total_berat'));
$totalHarga = array_sum(array_column($data, 'total_harga'));

$pageTitle     = 'Laporan Harian';
$sidebarActive = 'laporan-harian';
$breadcrumb    = [['label' => 'Laporan Harian', 'url' => '']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div><h1 class="page-title">Laporan Harian</h1><p class="page-subtitle">Rekap penyetoran sampah per hari</p></div>
</div>

<!-- Filter -->
<form method="GET" class="filter-bar">
    <label>Tanggal:</label>
    <input type="date" name="tanggal" class="form-control" style="width:175px;" value="<?= e($tanggal) ?>">
    <label>Bank Sampah:</label>
    <select name="bank" class="form-control" style="width:200px;">
        <option value="">Semua</option>
        <?php foreach ($bankList as $b): ?>
        <option value="<?= (int)$b['id'] ?>" <?= $idBank==$b['id']?'selected':'' ?>><?= e($b['nama']) ?></option>
        <?php endforeach ?>
    </select>
    <button type="submit" class="btn btn-secondary">🔍 Tampilkan</button>
</form>

<!-- Ringkasan -->
<div style="display:flex;gap:1rem;margin-bottom:1.5rem;flex-wrap:wrap;">
    <div class="stat-card" style="flex:1;min-width:160px;padding:1rem 1.25rem;">
        <div class="stat-icon">📅</div>
        <div class="stat-content"><div class="stat-label">Tanggal</div><div style="font-weight:700;font-size:15px;"><?= formatTanggal($tanggal,'d F Y') ?></div></div>
    </div>
    <div class="stat-card" style="flex:1;min-width:160px;padding:1rem 1.25rem;">
        <div class="stat-icon">📋</div>
        <div class="stat-content"><div class="stat-label">Transaksi</div><div class="stat-value" style="font-size:1.5rem;"><?= count($data) ?></div></div>
    </div>
    <div class="stat-card" style="flex:1;min-width:160px;padding:1rem 1.25rem;">
        <div class="stat-icon">⚖️</div>
        <div class="stat-content"><div class="stat-label">Total Berat</div><div class="stat-value" style="font-size:1.4rem;"><?= number_format($totalBerat,2,',','.') ?> kg</div></div>
    </div>
    <div class="stat-card" style="flex:1;min-width:160px;padding:1rem 1.25rem;">
        <div class="stat-icon">💰</div>
        <div class="stat-content"><div class="stat-label">Total Nilai</div><div class="stat-value" style="font-size:1.2rem;"><?= formatRupiah($totalHarga) ?></div></div>
    </div>
</div>

<!-- Tabel rekap per bank -->
<?php if (!empty($data)): ?>
<div class="table-wrapper" style="margin-bottom:1.5rem;">
    <table class="data-table">
        <thead><tr><th>#</th><th>Bank Sampah</th><th>Berat (kg)</th><th>Nilai (Rp)</th><th>Sumber</th></tr></thead>
        <tbody>
        <?php foreach ($data as $i => $row): ?>
        <tr>
            <td><?= $i+1 ?></td>
            <td><strong><?= e($row['nama_bank']) ?></strong></td>
            <td class="font-mono"><?= number_format((float)$row['total_berat'],2,',','.') ?></td>
            <td class="font-mono"><?= formatRupiah($row['total_harga']) ?></td>
            <td><span class="badge <?= $row['sumber_data']==='manual'?'badge-success':'badge-info' ?>"><?= e($row['sumber_data']) ?></span></td>
        </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?php else: ?>
<div class="alert alert-info"><span class="alert-icon">ℹ️</span><div class="alert-body">Tidak ada data rekap pada tanggal <?= formatTanggal($tanggal,'d F Y') ?>.</div></div>
<?php endif ?>

<!-- Detail per jenis sampah -->
<?php if (!empty($detail)): ?>
<div class="card">
    <div class="card-header"><span class="card-title">📊 Rincian per Jenis Sampah</span></div>
    <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
        <table class="data-table">
            <thead><tr><th>Nama Sampah</th><th>Jenis</th><th>Total Berat</th><th>Rata Harga</th><th>Total Nilai</th></tr></thead>
            <tbody>
            <?php foreach ($detail as $d): ?>
            <tr>
                <td><strong><?= e($d['nama_sampah']) ?></strong></td>
                <td><span class="badge" style="background:<?= e($d['warna']??'#ddd') ?>22;color:<?= e($d['warna']??'#666') ?>"><?= e($d['nama_jenis']) ?></span></td>
                <td class="font-mono"><?= number_format((float)$d['total_berat'],2,',','.') ?> kg</td>
                <td class="font-mono"><?= formatRupiah($d['rata_harga']) ?>/kg</td>
                <td class="font-mono"><strong><?= formatRupiah($d['total_harga']) ?></strong></td>
            </tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif ?>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
