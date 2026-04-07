<?php
// ─── core/Response.php ───
// Helper untuk redirect, flash message, dan JSON response.

class Response
{
    private const FLASH_KEY = '__flash';

    /**
     * Redirect ke URL tertentu.
     * Mendukung path relatif seperti '/admin/dashboard.php'
     * yang akan digabung dengan BASE_URL.
     */
    public static function redirect(string $url, string $message = '', string $type = 'success'): never
    {
        if ($message !== '') {
            self::setFlash($message, $type);
        }

        // Jika sudah URL absolut (http/https), langsung pakai
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            header('Location: ' . $url);
            exit;
        }

        // Path relatif dimulai '/' — gabungkan dengan BASE_URL
        if (str_starts_with($url, '/')) {
            $target = rtrim(BASE_URL, '/') . $url;
        } else {
            $target = rtrim(BASE_URL, '/') . '/' . $url;
        }

        header('Location: ' . $target);
        exit;
    }

    /**
     * Simpan flash message ke session (one-time).
     */
    public static function setFlash(string $message, string $type = 'success'): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION[self::FLASH_KEY] = ['message' => $message, 'type' => $type];
    }

    /**
     * Ambil dan hapus flash message dari session.
     */
    public static function getFlash(): array|null
    {
        if (isset($_SESSION[self::FLASH_KEY])) {
            $flash = $_SESSION[self::FLASH_KEY];
            unset($_SESSION[self::FLASH_KEY]);
            return $flash;
        }
        return null;
    }

    /**
     * Output JSON — untuk endpoint API.
     */
    public static function json(mixed $data, int $statusCode = 200): never
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=UTF-8');
        header('X-Content-Type-Options: nosniff');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Abort dengan status code dan pesan.
     */
    public static function abort(int $code = 403, string $message = 'Akses ditolak'): never
    {
        http_response_code($code);
        $titles = [403 => '403 Forbidden', 404 => '404 Not Found', 500 => 'Server Error'];
        $title  = $titles[$code] ?? "Error $code";
        echo '<!DOCTYPE html><html lang="id"><head><meta charset="UTF-8"><title>' . $title . '</title>';
        echo '<style>body{font-family:sans-serif;text-align:center;padding:4rem;color:#0f172a}';
        echo 'h1{font-size:4rem;margin:0;color:#16a34a}p{color:#64748b}</style></head><body>';
        echo '<h1>' . $code . '</h1><p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>';
        echo '<a href="javascript:history.back()" style="color:#16a34a">← Kembali</a>';
        echo '</body></html>';
        exit;
    }
}
