<?php
// ─── models/UserModel.php ───
defined('DB_HOST') || require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/database.php';

function user_findByUsername(string $username): array|false {
    return Database::queryOne("SELECT * FROM users WHERE username = ? LIMIT 1", [$username]);
}

function user_findById(int $id): array|false {
    return Database::queryOne("SELECT * FROM users WHERE id = ? LIMIT 1", [$id]);
}

function user_getAll(): array {
    return Database::query("SELECT id, nama, username, email, role, aktif, last_login, created_at FROM users ORDER BY created_at DESC");
}

function user_create(array $data): int {
    Database::execute("
        INSERT INTO users (nama, username, email, password, role, dibuat_oleh, aktif)
        VALUES (?, ?, ?, ?, ?, ?, 1)
    ", [$data['nama'], $data['username'], $data['email'], $data['password'], $data['role'], $data['dibuat_oleh']]);
    return Database::lastInsertId();
}

function user_update(int $id, array $data): void {
    $fields = [];
    $params = [];
    foreach (['nama','email','role','aktif'] as $col) {
        if (array_key_exists($col, $data)) {
            $fields[] = "$col = ?";
            $params[] = $data[$col];
        }
    }
    if (!empty($data['password'])) {
        $fields[] = "password = ?";
        $params[] = $data['password'];
    }
    $params[] = $id;
    Database::execute("UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?", $params);
}

function user_delete(int $id): void {
    Database::execute("DELETE FROM users WHERE id = ?", [$id]);
}

function user_usernameExists(string $username, int $excludeId = 0): bool {
    $row = Database::queryOne("SELECT id FROM users WHERE username = ? AND id != ? LIMIT 1", [$username, $excludeId]);
    return $row !== false;
}

function user_emailExists(string $email, int $excludeId = 0): bool {
    $row = Database::queryOne("SELECT id FROM users WHERE email = ? AND id != ? LIMIT 1", [$email, $excludeId]);
    return $row !== false;
}

function user_updateLastLogin(int $id): void {
    Database::execute("UPDATE users SET last_login = NOW() WHERE id = ?", [$id]);
}

function user_toggleAktif(int $id): void {
    Database::execute("UPDATE users SET aktif = NOT aktif WHERE id = ?", [$id]);
}
