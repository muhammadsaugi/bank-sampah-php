<?php
// ─── templates/public/footer.php ───
// Footer halaman publik + penutup HTML.

require_once dirname(__DIR__, 2) . '/models/ProfilModel.php';
$profil = profil_get();
?>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand -->
            <div>
                <div class="navbar-brand" style="display:flex;align-items:center;gap:.6rem;margin-bottom:.75rem;">
                    <div class="navbar-logo-icon">♻</div>
                    <div class="navbar-logo-text">
                        <?= e($profil['nama_lembaga'] ?? APP_NAME) ?>
                        <span class="navbar-logo-sub">Kota Mojokerto</span>
                    </div>
                </div>
                <p class="footer-desc">
                    <?= e(truncate($profil['deskripsi'] ?? 'Sistem Informasi Bank Sampah Induk Kota Mojokerto — mengelola data bank sampah, rekapitulasi, dan laporan secara terpadu.', 180)) ?>
                </p>
            </div>

            <!-- Menu Cepat -->
            <div>
                <p class="footer-title">Menu Cepat</p>
                <div class="footer-links">
                    <a href="<?= BASE_URL ?>/public/beranda.php">Beranda</a>
                    <a href="<?= BASE_URL ?>/public/kelembagaan.php">Profil Lembaga</a>
                    <a href="<?= BASE_URL ?>/public/data-bank-sampah.php">Data Bank Sampah</a>
                    <a href="<?= BASE_URL ?>/public/rekapitulasi.php">Rekapitulasi</a>
                    <a href="<?= BASE_URL ?>/public/kegiatan.php">Kegiatan</a>
                    <a href="<?= BASE_URL ?>/public/berita.php">Berita</a>
                    <a href="<?= BASE_URL ?>/public/galeri.php">Galeri</a>
                </div>
            </div>

            <!-- Kontak -->
            <div>
                <p class="footer-title">Kontak Kami</p>
                <div class="footer-contact">
                    <?php if (!empty($profil['alamat'])): ?>
                    <div class="footer-contact-item">
                        <span>📍</span>
                        <span><?= e($profil['alamat']) ?></span>
                    </div>
                    <?php endif ?>
                    <?php if (!empty($profil['telepon'])): ?>
                    <div class="footer-contact-item">
                        <span>📞</span>
                        <span><?= e($profil['telepon']) ?></span>
                    </div>
                    <?php endif ?>
                    <?php if (!empty($profil['email'])): ?>
                    <div class="footer-contact-item">
                        <span>✉️</span>
                        <span><?= e($profil['email']) ?></span>
                    </div>
                    <?php endif ?>
                    <?php if (!empty($profil['tahun_berdiri'])): ?>
                    <div class="footer-contact-item">
                        <span>🗓️</span>
                        <span>Berdiri sejak <?= e($profil['tahun_berdiri']) ?></span>
                    </div>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            &copy; <?= date('Y') ?> <?= e($profil['nama_lembaga'] ?? APP_NAME) ?> — Kota Mojokerto.
            Seluruh data bersifat resmi dan dapat dipertanggungjawabkan.
        </div>
    </div>
</footer>

</body>
</html>
