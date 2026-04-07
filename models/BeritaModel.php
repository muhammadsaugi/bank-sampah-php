<?php
// ─── models/BeritaModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function berita_getAll(string $search = '', string $status = ''): array {
    $sql = "SELECT * FROM berita WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (judul LIKE ? OR kategori LIKE ?)";
        $like = "%$search%";
        $params = [$like, $like];
    }
    if ($status) { $sql .= " AND status = ?"; $params[] = $status; }
    $sql .= " ORDER BY id DESC";
    return Database::query($sql, $params);
}

function berita_getPublish(int $limit = 9): array {
    return Database::query("SELECT * FROM berita WHERE status='publish' ORDER BY id DESC LIMIT ?", [$limit]);
}

function berita_findBySlug(string $slug): array|false {
    return Database::queryOne("SELECT * FROM berita WHERE slug = ? AND status='publish' LIMIT 1", [$slug]);
}

function berita_findById(int $id): array|false {
    return Database::queryOne("SELECT * FROM berita WHERE id = ?", [$id]);
}

function berita_tambah(array $data): int {
    Database::execute("
        INSERT INTO berita (judul, slug, isi, foto, kategori, tags, status)
        VALUES (?,?,?,?,?,?,?)
    ", [$data['judul'], $data['slug'], $data['isi'] ?? '', $data['foto'] ?? null,
        $data['kategori'] ?? '', $data['tags'] ?? '', $data['status'] ?? 'draft']);
    return Database::lastInsertId();
}

function berita_edit(int $id, array $data): void {
    $fields = ['judul=?','slug=?','isi=?','kategori=?','tags=?','status=?'];
    $params = [$data['judul'], $data['slug'], $data['isi'] ?? '', $data['kategori'] ?? '',
               $data['tags'] ?? '', $data['status']];
    if (!empty($data['foto'])) { $fields[] = 'foto=?'; $params[] = $data['foto']; }
    $params[] = $id;
    Database::execute("UPDATE berita SET " . implode(',', $fields) . " WHERE id=?", $params);
}

function berita_hapus(int $id): void {
    Database::execute("DELETE FROM berita WHERE id = ?", [$id]);
}

function berita_slugExists(string $slug, int $excludeId = 0): bool {
    $row = Database::queryOne("SELECT id FROM berita WHERE slug=? AND id!=?", [$slug, $excludeId]);
    return $row !== false;
}

function berita_getLainnya(int $excludeId, int $limit = 3): array {
    return Database::query("SELECT id,judul,slug,foto,kategori FROM berita WHERE status='publish' AND id!=? ORDER BY id DESC LIMIT ?", [$excludeId, $limit]);
}
