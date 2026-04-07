<?php
// ─── admin/harga-sampah/proses.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/HargaSampahModel.php';

Auth::cekSession();
Auth::cekRole(['admin_operasional', 'super_admin']);
CSRF::verify(Request::post('csrf_token'));

$aksi = Request::post('aksi');

if ($aksi === 'update_massal') {
    $hargaInput = $_POST['harga'] ?? [];
    if (!is_array($hargaInput)) {
        Response::redirect('/admin/harga-sampah/index.php', 'Data tidak valid.', 'error');
    }

    $userId  = Auth::getUserId();
    $updated = 0;

    Database::beginTransaction();
    try {
        foreach ($hargaInput as $idNama => $nilaiHarga) {
            $idNama    = (int)$idNama;
            $nilaiHarga = trim((string)$nilaiHarga);

            // Lewati jika kosong
            if ($nilaiHarga === '' || $idNama <= 0) continue;

            $harga = filter_var($nilaiHarga, FILTER_VALIDATE_FLOAT);
            if ($harga === false || $harga < 0) continue;

            harga_upsert($idNama, $harga, $userId);
            $updated++;
        }
        Database::commit();
    } catch (Throwable $e) {
        Database::rollback();
        Response::redirect('/admin/harga-sampah/index.php', 'Gagal menyimpan: ' . $e->getMessage(), 'error');
    }

    Response::redirect('/admin/harga-sampah/index.php', "$updated harga berhasil diperbarui.", 'success');
}

Response::redirect('/admin/harga-sampah/index.php', 'Aksi tidak dikenal.', 'error');
