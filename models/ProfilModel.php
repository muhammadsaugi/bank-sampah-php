<?php
// ─── models/ProfilModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function profil_get(): array {
    $row = Database::queryOne("SELECT * FROM profil_kelembagaan WHERE id = 1 LIMIT 1");
    return $row ?: ['id'=>1,'nama_lembaga'=>APP_NAME,'tagline'=>'','deskripsi'=>'','visi'=>'','misi'=>'','alamat'=>'','telepon'=>'','email'=>'','website'=>'','tahun_berdiri'=>'','logo'=>''];
}

function profil_update(array $data): void {
    $cols = ['nama_lembaga','tagline','deskripsi','visi','misi','alamat','telepon','email','website','tahun_berdiri'];
    if (!empty($data['logo'])) $cols[] = 'logo';
    $set    = implode(', ', array_map(fn($c) => "$c = ?", $cols));
    $values = array_map(fn($c) => $data[$c] ?? null, $cols);
    $exists = Database::queryOne("SELECT id FROM profil_kelembagaan WHERE id = 1");
    if ($exists) {
        Database::execute("UPDATE profil_kelembagaan SET $set WHERE id = 1", $values);
    } else {
        $colStr = 'id, ' . implode(', ', $cols);
        $placeholders = '1, ' . implode(', ', array_fill(0, count($cols), '?'));
        Database::execute("INSERT INTO profil_kelembagaan ($colStr) VALUES ($placeholders)", $values);
    }
}
