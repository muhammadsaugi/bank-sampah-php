<?php
// ─── admin/rekap-sampah/import.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/RekapModel.php';
require_once dirname(__DIR__, 2) . '/models/DetailRekapModel.php';
require_once dirname(__DIR__, 2) . '/models/HargaSampahModel.php';
require_once dirname(__DIR__, 2) . '/models/BankSampahModel.php';
require_once dirname(__DIR__, 2) . '/models/NamaSampahModel.php';

Auth::cekSession();
Auth::cekRole(['admin_operasional', 'super_admin']);

$pageTitle     = 'Import CSV Rekap';
$sidebarActive = 'rekap-sampah';
$breadcrumb    = [
    ['label' => 'Rekap Sampah', 'url' => BASE_URL . '/admin/rekap-sampah/index.php'],
    ['label' => 'Import CSV', 'url' => ''],
];

$preview     = [];
$errors      = [];
$berhasil    = 0;
$gagal       = 0;
$diProses    = false;

// Handle upload & proses
if (Request::isPost()) {
    CSRF::verify(Request::post('csrf_token'));
    $aksi = Request::post('aksi');
    $file = $_FILES['csv_file'] ?? null;

    if ($aksi === 'preview' && $file && $file['error'] === UPLOAD_ERR_OK) {
        // Validasi MIME
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mime     = $finfo->file($file['tmp_name']);
        $allowed  = ['text/csv', 'text/plain', 'application/csv', 'application/vnd.ms-excel'];

        if (!in_array($mime, $allowed)) {
            $errors[] = 'File harus berformat CSV.';
        } else {
            $handle = fopen($file['tmp_name'], 'r');
            $baris  = 0;
            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $baris++;
                if ($baris === 1) continue; // skip header
                if ($baris > 6) break;      // preview 5 baris
                $preview[] = $row;
            }
            fclose($handle);
            // Simpan file tmp sementara di session untuk konfirmasi
            $tmpPath = sys_get_temp_dir() . '/bsi_import_' . session_id() . '.csv';
            move_uploaded_file($file['tmp_name'], $tmpPath);
            $_SESSION['import_csv_path'] = $tmpPath;
        }
    } elseif ($aksi === 'konfirmasi') {
        $tmpPath = $_SESSION['import_csv_path'] ?? '';
        if (!$tmpPath || !file_exists($tmpPath)) {
            $errors[] = 'File tidak ditemukan. Ulangi upload.';
        } else {
            $handle     = fopen($tmpPath, 'r');
            $baris      = 0;
            $detailBaris = [];

            Database::beginTransaction();
            try {
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    $baris++;
                    if ($baris === 1) continue; // header

                    [$idBank, $tanggal, $idNama, $berat] = array_pad(array_map('trim', $row), 4, '');

                    $idBank  = (int)$idBank;
                    $idNama  = (int)$idNama;
                    $berat   = (float)$berat;

                    if ($idBank <= 0 || empty($tanggal) || $idNama <= 0 || $berat <= 0) {
                        $errors[] = "Baris $baris: data tidak valid — dilewati.";
                        $gagal++;
                        continue;
                    }

                    // Verifikasi bank & nama sampah
                    $bank = bs_findById($idBank);
                    $nama = nama_findById($idNama);
                    if (!$bank || !$nama) {
                        $errors[] = "Baris $baris: ID bank/sampah tidak ditemukan — dilewati.";
                        $gagal++;
                        continue;
                    }

                    $harga    = harga_getByIdNama($idNama);
                    $subtotal = round($berat * $harga, 2);

                    // Buat rekap header baru untuk setiap baris
                    $idRekap = rekap_tambah([
                        'id_bank_sampah' => $idBank,
                        'tanggal'        => $tanggal,
                        'total_berat'    => $berat,
                        'total_harga'    => $subtotal,
                        'sumber_data'    => 'import_csv',
                        'catatan'        => 'Import CSV baris ' . $baris,
                    ], Auth::getUserId());

                    detail_tambahBulk($idRekap, [[
                        'id_nama_sampah' => $idNama,
                        'berat'          => $berat,
                        'harga_satuan'   => $harga,
                        'subtotal'       => $subtotal,
                    ]]);

                    $berhasil++;
                }
                fclose($handle);
                unlink($tmpPath);
                unset($_SESSION['import_csv_path']);
                Database::commit();
                $diProses = true;
            } catch (Throwable $e) {
                Database::rollback();
                fclose($handle);
                $errors[] = 'Terjadi error saat import: ' . $e->getMessage();
            }
        }
    }
}

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div><h1 class="page-title">Import CSV Rekap Sampah</h1><p class="page-subtitle">Upload file CSV untuk input rekap massal</p></div>
    <a href="index.php" class="btn btn-outline">← Kembali</a>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/flash.php' ?>

