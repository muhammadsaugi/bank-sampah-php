<?php
// ─── models/BankSampahModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function bs_getAll(string $search = '', bool $aktifSaja = false): array {
    $sql = "SELECT b.*, u.nama AS nama_petugas FROM bank_sampah b LEFT JOIN users u ON b.dibuat_oleh = u.id WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (b.nama LIKE ? OR b.ketua LIKE ? OR b.kelurahan LIKE ?)";
        $like = "%$search%";
        $params = [$like, $like, $like];
    }
    if ($aktifSaja) { $sql .= " AND b.aktif = 1"; }
    $sql .= " ORDER BY b.nama ASC";
    return Database::query($sql, $params);
}

function bs_getAktif(): array {
    return Database::query("SELECT * FROM bank_sampah WHERE aktif = 1 ORDER BY nama ASC");
}

function bs_findById(int $id): array|false {
    return Database::queryOne("SELECT * FROM bank_sampah WHERE id = ?", [$id]);
}

function bs_tambah(array $data, int $userId): int {
    Database::execute("
        INSERT INTO bank_sampah (nama,alamat,kelurahan,kecamatan,ketua,kontak,tahun_berdiri,dibuat_oleh,aktif,created_at)
        VALUES (?,?,?,?,?,?,?,?,1,NOW())
    ", [$data['nama'], $data['alamat'], $data['kelurahan'] ?? '', $data['kecamatan'] ?? '',
        $data['ketua'], $data['kontak'] ?? '', $data['tahun_berdiri'] ?: null, $userId]);
    return Database::lastInsertId();
}

function bs_edit(int $id, array $data): void {
    Database::execute("
        UPDATE bank_sampah SET nama=?,alamat=?,kelurahan=?,kecamatan=?,ketua=?,kontak=?,tahun_berdiri=?,updated_at=NOW()
        WHERE id=?
    ", [$data['nama'], $data['alamat'], $data['kelurahan'] ?? '', $data['kecamatan'] ?? '',
        $data['ketua'], $data['kontak'] ?? '', $data['tahun_berdiri'] ?: null, $id]);
}

function bs_hapus(int $id): void {
    Database::execute("DELETE FROM bank_sampah WHERE id = ?", [$id]);
}

function bs_toggle(int $id): void {
    Database::execute("UPDATE bank_sampah SET aktif = NOT aktif WHERE id = ?", [$id]);
}

function bs_count(): int {
    $row = Database::queryOne("SELECT COUNT(*) as n FROM bank_sampah WHERE aktif = 1");
    return (int)($row['n'] ?? 0);
}
