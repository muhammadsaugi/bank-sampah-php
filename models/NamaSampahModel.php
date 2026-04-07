<?php
// ─── models/NamaSampahModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function nama_getAll(): array {
    return Database::query("
        SELECT n.*, j.nama AS nama_jenis, j.warna AS warna_jenis,
               COALESCE(h.harga, 0) AS harga_saat_ini
        FROM nama_sampah n
        LEFT JOIN jenis_sampah j ON n.id_jenis = j.id
        LEFT JOIN harga_sampah h ON h.id_nama_sampah = n.id
        ORDER BY j.nama, n.nama
    ");
}

function nama_getByJenis(int $idJenis): array {
    return Database::query("
        SELECT n.*, COALESCE(h.harga, 0) AS harga_saat_ini
        FROM nama_sampah n LEFT JOIN harga_sampah h ON h.id_nama_sampah = n.id
        WHERE n.id_jenis = ? ORDER BY n.nama
    ", [$idJenis]);
}

function nama_findById(int $id): array|false {
    return Database::queryOne("
        SELECT n.*, j.nama AS nama_jenis FROM nama_sampah n
        LEFT JOIN jenis_sampah j ON n.id_jenis = j.id WHERE n.id = ?
    ", [$id]);
}

function nama_tambah(array $data, int $userId): int {
    Database::execute("
        INSERT INTO nama_sampah (id_jenis,nama,satuan,keterangan,dibuat_oleh,created_at)
        VALUES (?,?,?,?,?,NOW())
    ", [$data['id_jenis'], $data['nama'], $data['satuan'] ?? 'Kg', $data['keterangan'] ?? '', $userId]);
    return Database::lastInsertId();
}

function nama_edit(int $id, array $data): void {
    Database::execute("UPDATE nama_sampah SET id_jenis=?,nama=?,satuan=?,keterangan=? WHERE id=?",
        [$data['id_jenis'], $data['nama'], $data['satuan'] ?? 'Kg', $data['keterangan'] ?? '', $id]);
}

function nama_hapus(int $id): void {
    Database::execute("DELETE FROM nama_sampah WHERE id = ?", [$id]);
}

function nama_count(): int {
    $row = Database::queryOne("SELECT COUNT(*) AS n FROM nama_sampah");
    return (int)($row['n'] ?? 0);
}
