<?php
// ─── config/session.php ───
// Konfigurasi session secure. Harus di-include sebelum session_start().

defined('APP_NAME') || require_once __DIR__ . '/app.php';

if (session_status() === PHP_SESSION_NONE) {
    // cookie_secure = 0 agar bisa berjalan di localhost / HTTP
    ini_set('session.cookie_httponly', '1');
    ini_set('session.cookie_samesite', 'Lax');   // Lax lebih kompatibel dari Strict di localhost
    ini_set('session.use_strict_mode', '1');
    ini_set('session.gc_maxlifetime', '7200');    // 2 jam
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_secure', '0');        // 0 agar bisa di HTTP / localhost

    session_name('BSI_SESS');
    session_start();

    // Auto-logout jika idle > 2 jam
    if (isset($_SESSION['last_activity'])) {
        if (time() - $_SESSION['last_activity'] > 7200) {
            session_unset();
            session_destroy();
            session_start();
        }
    }
    $_SESSION['last_activity'] = time();
}
