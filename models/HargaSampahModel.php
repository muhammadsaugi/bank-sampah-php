<?php
// ─── models/HargaSampahModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function harga_getAll(): array {
    return Database::query("
        SELECT h.*, n.nama AS nama_sampah, n.satuan, j.nama AS nama_jenis, j.warna AS warna_jenis,
               u.nama AS diupdate_oleh_nama
        FROM harga_sampah h
        JOIN nama_sampah n ON h.id_nama_sampah = n.id
        JOIN jenis_sampah j ON n.id_jenis = j.id
        LEFT JOIN users u ON h.diupdate_oleh = u.id
        ORDER BY j.nama, n.nama
    ");
}

function harga_getByNama(int $idNamaSampah): array|false {
    return Database::queryOne("SELECT * FROM harga_sampah WHERE id_nama_sampah = ?", [$idNamaSampah]);
}

function harga_upsert(int $idNamaSampah, float $harga, int $userId): void {
    Database::execute("
        INSERT INTO harga_sampah (id_nama_sampah, harga, tanggal_update, diupdate_oleh)
        VALUES (?, ?, CURDATE(), ?)
        ON DUPLICATE KEY UPDATE harga = VALUES(harga), tanggal_update = VALUES(tanggal_update), diupdate_oleh = VALUES(diupdate_oleh)
    ", [$idNamaSampah, $harga, $userId]);
}

function harga_getJson(): array {
    $rows = Database::query("SELECT id_nama_sampah, harga FROM harga_sampah");
    $result = [];
    foreach ($rows as $row) {
        $result[$row['id_nama_sampah']] = (float)$row['harga'];
    }
    return $result;
}

function harga_getByIdNama(int $idNamaSampah): float {
    $row = Database::queryOne("SELECT harga FROM harga_sampah WHERE id_nama_sampah = ?", [$idNamaSampah]);
    return $row ? (float)$row['harga'] : 0.0;
}

function harga_getNamaTanpaHarga(): array {
    return Database::query("
        SELECT n.*, j.nama AS nama_jenis FROM nama_sampah n
        LEFT JOIN harga_sampah h ON h.id_nama_sampah = n.id
        LEFT JOIN jenis_sampah j ON n.id_jenis = j.id
        WHERE h.id IS NULL ORDER BY j.nama, n.nama
    ");
}
