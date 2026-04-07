<?php
// ─── config/app.php ───
// Konstanta global aplikasi. Load pertama kali di setiap entry point.

// ------------------------------------------------------------------
// DETEKSI ROOT PATH
// ------------------------------------------------------------------
defined('ROOT_PATH') || define('ROOT_PATH', dirname(__DIR__));

// ------------------------------------------------------------------
// LOAD ENVIRONMENT VARIABLES (dari .env jika ada, fallback ke default)
// ------------------------------------------------------------------
$envFile = ROOT_PATH . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) continue;
        [$key, $val] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($val, '"\'');
    }
}

// ------------------------------------------------------------------
// APLIKASI
// ------------------------------------------------------------------
define('APP_NAME',  $_ENV['APP_NAME']  ?? 'Bank Sampah Induk Kota Mojokerto');
define('APP_ENV',   $_ENV['APP_ENV']   ?? 'production');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));
define('BASE_URL',  rtrim($_ENV['BASE_URL'] ?? 'http://localhost/bank-sampah-induk', '/'));

// ------------------------------------------------------------------
// DATABASE
// ------------------------------------------------------------------
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'bsi_db');   // sesuai nama database di phpMyAdmin
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_PORT', $_ENV['DB_PORT'] ?? '3306');

// ------------------------------------------------------------------
// UPLOAD
// ------------------------------------------------------------------
define('UPLOAD_PATH',    ROOT_PATH . '/uploads');
define('UPLOAD_MAX_SIZE', 2097152); // 2MB
define('UPLOAD_ALLOWED',  ['jpg', 'jpeg', 'png', 'webp']);

// ------------------------------------------------------------------
// ERROR HANDLING
// ------------------------------------------------------------------
if (APP_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
    set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline): bool {
        error_log("[BSI ERROR] $errno: $errstr in $errfile:$errline");
        return true;
    });
    set_exception_handler(function (Throwable $e): void {
        error_log('[BSI EXCEPTION] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
        if (!headers_sent()) {
            http_response_code(500);
            header('Content-Type: text/html; charset=UTF-8');
        }
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>Terjadi Kesalahan</title></head>';
        echo '<body style="font-family:sans-serif;text-align:center;padding:4rem;">';
        echo '<h2>Terjadi kesalahan sistem</h2><p>Silakan coba beberapa saat lagi atau hubungi administrator.</p>';
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? '';
        $showDetail = $remoteAddr === '127.0.0.1' || $remoteAddr === '::1';
        if ($showDetail) {
            $msg = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
            $file = htmlspecialchars((string)$e->getFile(), ENT_QUOTES, 'UTF-8');
            $line = (int)$e->getLine();
            echo '<div style="margin-top:1.5rem;text-align:left;max-width:980px;margin-left:auto;margin-right:auto;">';
            echo '<h3 style="margin:0 0 .75rem 0;font-size:1.05rem;color:#0f172a;">Detail Error (lokal)</h3>';
            echo '<pre style="background:#f1f5f9;padding:1rem;border-radius:8px;white-space:pre-wrap;">' . $msg . "\n" . $file . ':' . $line . '</pre>';
            echo '</div>';
        }
        echo '</body></html>';
        exit;
    });
}

// ------------------------------------------------------------------
// TIMEZONE
// ------------------------------------------------------------------
date_default_timezone_set('Asia/Jakarta');
