<?php
// ─── models/LaporanModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function laporan_getHarian(string $tanggal, int $idBank = 0): array {
    $sql = "
        SELECT r.*, b.nama AS nama_bank,
               (SELECT COALESCE(SUM(berat),0) FROM detail_rekap_sampah WHERE id_rekap=r.id) AS total_berat_detail
        FROM rekap_sampah r
        JOIN bank_sampah b ON r.id_bank_sampah = b.id
        WHERE r.tanggal = ?
    ";
    $params = [$tanggal];
    if ($idBank > 0) { $sql .= " AND r.id_bank_sampah = ?"; $params[] = $idBank; }
    $sql .= " ORDER BY b.nama";
    return Database::query($sql, $params);
}

function laporan_getBulanan(int $bulan, int $tahun, int $idBank = 0): array {
    $sql = "
        SELECT
            r.id_bank_sampah,
            b.nama AS nama_bank,
            COUNT(r.id) AS jumlah_rekap,
            COALESCE(SUM(r.total_berat),0) AS total_berat,
            COALESCE(SUM(r.total_harga),0) AS total_harga
        FROM rekap_sampah r
        JOIN bank_sampah b ON r.id_bank_sampah = b.id
        WHERE MONTH(r.tanggal) = ? AND YEAR(r.tanggal) = ?
    ";
    $params = [$bulan, $tahun];
    if ($idBank > 0) { $sql .= " AND r.id_bank_sampah = ?"; $params[] = $idBank; }
    $sql .= " GROUP BY r.id_bank_sampah, b.nama ORDER BY total_berat DESC";
    return Database::query($sql, $params);
}

function laporan_getStatistikBulanan(int $tahun): array {
    return Database::query("
        SELECT
            MONTH(tanggal) AS bulan,
            MONTHNAME(MAX(tanggal)) AS nama_bulan,
            COUNT(id) AS jumlah_rekap,
            COALESCE(SUM(total_berat),0) AS total_berat,
            COALESCE(SUM(total_harga),0) AS total_harga
        FROM rekap_sampah
        WHERE YEAR(tanggal) = ?
        GROUP BY MONTH(tanggal)
        ORDER BY MONTH(tanggal)
    ", [$tahun]);
}

function laporan_getStatistikPerJenis(int $bulan = 0, int $tahun = 0): array {
    $sql = "
        SELECT
            j.nama AS nama_jenis, j.warna,
            COALESCE(SUM(d.berat),0) AS total_berat,
            COALESCE(SUM(d.subtotal),0) AS total_harga
        FROM detail_rekap_sampah d
        JOIN rekap_sampah r ON d.id_rekap = r.id
        JOIN nama_sampah n ON d.id_nama_sampah = n.id
        JOIN jenis_sampah j ON n.id_jenis = j.id
        WHERE 1=1
    ";
    $params = [];
    if ($bulan > 0) { $sql .= " AND MONTH(r.tanggal) = ?"; $params[] = $bulan; }
    if ($tahun > 0) { $sql .= " AND YEAR(r.tanggal) = ?"; $params[] = $tahun; }
    $sql .= " GROUP BY j.id ORDER BY total_berat DESC";
    return Database::query($sql, $params);
}

function laporan_getDetailHarian(string $tanggal): array {
    return Database::query("
        SELECT
            n.nama AS nama_sampah, j.nama AS nama_jenis, j.warna,
            COALESCE(SUM(d.berat),0) AS total_berat,
            AVG(d.harga_satuan) AS rata_harga,
            COALESCE(SUM(d.subtotal),0) AS total_harga
        FROM detail_rekap_sampah d
        JOIN rekap_sampah r ON d.id_rekap = r.id
        JOIN nama_sampah n ON d.id_nama_sampah = n.id
        JOIN jenis_sampah j ON n.id_jenis = j.id
        WHERE r.tanggal = ?
        GROUP BY n.id ORDER BY j.nama, n.nama
    ", [$tanggal]);
}

function laporan_getTahunTersedia(): array {
    return Database::query("SELECT DISTINCT YEAR(tanggal) AS tahun FROM rekap_sampah ORDER BY tahun DESC");
}

function laporan_getSummary6Bulan(): array {
    return Database::query("
        SELECT
            DATE_FORMAT(tanggal, '%Y-%m') AS periode,
            DATE_FORMAT(MAX(tanggal), '%b %Y') AS label,
            COALESCE(SUM(total_berat),0) AS total_berat,
            COALESCE(SUM(total_harga),0) AS total_harga
        FROM rekap_sampah
        WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(tanggal, '%Y-%m')
        ORDER BY periode ASC
    ");
}
