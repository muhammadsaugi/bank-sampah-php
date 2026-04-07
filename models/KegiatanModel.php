<?php
// ─── models/KegiatanModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function kegiatan_getAll(string $search = '', string $status = ''): array {
    $sql = "SELECT * FROM kegiatan WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (judul LIKE ? OR lokasi LIKE ?)";
        $like = "%$search%";
        $params = [$like, $like];
    }
    if ($status) { $sql .= " AND status = ?"; $params[] = $status; }
    $sql .= " ORDER BY tanggal DESC";
    return Database::query($sql, $params);
}

function kegiatan_getPublik(int $limit = 6): array {
    return Database::query("SELECT * FROM kegiatan ORDER BY tanggal DESC LIMIT ?", [$limit]);
}

function kegiatan_findById(int $id): array|false {
    return Database::queryOne("SELECT * FROM kegiatan WHERE id = ?", [$id]);
}

function kegiatan_tambah(array $data): int {
    Database::execute("
        INSERT INTO kegiatan (judul, jenis_kegiatan, tanggal, tanggal_selesai, lokasi, deskripsi, foto, status)
        VALUES (?,?,?,?,?,?,?,?)
    ", [$data['judul'], $data['jenis_kegiatan'], $data['tanggal'], $data['tanggal_selesai'] ?: null,
        $data['lokasi'] ?? '', $data['deskripsi'] ?? '', $data['foto'] ?? null, $data['status'] ?? 'akan_datang']);
    return Database::lastInsertId();
}

function kegiatan_edit(int $id, array $data): void {
    $fields = ['judul=?','jenis_kegiatan=?','tanggal=?','tanggal_selesai=?','lokasi=?','deskripsi=?','status=?'];
    $params = [$data['judul'], $data['jenis_kegiatan'], $data['tanggal'], $data['tanggal_selesai'] ?: null,
               $data['lokasi'] ?? '', $data['deskripsi'] ?? '', $data['status']];
    if (!empty($data['foto'])) { $fields[] = 'foto=?'; $params[] = $data['foto']; }
    $params[] = $id;
    Database::execute("UPDATE kegiatan SET " . implode(',', $fields) . " WHERE id=?", $params);
}

function kegiatan_hapus(int $id): void {
    Database::execute("DELETE FROM kegiatan WHERE id = ?", [$id]);
}
