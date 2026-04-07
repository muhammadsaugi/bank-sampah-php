<?php
// ─── admin/rekap-sampah/index.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/RekapModel.php';
require_once dirname(__DIR__, 2) . '/models/BankSampahModel.php';

Auth::cekSession();

$filter = [
    'id_bank_sampah'  => Request::int('bank', 'get'),
    'tanggal_dari'    => Request::get('dari', ''),
    'tanggal_sampai'  => Request::get('sampai', ''),
];
// Default bulan ini jika tidak ada filter tanggal
if (empty($filter['tanggal_dari']) && empty($filter['tanggal_sampai'])) {
    $filter['tanggal_dari']  = date('Y-m-01');
    $filter['tanggal_sampai'] = date('Y-m-t');
}

$data          = rekap_getAll($filter);
$bankList      = bs_getAktif();
$canInput      = Auth::hasAnyRole(['admin_data', 'super_admin']);
$canImport     = Auth::hasAnyRole(['admin_operasional', 'super_admin']);
$canHapus      = Auth::hasAnyRole(['admin_data', 'super_admin']);
$pageTitle     = 'Rekap Sampah';
$sidebarActive = 'rekap-sampah';
$breadcrumb    = [['label' => 'Rekap Sampah', 'url' => '']];
$totalBerat    = array_sum(array_column($data, 'total_berat'));
$totalHarga    = array_sum(array_column($data, 'total_harga'));

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Rekap Sampah</h1>
        <p class="page-subtitle">Data penyetoran sampah dari bank sampah unit</p>
    </div>
    <div class="page-actions">
        <?php if ($canImport): ?>
        <a href="import.php" class="btn btn-outline">📥 Import CSV</a>
        <?php endif ?>
        <?php if ($canInput): ?>
        <a href="tambah.php" class="btn btn-primary">+ Input Rekap</a>
        <?php endif ?>
    </div>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<!-- Filter -->
<form method="GET" class="filter-bar">
    <label>Bank Sampah:</label>
    <select name="bank" class="form-control" style="width:200px;">
        <option value="">Semua</option>
        <?php foreach ($bankList as $b): ?>
        <option value="<?= (int)$b['id'] ?>" <?= $filter['id_bank_sampah']==$b['id']?'selected':'' ?>><?= e($b['nama']) ?></option>
        <?php endforeach ?>
    </select>
    <label>Dari:</label>
    <input type="date" name="dari" class="form-control" style="width:160px;" value="<?= e($filter['tanggal_dari']) ?>">
    <label>Sampai:</label>
    <input type="date" name="sampai" class="form-control" style="width:160px;" value="<?= e($filter['tanggal_sampai']) ?>">
    <button type="submit" class="btn btn-secondary">🔍 Filter</button>
    <a href="index.php" class="btn btn-outline">Reset</a>
</form>

<!-- Ringkasan -->
<?php if (!empty($data)): ?>
<div style="display:flex;gap:1rem;margin-bottom:1.25rem;flex-wrap:wrap;">
    <div class="stat-card" style="flex:1;min-width:180px;padding:1rem 1.25rem;">
        <div class="stat-icon" style="font-size:1.2rem;width:40px;height:40px;">📋</div>
        <div class="stat-content">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value" style="font-size:1.4rem;"><?= count($data) ?></div>
        </div>
    </div>
    <div class="stat-card" style="flex:1;min-width:180px;padding:1rem 1.25rem;">
        <div class="stat-icon" style="font-size:1.2rem;width:40px;height:40px;">⚖️</div>
        <div class="stat-content">
            <div class="stat-label">Total Berat</div>
            <div class="stat-value" style="font-size:1.4rem;"><?= number_format($totalBerat,2,',','.') ?> kg</div>
        </div>
    </div>
    <div class="stat-card" style="flex:1;min-width:180px;padding:1rem 1.25rem;">
        <div class="stat-icon" style="font-size:1.2rem;width:40px;height:40px;">💰</div>
        <div class="stat-content">
            <div class="stat-label">Total Nilai</div>
            <div class="stat-value" style="font-size:1.2rem;"><?= formatRupiah($totalHarga) ?></div>
        </div>
    </div>
</div>
<?php endif ?>

<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th><th>Bank Sampah</th><th>Tanggal</th>
                <th>Total Berat</th><th>Total Harga</th>
                <th>Sumber</th><th>Catatan</th><th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="8"><div class="empty-state"><div class="empty-state-icon">📋</div><h3>Tidak ada data rekap</h3><p>Sesuaikan filter atau tambah rekap baru.</p></div></td></tr>
        <?php else: foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><strong><?= e($row['nama_bank']) ?></strong></td>
                <td><?= formatTanggal($row['tanggal']) ?></td>
                <td class="font-mono"><?= number_format((float)$row['total_berat'],2,',','.') ?> kg</td>
                <td class="font-mono"><?= formatRupiah($row['total_harga']) ?></td>
                <td>
                    <?php if ($row['sumber_data'] === 'manual'): ?>
                        <span class="badge badge-success">Manual</span>
                    <?php elseif ($row['sumber_data'] === 'import_csv'): ?>
                        <span class="badge badge-info">CSV</span>
                    <?php else: ?>
                        <span class="badge badge-info">Excel</span>
                    <?php endif ?>
                </td>
                <td><?= e(truncate($row['catatan'] ?? '', 40)) ?></td>
                <td class="action-cell">
                    <a href="detail.php?id=<?= (int)$row['id'] ?>" class="btn btn-sm btn-secondary">👁 Detail</a>
                    <?php if ($canHapus): ?>
                    <form method="POST" action="proses.php" class="inline-form" onsubmit="return confirm('Hapus rekap ini beserta semua detailnya?')">
                        <?= CSRF::input() ?>
                        <input type="hidden" name="aksi" value="hapus">
                        <input type="hidden" name="id" value="<?= (int)$row['id'] ?>">
                        <button class="btn btn-sm btn-danger">🗑️</button>
                    </form>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach; endif ?>
        </tbody>
    </table>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
