<?php
// ─── core/Auth.php ───
// Autentikasi dan kontrol akses berbasis session + role.

class Auth
{
    /**
     * Cek apakah user sudah login.
     * Jika belum → redirect ke halaman login.
     */
    public static function cekSession(): void
    {
        if (empty($_SESSION['user_id'])) {
            Response::setFlash('Silakan login terlebih dahulu.', 'error');
            header('Location: ' . BASE_URL . '/auth/login.php');
            exit;
        }
    }

    /**
     * Cek apakah user memiliki role yang diizinkan.
     * $roles bisa string tunggal atau array role.
     * Jika tidak sesuai → abort 403.
     */
    public static function cekRole(string|array $roles): void
    {
        self::cekSession();
        $userRole = $_SESSION['role'] ?? '';
        $roles    = (array)$roles;

        if (!in_array($userRole, $roles, true)) {
            Response::abort(403, 'Anda tidak memiliki hak akses ke halaman ini.');
        }
    }

    /**
     * Cek apakah role user cocok (return bool, tidak abort).
     */
    public static function hasRole(string $role): bool
    {
        return ($_SESSION['role'] ?? '') === $role;
    }

    /**
     * Cek apakah user memiliki salah satu dari beberapa role.
     */
    public static function hasAnyRole(array $roles): bool
    {
        return in_array($_SESSION['role'] ?? '', $roles, true);
    }

    /**
     * Kembalikan user_id dari session.
     */
    public static function getUserId(): int
    {
        return (int)($_SESSION['user_id'] ?? 0);
    }

    /**
     * Kembalikan username dari session.
     */
    public static function getUsername(): string
    {
        return $_SESSION['username'] ?? '';
    }

    /**
     * Kembalikan nama lengkap dari session.
     */
    public static function getNama(): string
    {
        return $_SESSION['nama'] ?? '';
    }

    /**
     * Kembalikan role dari session.
     */
    public static function getRole(): string
    {
        return $_SESSION['role'] ?? '';
    }

    /**
     * Proses login: verifikasi credential, set session, update last_login.
     * Return: array user jika berhasil, null jika gagal.
     */
    public static function login(string $username, string $password): array|null
    {
        // Cek user dari database
        require_once dirname(__DIR__) . '/models/UserModel.php';
        $user = user_findByUsername($username);

        if (!$user) return null;
        // Nonaktif jika kolom ada dan bernilai 0 (DB lama tanpa kolom aktif = tetap boleh login)
        if (array_key_exists('aktif', $user) && !(int)$user['aktif']) {
            return null;
        }
        if (!password_verify($password, $user['password'])) return null;

        // Regenerate session ID setelah login (cegah session fixation)
        session_regenerate_id(true);

        // Set session
        $_SESSION['user_id']       = $user['id'];
        $_SESSION['username']      = $user['username'];
        $_SESSION['nama']          = $user['nama'];
        $_SESSION['role']          = $user['role'];
        $_SESSION['login_time']    = time();
        $_SESSION['last_activity'] = time();

        // Update kolom last_login jika ada (hindari gagal login jika skema DB belum lengkap)
        try {
            user_updateLastLogin((int)$user['id']);
        } catch (Throwable $e) {
            error_log('[BSI] user_updateLastLogin: ' . $e->getMessage());
        }

        return $user;
    }

    /**
     * Logout: hapus semua data session + redirect ke login.
     */
    public static function logout(): never
    {
        $_SESSION = [];

        // Hapus cookie session
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(), '', time() - 42000,
                $params['path'], $params['domain'],
                $params['secure'], $params['httponly']
            );
        }

        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login.php');
        exit;
    }

    /**
     * Cek apakah user sudah login (return bool, tidak redirect).
     */
    public static function isLoggedIn(): bool
    {
        return !empty($_SESSION['user_id']);
    }
}
