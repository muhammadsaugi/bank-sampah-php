<?php
// ─── templates/admin/flash.php ───
// Tampilkan flash message (one-time) dari session.

$flash = Response::getFlash();
if ($flash):
    $icons = ['success' => '✅', 'error' => '❌', 'warning' => '⚠️', 'info' => 'ℹ️'];
    $icon  = $icons[$flash['type']] ?? 'ℹ️';
?>
<div class="alert alert-<?= e($flash['type'] === 'error' ? 'error' : $flash['type']) ?>" role="alert">
    <span class="alert-icon"><?= $icon ?></span>
    <div class="alert-body"><?= e($flash['message']) ?></div>
</div>
<?php endif ?>
