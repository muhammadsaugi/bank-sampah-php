<?php
// ─── admin/harga-sampah/index.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
 require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/HargaSampahModel.php';
require_once dirname(__DIR__, 2) . '/models/NamaSampahModel.php';

Auth::cekSession();
// Semua role bisa lihat; hanya operasional & super_admin bisa update
$canEdit = Auth::hasAnyRole(['admin_operasional', 'super_admin']);

$data           = harga_getAll();
$tanpaHarga     = $canEdit ? harga_getNamaTanpaHarga() : [];
$pageTitle      = 'Harga Sampah';
$sidebarActive  = 'harga-sampah';
$breadcrumb     = [['label' => 'Harga Sampah', 'url' => '']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div>
        <h1 class="page-title">Harga Sampah</h1>
        <p class="page-subtitle">Daftar harga terkini per item sampah</p>
    </div>
    <?php if ($canEdit && !empty($tanpaHarga)): ?>
    <span class="badge badge-warning">⚠️ <?= count($tanpaHarga) ?> item belum ada harga</span>
    <?php endif ?>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<?php if ($canEdit && !empty($tanpaHarga)): ?>
<div class="alert alert-warning">
    <span class="alert-icon">⚠️</span>
    <div class="alert-body">
        <strong><?= count($tanpaHarga) ?> item belum memiliki harga:</strong>
        <?= e(implode(', ', array_column($tanpaHarga, 'nama'))) ?>.
        Isi harga di tabel bawah menggunakan form inline.
    </div>
</div>
<?php endif ?>

<?php if ($canEdit): ?>
<!-- Form update harga massal -->
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header">
        <span class="card-title">💰 Update Harga Sampah</span>
        <small class="text-muted">Kosongkan kolom harga jika tidak ingin mengubah</small>
    </div>
    <form method="POST" action="proses.php">
        <?= CSRF::input() ?>
        <input type="hidden" name="aksi" value="update_massal">
        <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Item</th>
                        <th>Jenis</th>
                        <th>Satuan</th>
                        <th>Harga Saat Ini</th>
                        <th>Harga Baru (Rp)</th>
                        <th>Tgl Update</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                // Gabungkan item yang sudah ada harga + yang belum
                $semuaItem = nama_getAll();
                $hargaMap  = [];
                foreach ($data as $h) $hargaMap[$h['id_nama_sampah']] = $h;
                foreach ($semuaItem as $item):
                    $h = $hargaMap[$item['id']] ?? null;
                ?>
                <tr>
                    <td><strong><?= e($item['nama']) ?></strong></td>
                    <td><span class="badge" style="background:<?= e($item['warna_jenis']??'#ddd') ?>22;color:<?= e($item['warna_jenis']??'#666') ?>"><?= e($item['nama_jenis']) ?></span></td>
                    <td><?= e($item['satuan']) ?></td>
                    <td class="font-mono"><?= $h ? formatRupiah($h['harga']) : '<span class="text-muted">—</span>' ?></td>
                    <td>
                        <input type="number" name="harga[<?= (int)$item['id'] ?>]"
                               class="form-control" style="min-width:120px;"
                               min="0" step="50"
                               placeholder="<?= $h ? number_format((float)$h['harga'],0,'.','.') : '0' ?>"
                               value="">
                    </td>
                    <td><?= $h ? formatTanggal($h['tanggal_update']) : '-' ?></td>
                </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="form-card-footer" style="padding:1rem 1.25rem;">
            <button type="submit" class="btn btn-primary">💾 Simpan Semua Perubahan Harga</button>
        </div>
    </form>
</div>

<?php else: ?>
<!-- Hanya tampilkan tabel (read-only untuk admin_data) -->
<div class="table-wrapper">
    <table class="data-table">
        <thead>
            <tr><th>#</th><th>Nama Item</th><th>Jenis</th><th>Satuan</th><th>Harga</th><th>Tgl Update</th><th>Diupdate Oleh</th></tr>
        </thead>
        <tbody>
        <?php if (empty($data)): ?>
            <tr><td colspan="7" class="text-center text-muted" style="padding:2rem;">Belum ada data harga.</td></tr>
        <?php else: foreach ($data as $i => $row): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><strong><?= e($row['nama_sampah']) ?></strong></td>
                <td><span class="badge" style="background:<?= e($row['warna_jenis']??'#ddd') ?>22;color:<?= e($row['warna_jenis']??'#666') ?>"><?= e($row['nama_jenis']) ?></span></td>
                <td><?= e($row['satuan']) ?></td>
                <td class="font-mono"><strong><?= formatRupiah($row['harga']) ?></strong></td>
                <td><?= formatTanggal($row['tanggal_update']) ?></td>
                <td><?= e($row['diupdate_oleh_nama'] ?? '-') ?></td>
            </tr>
        <?php endforeach; endif ?>
        </tbody>
    </table>
</div>
<?php endif ?>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
