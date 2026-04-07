<?php
// ─── models/StrukturModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function struktur_getAll(): array {
    return Database::query("SELECT * FROM struktur_organisasi ORDER BY urutan ASC, id DESC");
}

function struktur_getAktif(): array {
    return Database::query("SELECT * FROM struktur_organisasi WHERE aktif = 1 ORDER BY urutan ASC");
}

function struktur_findById(int $id): array|false {
    return Database::queryOne("SELECT * FROM struktur_organisasi WHERE id = ?", [$id]);
}

function struktur_tambah(array $data): int {
    Database::execute("
        INSERT INTO struktur_organisasi (judul, gambar, periode, keterangan, urutan, aktif)
        VALUES (?, ?, ?, ?, ?, 1)
    ", [$data['judul'], $data['gambar'], $data['periode'] ?? '', $data['keterangan'] ?? '', $data['urutan'] ?? 0]);
    return Database::lastInsertId();
}

function struktur_edit(int $id, array $data): void {
    $fields = ['judul=?','periode=?','keterangan=?','urutan=?','aktif=?'];
    $params = [$data['judul'], $data['periode'] ?? '', $data['keterangan'] ?? '', $data['urutan'] ?? 0, $data['aktif'] ?? 1];
    if (!empty($data['gambar'])) { $fields[] = 'gambar=?'; $params[] = $data['gambar']; }
    $params[] = $id;
    Database::execute("UPDATE struktur_organisasi SET " . implode(',', $fields) . " WHERE id = ?", $params);
}

function struktur_hapus(int $id): void {
    Database::execute("DELETE FROM struktur_organisasi WHERE id = ?", [$id]);
}