<?php if ($diProses): ?>
<div class="alert alert-<?= $berhasil > 0 ? 'success' : 'error' ?>">
    <span class="alert-icon"><?= $berhasil > 0 ? '✅' : '❌' ?></span>
    <div class="alert-body">
        Import selesai: <strong><?= $berhasil ?> baris berhasil</strong>, <?= $gagal ?> baris gagal.
        <?php if (!empty($errors)): ?>
        <ul style="margin-top:.5rem;padding-left:1.2rem;">
            <?php foreach ($errors as $e): ?><li><?= e($e) ?></li><?php endforeach ?>
        </ul>
        <?php endif ?>
    </div>
</div>
<a href="index.php" class="btn btn-primary">Lihat Daftar Rekap</a>

<?php else: ?>

<!-- Format panduan -->
<div class="alert alert-info" style="margin-bottom:1.5rem;">
    <span class="alert-icon">ℹ️</span>
    <div class="alert-body">
        <strong>Format CSV yang diterima:</strong><br>
        <code style="font-family:var(--font-mono);font-size:13px;">id_bank_sampah, tanggal (Y-m-d), id_nama_sampah, berat</code><br>
        Baris pertama dianggap header dan dilewati. Harga diambil otomatis dari tabel harga saat ini.
    </div>
</div>

<!-- Form upload -->
<div class="form-card" style="max-width:560px;margin-bottom:1.5rem;">
    <div class="form-card-header"><span class="form-card-title">📂 Upload File CSV</span></div>
    <form method="POST" enctype="multipart/form-data">
        <?= CSRF::input() ?>
        <input type="hidden" name="aksi" value="preview">
        <div class="form-card-body">
            <div class="form-group">
                <label class="form-label">File CSV <span class="required">*</span></label>
                <input type="file" name="csv_file" class="form-control" accept=".csv,text/csv" required>
                <p class="form-hint">Maksimal 5MB, format .csv</p>
            </div>
            <?php if (!empty($errors)): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $e): ?><div><?= e($e) ?></div><?php endforeach ?>
            </div>
            <?php endif ?>
        </div>
        <div class="form-card-footer">
            <button type="submit" class="btn btn-primary">👁 Preview Data</button>
        </div>
    </form>
</div>

<!-- Preview -->
<?php if (!empty($preview)): ?>
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header"><span class="card-title">👁 Preview 5 Baris Pertama</span></div>
    <div class="table-wrapper" style="border:none;border-radius:0;box-shadow:none;">
        <table class="data-table">
            <thead><tr><th>id_bank_sampah</th><th>tanggal</th><th>id_nama_sampah</th><th>berat</th></tr></thead>
            <tbody>
            <?php foreach ($preview as $row): ?>
                <tr><?php foreach (array_pad($row, 4, '') as $cell): ?><td><?= e($cell) ?></td><?php endforeach ?></tr>
            <?php endforeach ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        <form method="POST">
            <?= CSRF::input() ?>
            <input type="hidden" name="aksi" value="konfirmasi">
            <button type="submit" class="btn btn-primary" onclick="return confirm('Proses import semua data CSV?')">✅ Konfirmasi &amp; Import</button>
            <a href="import.php" class="btn btn-outline">Batalkan</a>
        </form>
    </div>
</div>
<?php endif ?>

<?php endif ?>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>
