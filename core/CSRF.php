<?php
// ─── core/CSRF.php ───
// Proteksi Cross-Site Request Forgery berbasis session token.

class CSRF
{
    private const SESSION_KEY = 'csrf_token';

    /**
     * Generate token baru atau kembalikan yang sudah ada di session.
     * Token dibuat sekali per session.
     */
    public static function token(): string
    {
        if (empty($_SESSION[self::SESSION_KEY])) {
            $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
        }
        return $_SESSION[self::SESSION_KEY];
    }

    /**
     * Verifikasi token dari form POST.
     * Jika gagal: 403 + pesan + stop eksekusi.
     */
    public static function verify(string|null $token): void
    {
        $expected = $_SESSION[self::SESSION_KEY] ?? '';

        if (empty($token) || empty($expected) || !hash_equals($expected, $token)) {
            http_response_code(403);
            // Regenerate token agar tidak bisa di-reuse
            unset($_SESSION[self::SESSION_KEY]);
            die('<h2>403 Forbidden</h2><p>Token CSRF tidak valid. Silakan kembali dan coba lagi.</p>');
        }
    }

    /**
     * Cetak hidden input siap pakai di form.
     */
    public static function input(): string
    {
        return '<input type="hidden" name="csrf_token" value="' . self::token() . '">';
    }

    /**
     * Regenerate token (opsional — digunakan setelah aksi penting).
     */
    public static function regenerate(): void
    {
        $_SESSION[self::SESSION_KEY] = bin2hex(random_bytes(32));
    }
}
