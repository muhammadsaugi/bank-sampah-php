<?php
// ─── models/RekapModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function rekap_getAll(array $filter = []): array {
    $sql = "
        SELECT r.*, b.nama AS nama_bank, u.nama AS nama_user
        FROM rekap_sampah r
        JOIN bank_sampah b ON r.id_bank_sampah = b.id
        LEFT JOIN users u ON r.id_user = u.id
        WHERE 1=1
    ";
    $params = [];
    if (!empty($filter['id_bank_sampah'])) {
        $sql .= " AND r.id_bank_sampah = ?"; $params[] = $filter['id_bank_sampah'];
    }
    if (!empty($filter['tanggal_dari'])) {
        $sql .= " AND r.tanggal >= ?"; $params[] = $filter['tanggal_dari'];
    }
    if (!empty($filter['tanggal_sampai'])) {
        $sql .= " AND r.tanggal <= ?"; $params[] = $filter['tanggal_sampai'];
    }
    if (!empty($filter['bulan'])) {
        $sql .= " AND MONTH(r.tanggal) = ?"; $params[] = $filter['bulan'];
    }
    if (!empty($filter['tahun'])) {
        $sql .= " AND YEAR(r.tanggal) = ?"; $params[] = $filter['tahun'];
    }
    $sql .= " ORDER BY r.tanggal DESC, r.id DESC";
    if (!empty($filter['limit'])) {
        $sql .= " LIMIT " . (int)$filter['limit'];
    }
    return Database::query($sql, $params);
}

function rekap_findById(int $id): array|false {
    return Database::queryOne("
        SELECT r.*, b.nama AS nama_bank FROM rekap_sampah r
        JOIN bank_sampah b ON r.id_bank_sampah = b.id WHERE r.id = ?
    ", [$id]);
}

function rekap_tambah(array $data, int $userId): int {
    Database::execute("
        INSERT INTO rekap_sampah (id_bank_sampah, id_user, tanggal, total_berat, total_harga, sumber_data, catatan, created_at)
        VALUES (?,?,?,?,?,?,?,NOW())
    ", [$data['id_bank_sampah'], $userId, $data['tanggal'], $data['total_berat'] ?? 0,
        $data['total_harga'] ?? 0, $data['sumber_data'] ?? 'manual', $data['catatan'] ?? '']);
    return Database::lastInsertId();
}

function rekap_hapus(int $id): void {
    Database::execute("DELETE FROM rekap_sampah WHERE id = ?", [$id]);
}

function rekap_updateTotal(int $id): void {
    Database::execute("
        UPDATE rekap_sampah r SET
            total_berat = (SELECT COALESCE(SUM(berat),0) FROM detail_rekap_sampah WHERE id_rekap = r.id),
            total_harga = (SELECT COALESCE(SUM(subtotal),0) FROM detail_rekap_sampah WHERE id_rekap = r.id)
        WHERE r.id = ?
    ", [$id]);
}

function rekap_count(): int {
    $row = Database::queryOne("SELECT COUNT(*) AS n FROM rekap_sampah");
    return (int)($row['n'] ?? 0);
}

function rekap_totalBerat(): float {
    $row = Database::queryOne("SELECT COALESCE(SUM(total_berat),0) AS n FROM rekap_sampah");
    return (float)($row['n'] ?? 0);
}

function rekap_totalHarga(): float {
    $row = Database::queryOne("SELECT COALESCE(SUM(total_harga),0) AS n FROM rekap_sampah");
    return (float)($row['n'] ?? 0);
}
