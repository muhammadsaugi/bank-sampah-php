<?php
// ─── admin/rekap-sampah/proses.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/core/Validator.php';
require_once dirname(__DIR__, 2) . '/models/RekapModel.php';
require_once dirname(__DIR__, 2) . '/models/DetailRekapModel.php';
require_once dirname(__DIR__, 2) . '/models/HargaSampahModel.php';

Auth::cekSession();
CSRF::verify(Request::post('csrf_token'));

$aksi = Request::post('aksi');

match ($aksi) {
    'tambah' => prosesTambah(),
    'hapus'  => prosesHapus(),
    default  => Response::redirect('/admin/rekap-sampah/index.php', 'Aksi tidak dikenal.', 'error'),
};

function prosesTambah(): void
{
    Auth::cekRole(['admin_data', 'super_admin']);

    $idBank  = Request::int('id_bank_sampah');
    $tanggal = Request::str('tanggal');
    $catatan = Request::str('catatan');

    if ($idBank <= 0 || empty($tanggal)) {
        Response::redirect('/admin/rekap-sampah/tambah.php', 'Bank sampah dan tanggal wajib diisi.', 'error');
    }

    // Ambil array item dari POST
    $idNamaArr   = Request::postIntArray('id_nama_sampah');
    $beratArr    = Request::postFloatArray('berat');
    $hargaArr    = Request::postFloatArray('harga_satuan');
    $subtotalArr = Request::postFloatArray('subtotal');

    if (empty($idNamaArr)) {
        Response::redirect('/admin/rekap-sampah/tambah.php', 'Minimal satu item sampah harus diisi.', 'error');
    }

    // Bangun array item yang valid
    $items      = [];
    $totalBerat = 0;
    $totalHarga = 0;

    foreach ($idNamaArr as $idx => $idNama) {
        if ($idNama <= 0) continue;
        $berat     = $beratArr[$idx] ?? 0;
        $harga     = $hargaArr[$idx] ?? 0;
        $subtotal  = round($berat * $harga, 2);

        if ($berat <= 0) continue;

        $items[] = [
            'id_nama_sampah' => $idNama,
            'berat'          => $berat,
            'harga_satuan'   => $harga,
            'subtotal'       => $subtotal,
        ];
        $totalBerat += $berat;
        $totalHarga += $subtotal;
    }

    if (empty($items)) {
        Response::redirect('/admin/rekap-sampah/tambah.php', 'Tidak ada item valid yang diisi.', 'error');
    }

    Database::beginTransaction();
    try {
        $idRekap = rekap_tambah([
            'id_bank_sampah' => $idBank,
            'tanggal'        => $tanggal,
            'total_berat'    => round($totalBerat, 2),
            'total_harga'    => round($totalHarga, 2),
            'sumber_data'    => 'manual',
            'catatan'        => $catatan,
        ], Auth::getUserId());

        detail_tambahBulk($idRekap, $items);
        Database::commit();
    } catch (Throwable $e) {
        Database::rollback();
        Response::redirect('/admin/rekap-sampah/tambah.php', 'Gagal menyimpan: ' . $e->getMessage(), 'error');
    }

    Response::redirect('/admin/rekap-sampah/detail.php?id=' . $idRekap, 'Rekap sampah berhasil disimpan.', 'success');
}

function prosesHapus(): void
{
    Auth::cekRole(['admin_data', 'super_admin']);
    $id = Request::int('id');
    if ($id <= 0) Response::redirect('/admin/rekap-sampah/index.php', 'ID tidak valid.', 'error');

    Database::beginTransaction();
    try {
        detail_hapusByRekap($id); // CASCADE, tapi explicit untuk kejelasan
        rekap_hapus($id);
        Database::commit();
    } catch (Throwable $e) {
        Database::rollback();
        Response::redirect('/admin/rekap-sampah/index.php', 'Gagal menghapus rekap.', 'error');
    }

    Response::redirect('/admin/rekap-sampah/index.php', 'Rekap berhasil dihapus.', 'success');
}
