<?php
// ─── core/Request.php ───
// Wrapper sanitasi untuk akses input. TIDAK BOLEH akses $_GET/$_POST langsung
// di halaman — selalu via Request::.

class Request
{
    /**
     * Ambil nilai dari $_GET, sanitasi trim + strip_tags.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        if (!isset($_GET[$key])) return $default;
        return self::sanitizeStr($_GET[$key]);
    }

    /**
     * Ambil nilai dari $_POST, sanitasi trim + strip_tags.
     */
    public static function post(string $key, mixed $default = null): mixed
    {
        if (!isset($_POST[$key])) return $default;
        return self::sanitizeStr($_POST[$key]);
    }

    /**
     * Ambil integer dari GET atau POST (validasi > 0 untuk ID).
     * $source: 'get' atau 'post'
     */
    public static function int(string $key, string $source = 'post', int $default = 0): int
    {
        $raw = $source === 'get' ? ($_GET[$key] ?? null) : ($_POST[$key] ?? null);
        if ($raw === null) return $default;
        $val = filter_var($raw, FILTER_VALIDATE_INT);
        return $val === false ? $default : (int)$val;
    }

    /**
     * Ambil string bersih dari GET atau POST.
     * $source: 'get' atau 'post'
     */
    public static function str(string $key, string $source = 'post', string $default = ''): string
    {
        $raw = $source === 'get' ? ($_GET[$key] ?? null) : ($_POST[$key] ?? null);
        if ($raw === null) return $default;
        return self::sanitizeStr($raw);
    }

    /**
     * Ambil nilai textarea (izinkan newline tapi strip tags).
     */
    public static function textarea(string $key, string $source = 'post', string $default = ''): string
    {
        $raw = $source === 'get' ? ($_GET[$key] ?? null) : ($_POST[$key] ?? null);
        if ($raw === null) return $default;
        return trim(strip_tags((string)$raw));
    }

    /**
     * Ambil nilai float dari POST.
     */
    public static function float(string $key, string $source = 'post', float $default = 0.0): float
    {
        $raw = $source === 'get' ? ($_GET[$key] ?? null) : ($_POST[$key] ?? null);
        if ($raw === null) return $default;
        $val = filter_var($raw, FILTER_VALIDATE_FLOAT);
        return $val === false ? $default : (float)$val;
    }

    /**
     * Ambil data file dari $_FILES.
     */
    public static function file(string $key): array|null
    {
        return isset($_FILES[$key]) && $_FILES[$key]['error'] !== UPLOAD_ERR_NO_FILE
            ? $_FILES[$key]
            : null;
    }

    /**
     * Ambil array dari POST (contoh: baris detail rekap).
     */
    public static function postArray(string $key): array
    {
        $raw = $_POST[$key] ?? [];
        if (!is_array($raw)) return [];
        return array_map(fn($v) => self::sanitizeStr((string)$v), $raw);
    }

    /**
     * Ambil array float dari POST.
     */
    public static function postFloatArray(string $key): array
    {
        $raw = $_POST[$key] ?? [];
        if (!is_array($raw)) return [];
        return array_map(fn($v) => (float)filter_var($v, FILTER_VALIDATE_FLOAT), $raw);
    }

    /**
     * Ambil array int dari POST.
     */
    public static function postIntArray(string $key): array
    {
        $raw = $_POST[$key] ?? [];
        if (!is_array($raw)) return [];
        return array_map('intval', $raw);
    }

    /**
     * Cek apakah request adalah POST.
     */
    public static function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Sanitasi string: trim + strip_tags.
     */
    private static function sanitizeStr(mixed $val): string
    {
        return trim(strip_tags((string)$val));
    }
}
