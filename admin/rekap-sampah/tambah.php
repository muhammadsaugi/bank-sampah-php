<?php
// ─── admin/rekap-sampah/tambah.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/BankSampahModel.php';

Auth::cekSession();
Auth::cekRole(['admin_data', 'super_admin']);

$bankList      = bs_getAktif();
$pageTitle     = 'Input Rekap Sampah';
$sidebarActive = 'rekap-sampah';
$breadcrumb    = [
    ['label' => 'Rekap Sampah', 'url' => BASE_URL . '/admin/rekap-sampah/index.php'],
    ['label' => 'Input Rekap', 'url' => ''],
];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div><h1 class="page-title">Input Rekap Sampah</h1><p class="page-subtitle">Catat penyetoran sampah dari bank sampah unit</p></div>
    <a href="index.php" class="btn btn-outline">← Kembali</a>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<form method="POST" action="proses.php" id="form-rekap">
    <?= CSRF::input() ?>
    <input type="hidden" name="aksi" value="tambah">

    <!-- Header rekap -->
    <div class="form-card" style="margin-bottom:1.25rem;">
        <div class="form-card-header"><span class="form-card-title">📋 Informasi Rekap</span></div>
        <div class="form-card-body">
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Bank Sampah <span class="required">*</span></label>
                    <select name="id_bank_sampah" class="form-control" required>
                        <option value="">-- Pilih Bank Sampah --</option>
                        <?php foreach ($bankList as $b): ?>
                        <option value="<?= (int)$b['id'] ?>"><?= e($b['nama']) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal <span class="required">*</span></label>
                    <input type="date" name="tanggal" class="form-control" required value="<?= date('Y-m-d') ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <textarea name="catatan" class="form-control" rows="2" placeholder="Catatan tambahan (opsional)..."></textarea>
            </div>
        </div>
    </div>

    <!-- Tabel detail item -->
    <div class="form-card">
        <div class="form-card-header">
            <span class="form-card-title">⚖️ Detail Item Sampah</span>
            <button type="button" class="btn btn-sm btn-secondary" id="btn-tambah-baris">+ Tambah Baris</button>
        </div>
        <div style="overflow-x:auto;">
            <table class="rekap-table" id="tabel-rekap">
                <thead>
                    <tr>
                        <th style="width:35%">Nama Sampah</th>
                        <th style="width:15%">Berat (kg)</th>
                        <th style="width:20%">Harga/kg (Rp)</th>
                        <th style="width:20%">Subtotal (Rp)</th>
                        <th style="width:10%"></th>
                    </tr>
                </thead>
                <tbody id="tbody-rekap">
                    <!-- Baris awal dirender JS -->
                </tbody>
                <tfoot>
                    <tr class="rekap-total-row">
                        <td colspan="2" style="text-align:right;">TOTAL KESELURUHAN:</td>
                        <td></td>
                        <td id="grand-total">Rp 0</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="form-card-footer">
            <span class="text-muted" style="font-size:13px;">Minimal 1 baris item harus diisi.</span>
            <button type="submit" class="btn btn-primary" id="btn-submit">💾 Simpan Rekap</button>
        </div>
    </div>
</form>

<!-- Data JS lewat file eksternal (CSP memblokir skrip inline: script-src 'self') -->
<script src="<?= BASE_URL ?>/admin/rekap-sampah/rekap-form-data.js.php"></script>
<script src="<?= BASE_URL ?>/assets/js/rekap-form.js"></script>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
