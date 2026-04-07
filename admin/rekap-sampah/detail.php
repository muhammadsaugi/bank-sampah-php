<?php
// ─── admin/rekap-sampah/detail.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/RekapModel.php';
require_once dirname(__DIR__, 2) . '/models/DetailRekapModel.php';

Auth::cekSession();

$id     = Request::int('id', 'get');
$rekap  = $id > 0 ? rekap_findById($id) : false;
if (!$rekap) Response::abort(404, 'Data rekap tidak ditemukan.');

$detail        = detail_getByRekap($id);
$pageTitle     = 'Detail Rekap';
$sidebarActive = 'rekap-sampah';
$breadcrumb    = [
    ['label' => 'Rekap Sampah', 'url' => BASE_URL . '/admin/rekap-sampah/index.php'],
    ['label' => 'Detail #' . $id, 'url' => ''],
];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Detail Rekap #<?= (int)$id ?></h1>
        <p class="page-subtitle"><?= e($rekap['nama_bank']) ?> — <?= formatTanggal($rekap['tanggal']) ?></p>
    </div>
    <div class="page-actions">
        <a href="index.php" class="btn btn-outline">← Kembali</a>
        <?php if (Auth::hasAnyRole(['admin_data','super_admin'])): ?>
        <form method="POST" action="proses.php" class="inline-form" onsubmit="return confirm('Hapus rekap ini beserta semua detailnya?')">
            <?php require_once dirname(__DIR__, 2) . '/core/CSRF.php' ?>
            <?= CSRF::input() ?>
            <input type="hidden" name="aksi" value="hapus">
            <input type="hidden" name="id" value="<?= (int)$id ?>">
            <button class="btn btn-danger">🗑️ Hapus Rekap</button>
        </form>
        <?php endif ?>
    </div>
</div>

<!-- Info Header -->
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-body">
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;flex-wrap:wrap;">
            <div>
                <div class="stat-label">Bank Sampah</div>
                <div style="font-weight:700;font-size:15px;margin-top:4px;"><?= e($rekap['nama_bank']) ?></div>
            </div>
            <div>
                <div class="stat-label">Tanggal</div>
                <div style="font-weight:700;font-size:15px;margin-top:4px;"><?= formatTanggal($rekap['tanggal'], 'd F Y') ?></div>
            </div>
            <div>
                <div class="stat-label">Sumber Data</div>
                <div style="margin-top:4px;">
                    <?php if ($rekap['sumber_data'] === 'manual'): ?>
                        <span class="badge badge-success">Manual</span>
                    <?php else: ?>
                        <span class="badge badge-info">Import <?= str_replace('import_','',$rekap['sumber_data']) ?></span>
                    <?php endif ?>
                </div>
            </div>
            <div>
                <div class="stat-label">Total Berat</div>
                <div class="font-mono" style="font-weight:700;font-size:1.2rem;color:var(--color-primary);margin-top:4px;"><?= number_format((float)$rekap['total_berat'],2,',','.') ?> kg</div>
            </div>
            <div>
                <div class="stat-label">Total Nilai</div>
                <div class="font-mono" style="font-weight:700;font-size:1.2rem;color:var(--color-primary);margin-top:4px;"><?= formatRupiah($rekap['total_harga']) ?></div>
            </div>
            <?php if (!empty($rekap['catatan'])): ?>
            <div>
                <div class="stat-label">Catatan</div>
                <div style="font-size:13.5px;margin-top:4px;"><?= e($rekap['catatan']) ?></div>
            </div>
            <?php endif ?>
        </div>
    </div>
</div>

<!-- Tabel detail -->
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th><th>Nama Sampah</th><th>Jenis</th><th>Berat</th>
                <th>Harga/Satuan</th><th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($detail)): ?>
            <tr><td colspan="6" class="text-center text-muted" style="padding:2rem;">Tidak ada detail item.</td></tr>
        <?php else: foreach ($detail as $i => $d): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><strong><?= e($d['nama_sampah']) ?></strong></td>
                <td><span class="badge" style="background:<?= e($d['warna_jenis']??'#ddd') ?>22;color:<?= e($d['warna_jenis']??'#666') ?>"><?= e($d['nama_jenis']) ?></span></td>
                <td class="font-mono"><?= number_format((float)$d['berat'],2,',','.') ?> <?= e($d['satuan']) ?></td>
                <td class="font-mono"><?= formatRupiah($d['harga_satuan']) ?></td>
                <td class="font-mono"><strong><?= formatRupiah($d['subtotal']) ?></strong></td>
            </tr>
        <?php endforeach; endif ?>
        </tbody>
        <tfoot>
            <tr style="background:var(--color-primary-pale);font-weight:700;">
                <td colspan="3" style="padding:.75rem 1rem;text-align:right;color:var(--color-primary-mid);">TOTAL</td>
                <td class="font-mono" style="padding:.75rem 1rem;"><?= number_format((float)$rekap['total_berat'],2,',','.') ?> kg</td>
                <td></td>
                <td class="font-mono" style="padding:.75rem 1rem;color:var(--color-primary-mid);"><?= formatRupiah($rekap['total_harga']) ?></td>
            </tr>
        </tfoot>
    </table>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
