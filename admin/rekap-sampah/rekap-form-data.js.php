<?php
/**
 * Data untuk form rekap — diload sebagai skrip EKSTERNAL (bukan inline) agar lolos CSP script-src 'self'.
 * Harus dipanggil setelah user login dengan role yang sama seperti tambah.php.
 */
declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/models/NamaSampahModel.php';

header('Content-Type: application/javascript; charset=UTF-8');
header('Cache-Control: private, no-store');
header('X-Content-Type-Options: nosniff');

$baseJs = json_encode(BASE_URL, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS);
if ($baseJs === false) {
    $baseJs = '""';
}

if (!Auth::isLoggedIn() || !Auth::hasAnyRole(['admin_data', 'super_admin'])) {
    echo 'window.NAMA_SAMPAH_LIST=[];window.BASE_URL=' . $baseJs . ';';
    exit;
}

$namaList = nama_getAll();
$namaJson = json_encode(array_map(static function ($n) {
    return [
        'id'    => (int)($n['id'] ?? 0),
        'nama'  => (string)($n['nama'] ?? ''),
        'jenis' => (string)($n['nama_jenis'] ?? 'Tanpa jenis'),
        'harga' => (float)($n['harga_saat_ini'] ?? 0),
    ];
}, $namaList), JSON_UNESCAPED_UNICODE);

if ($namaJson === false) {
    $namaJson = '[]';
}

echo 'window.NAMA_SAMPAH_LIST=' . $namaJson . ';window.BASE_URL=' . $baseJs . ';';
