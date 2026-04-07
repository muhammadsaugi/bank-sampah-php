<?php
// ─── api/harga.php ───
// JSON endpoint: GET harga by id_nama_sampah
// Dipakai JS di form rekap — wajib autentikasi.

require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Auth.php';
require_once dirname(__DIR__) . '/core/Request.php';
require_once dirname(__DIR__) . '/core/Response.php';
require_once dirname(__DIR__) . '/models/HargaSampahModel.php';

// Hanya user yang sudah login
if (!Auth::isLoggedIn()) {
    Response::json(['error' => 'Unauthorized'], 401);
}

// Hanya GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    Response::json(['error' => 'Method not allowed'], 405);
}

$idNama = Request::int('id_nama', 'get');
if ($idNama <= 0) {
    Response::json(['error' => 'Parameter id_nama tidak valid'], 400);
}

$harga = harga_getByIdNama($idNama);
Response::json(['harga' => $harga, 'harga_format' => number_format($harga, 0, ',', '.')]);
