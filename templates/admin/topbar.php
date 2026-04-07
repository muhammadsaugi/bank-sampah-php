<?php
// ─── templates/admin/topbar.php ───
// Topbar admin: breadcrumb + info user + logout.

$breadcrumb ??= [];   // array ['label' => '...', 'url' => '...'] — url kosong = current
$topbarTitle ??= $pageTitle ?? 'Dashboard';
$userName  = Auth::getNama();
$userRole  = Auth::getRole();
$initials  = inisialNama($userName);
?>

<!-- TOPBAR -->
<header class="topbar">
    <div class="topbar-left">
        <!-- Hamburger mobile -->
        <button onclick="document.getElementById('sidebar').classList.toggle('open')"
                style="background:none;border:none;cursor:pointer;font-size:1.2rem;color:var(--color-text-muted);padding:4px;display:none;"
                class="sidebar-toggle-btn" aria-label="Toggle sidebar">☰</button>

        <div>
            <!-- Breadcrumb -->
            <?php if (!empty($breadcrumb)): ?>
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
                <?php foreach ($breadcrumb as $crumb): ?>
                    <span class="sep">›</span>
                    <?php if (!empty($crumb['url'])): ?>
                        <a href="<?= e($crumb['url']) ?>"><?= e($crumb['label']) ?></a>
                    <?php else: ?>
                        <span class="current"><?= e($crumb['label']) ?></span>
                    <?php endif ?>
                <?php endforeach ?>
            </nav>
            <?php endif ?>
            <div class="topbar-title"><?= e($topbarTitle) ?></div>
        </div>
    </div>

    <div class="topbar-right">
        <!-- Info user -->
        <div class="topbar-avatar"><?= e($initials) ?></div>
        <div>
            <div class="topbar-user-name"><?= e($userName) ?></div>
        </div>
        <?= badgeRole($userRole) ?>

        <!-- Logout -->
        <a href="<?= BASE_URL ?>/auth/logout.php" class="topbar-logout"
           onclick="return confirm('Yakin ingin logout?')">Logout</a>
    </div>
</header>

<!-- Main content area dimulai di sini -->
<div class="admin-main">
<div class="admin-content">

<style>
@media (max-width: 768px) {
    .sidebar-toggle-btn { display: flex !important; }
}
</style>
