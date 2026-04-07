<?php
// ─── auth/proses-login.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/core/Auth.php';
require_once dirname(__DIR__) . '/core/CSRF.php';
require_once dirname(__DIR__) . '/core/Request.php';
require_once dirname(__DIR__) . '/core/Response.php';
require_once dirname(__DIR__) . '/core/Helper.php';

// Hanya terima POST
if (!Request::isPost()) {
    Response::redirect('/auth/login.php');
}

// Verifikasi CSRF
CSRF::verify(Request::post('csrf_token'));

$username = Request::str('username');
$password = $_POST['password'] ?? ''; // password tidak di-strip_tags

// Validasi input kosong
if (empty($username) || empty($password)) {
    Response::redirect('/auth/login.php', 'Username dan password wajib diisi.', 'error');
}

// Proses login via Auth
$user = Auth::login($username, $password);

if ($user === null) {
    // Delay kecil untuk mencegah brute-force timing attack
    usleep(300000); // 300ms
    Response::redirect('/auth/login.php', 'Username atau password salah.', 'error');
}

// Berhasil login
Response::redirect('/admin/dashboard.php', 'Selamat datang, ' . $user['nama'] . '!', 'success');
