<?php
// ─── config/database.php ───
// Memuat konstanta DB (dari app.php) dan meng-include core/Database.php.
// File ini hanya setup — koneksi aktual dilakukan lazily di Database::getInstance().

defined('DB_HOST') || require_once __DIR__ . '/app.php';
require_once dirname(__DIR__) . '/core/Database.php';
