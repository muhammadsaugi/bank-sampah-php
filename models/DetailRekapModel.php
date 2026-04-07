<?php
// ─── models/DetailRekapModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function detail_getByRekap(int $idRekap): array {
    return Database::query("
        SELECT d.*, n.nama AS nama_sampah, n.satuan, j.nama AS nama_jenis, j.warna AS warna_jenis
        FROM detail_rekap_sampah d
        JOIN nama_sampah n ON d.id_nama_sampah = n.id
        JOIN jenis_sampah j ON n.id_jenis = j.id
        WHERE d.id_rekap = ?
        ORDER BY j.nama, n.nama
    ", [$idRekap]);
}

function detail_tambahBulk(int $idRekap, array $items): void {
    // $items = array of ['id_nama_sampah'=>int, 'berat'=>float, 'harga_satuan'=>float, 'subtotal'=>float]
    foreach ($items as $item) {
        Database::execute("
            INSERT INTO detail_rekap_sampah (id_rekap, id_nama_sampah, berat, harga_satuan, subtotal)
            VALUES (?, ?, ?, ?, ?)
        ", [
            $idRekap,
            (int)$item['id_nama_sampah'],
            (float)$item['berat'],
            (float)$item['harga_satuan'],
            (float)$item['subtotal'],
        ]);
    }
}

function detail_hapusByRekap(int $idRekap): void {
    Database::execute("DELETE FROM detail_rekap_sampah WHERE id_rekap = ?", [$idRekap]);
}
