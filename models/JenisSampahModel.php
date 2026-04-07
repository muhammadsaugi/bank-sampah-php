<?php
// ─── models/JenisSampahModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function jenis_getAll(): array {
    return Database::query("SELECT j.*, u.nama AS nama_petugas, (SELECT COUNT(*) FROM nama_sampah WHERE id_jenis=j.id) AS jumlah_item FROM jenis_sampah j LEFT JOIN users u ON j.dibuat_oleh=u.id ORDER BY j.nama ASC");
}

function jenis_findById(int $id): array|false {
    return Database::queryOne("SELECT * FROM jenis_sampah WHERE id = ?", [$id]);
}

function jenis_tambah(array $data, int $userId): int {
    Database::execute("INSERT INTO jenis_sampah (nama,kode,warna,dibuat_oleh,created_at) VALUES (?,?,?,?,NOW())",
        [$data['nama'], $data['kode'] ?? '', $data['warna'] ?? '#16A34A', $userId]);
    return Database::lastInsertId();
}

function jenis_edit(int $id, array $data): void {
    Database::execute("UPDATE jenis_sampah SET nama=?,kode=?,warna=? WHERE id=?",
        [$data['nama'], $data['kode'] ?? '', $data['warna'] ?? '#16A34A', $id]);
}

function jenis_hapus(int $id): void {
    Database::execute("DELETE FROM jenis_sampah WHERE id = ?", [$id]);
}

function jenis_namaExists(string $nama, int $excludeId = 0): bool {
    $row = Database::queryOne("SELECT id FROM jenis_sampah WHERE nama = ? AND id != ?", [$nama, $excludeId]);
    return $row !== false;
}
