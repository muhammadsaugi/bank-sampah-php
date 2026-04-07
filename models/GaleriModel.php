<?php
// ─── models/GaleriModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function galeri_getAll(string $kategori = ''): array {
    $sql = "SELECT * FROM galeri WHERE 1=1";
    $params = [];
    if ($kategori) { $sql .= " AND kategori = ?"; $params[] = $kategori; }
    $sql .= " ORDER BY tanggal DESC, id DESC";
    return Database::query($sql, $params);
}

function galeri_getByKategori(string $kategori): array {
    return Database::query("SELECT * FROM galeri WHERE kategori=? ORDER BY tanggal DESC, id DESC", [$kategori]);
}

function galeri_findById(int $id): array|false {
    return Database::queryOne("SELECT * FROM galeri WHERE id=?", [$id]);
}

function galeri_tambah(array $data): int {
    Database::execute("
        INSERT INTO galeri (judul, deskripsi, gambar, tanggal, kategori)
        VALUES (?,?,?,?,?)
    ", [$data['judul'], $data['deskripsi'] ?? '', $data['gambar'],
        $data['tanggal'] ?: date('Y-m-d'), $data['kategori'] ?? 'Kegiatan']);
    return Database::lastInsertId();
}

function galeri_edit(int $id, array $data): void {
    $fields = ['judul=?','deskripsi=?','tanggal=?','kategori=?'];
    $params = [$data['judul'], $data['deskripsi'] ?? '', $data['tanggal'] ?: date('Y-m-d'), $data['kategori']];
    if (!empty($data['gambar'])) { $fields[] = 'gambar=?'; $params[] = $data['gambar']; }
    $params[] = $id;
    Database::execute("UPDATE galeri SET " . implode(',', $fields) . " WHERE id=?", $params);
}

function galeri_hapus(int $id): void {
    Database::execute("DELETE FROM galeri WHERE id=?", [$id]);
}

function galeri_getKategoriList(): array {
    return ['Kunjungan','Edukasi','Produk Daur Ulang','Kegiatan'];
}
