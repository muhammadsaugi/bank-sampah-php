<?php
// ─── templates/admin/footer.php ───
// Penutup HTML admin + script JS minimal.
?>
</div><!-- /.admin-content -->
</div><!-- /.admin-main -->
</div><!-- /.admin-wrapper -->

<!-- Script upload preview -->
<script src="<?= BASE_URL ?>/assets/js/upload-preview.js"></script>

<footer class="admin-footer">
    <span>&copy; <?= date('Y') ?> <?= e(APP_NAME) ?></span>
    <a href="<?= BASE_URL ?>/public/beranda.php" target="_blank"
       style="color:var(--color-primary);font-size:12px;">← Lihat Situs Publik</a>
</footer>

</body>
</html>
