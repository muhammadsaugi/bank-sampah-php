<?php
// ─── auth/login.php ───
require_once dirname(__DIR__) . '/config/app.php';
require_once dirname(__DIR__) . '/config/session.php';
require_once dirname(__DIR__) . '/core/Auth.php';
require_once dirname(__DIR__) . '/core/Helper.php';
require_once dirname(__DIR__) . '/core/CSRF.php';
require_once dirname(__DIR__) . '/core/Response.php';

// Jika sudah login, langsung ke dashboard
if (Auth::isLoggedIn()) {
    header('Location: ' . BASE_URL . '/admin/dashboard.php');
    exit;
}

$flash = Response::getFlash();

header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Login Admin — <?= e(APP_NAME) ?></title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/reset.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/components.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary-mid) 100%);
            padding: 1.5rem;
        }

        .login-box {
            background: var(--color-surface);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-lg);
            width: 100%;
            max-width: 420px;
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--color-primary-dark), var(--color-primary-mid));
            padding: 2.5rem 2rem 2rem;
            text-align: center;
            color: white;
        }

        .login-logo {
            width: 56px;
            height: 56px;
            background: rgba(255,255,255,0.15);
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            margin: 0 auto 1rem;
            backdrop-filter: blur(8px);
        }

        .login-title {
            font-family: var(--font-display);
            font-size: 1.4rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.25rem;
        }

        .login-sub {
            font-size: 13px;
            color: rgba(255,255,255,0.65);
        }

        .login-body {
            padding: 2rem;
        }

        .login-footer {
            text-align: center;
            padding: 1rem 2rem 1.5rem;
            border-top: 1px solid var(--color-border);
        }

        .login-footer a {
            font-size: 13px;
            color: var(--color-text-muted);
        }

        .login-footer a:hover {
            color: var(--color-primary);
        }

        .password-toggle {
            position: relative;
        }

        .password-toggle .form-control { padding-right: 2.75rem; }

        .btn-show-pass {
            position: absolute;
            right: 0.65rem;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            font-size: 0.95rem;
            color: var(--color-text-muted);
            padding: 4px;
        }

        .btn-show-pass:hover { color: var(--color-primary); }
    </style>
</head>
<body>

<div class="login-box">
    <div class="login-header">
        <div class="login-logo">♻</div>
        <h1 class="login-title">Login Admin</h1>
        <p class="login-sub"><?= e(APP_NAME) ?></p>
    </div>

    <div class="login-body">
        <!-- Flash message -->
        <?php if ($flash): ?>
        <div class="alert alert-<?= e($flash['type'] === 'error' ? 'error' : $flash['type']) ?>"
             style="margin-bottom:1.25rem;">
            <span class="alert-icon"><?= $flash['type'] === 'success' ? '✅' : '❌' ?></span>
            <div class="alert-body"><?= e($flash['message']) ?></div>
        </div>
        <?php endif ?>

        <form method="POST" action="<?= BASE_URL ?>/auth/proses-login.php" autocomplete="on">
            <?= CSRF::input() ?>

            <div class="form-group">
                <label class="form-label" for="username">
                    Username <span class="required">*</span>
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    required
                    autofocus
                    maxlength="50"
                >
            </div>

            <div class="form-group">
                <label class="form-label" for="password">
                    Password <span class="required">*</span>
                </label>
                <div class="password-toggle">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                        maxlength="100"
                    >
                    <button type="button" class="btn-show-pass" onclick="togglePassword()" title="Tampilkan password">👁</button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;padding:.65rem;">
                Masuk ke Panel Admin
            </button>
        </form>
    </div>

    <div class="login-footer">
        <a href="<?= BASE_URL ?>/public/beranda.php">← Kembali ke Situs Publik</a>
    </div>
</div>

<script>
function togglePassword() {
    const inp = document.getElementById('password');
    inp.type = inp.type === 'password' ? 'text' : 'password';
}
</script>
</body>
</html>
