<?php
// ─── admin/kegiatan/detail.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/KegiatanModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
$id  = Request::int('id','get');
$row = $id > 0 ? kegiatan_findById($id) : false;
if (!$row) Response::abort(404,'Kegiatan tidak ditemukan.');

$pageTitle='Detail Kegiatan'; $sidebarActive='kegiatan';
$breadcrumb=[['label'=>'Kegiatan','url'=>BASE_URL.'/admin/kegiatan/index.php'],['label'=>'Detail','url'=>'']];

require_once dirname(__DIR__, 2) . '/templates/admin/header.php';
require_once dirname(__DIR__, 2) . '/templates/admin/sidebar.php';
require_once dirname(__DIR__, 2) . '/templates/admin/topbar.php';
?>

<div class="page-header">
    <div><h1 class="page-title">Detail Kegiatan</h1></div>
    <a href="index.php" class="btn btn-outline">← Kembali</a>
</div>

<div class="form-card" style="display: flex; gap: 2rem; flex-wrap: wrap;">
    <div style="flex: 1; min-width: 300px;">
        <h2 style="margin-top:0; margin-bottom: 0.5rem;"><?= e($row['judul']) ?></h2>
        <div style="margin-bottom: 1.5rem;"><?= badgeStatus($row['status']) ?> <span class="badge" style="background:#e2e8f0; color:#475569; padding: 0.25rem 0.75rem; border-radius: 999px; font-size: 0.875rem;"><?= e($row['jenis_kegiatan']) ?></span></div>
        
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 1rem;">
            <tr>
                <td style="padding: 0.5rem 0; font-weight: bold; width: 130px; color: #64748b; border-bottom: 1px solid #f1f5f9;">📅 Tanggal Mulai</td>
                <td style="padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;"><?= formatTanggal($row['tanggal']) ?></td>
            </tr>
            <tr>
                <td style="padding: 0.5rem 0; font-weight: bold; color: #64748b; border-bottom: 1px solid #f1f5f9;">🏁 Tanggal Selesai</td>
                <td style="padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;"><?= $row['tanggal_selesai'] ? formatTanggal($row['tanggal_selesai']) : '-' ?></td>
            </tr>
            <tr>
                <td style="padding: 0.5rem 0; font-weight: bold; color: #64748b; border-bottom: 1px solid #f1f5f9;">📍 Lokasi</td>
                <td style="padding: 0.5rem 0; border-bottom: 1px solid #f1f5f9;"><?= e($row['lokasi'] ?: 'Tidak ada informasi lokasi') ?></td>
            </tr>
        </table>

        <h4 style="color: #64748b; margin-bottom: 0.5rem;">📝 Deskripsi:</h4>
        <div style="background: #f8fafc; padding: 1rem; border-radius: 0.5rem; color: #334155; line-height: 1.6;">
            <?= $row['deskripsi'] ? nl2br(e($row['deskripsi'])) : '<em>Tidak ada deskripsi.</em>' ?>
        </div>
    </div>
    
    <div style="flex: 0 0 300px;">
        <h4 style="color: #64748b; margin-top: 0; margin-bottom: 0.5rem;">📸 Foto Kegiatan:</h4>
        <?php if ($row['foto']): ?>
            <img src="<?= BASE_URL ?>/uploads/kegiatan/<?= e($row['foto']) ?>" alt="Foto Kegiatan" style="width: 100%; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <?php else: ?>
            <div style="width: 100%; height: 200px; background: #f1f5f9; border-radius: 0.5rem; display: flex; align-items: center; justify-content: center; color: #94a3b8; font-style: italic;">
                Tidak ada foto
            </div>
        <?php endif; ?>
    </div>
</div>

<div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
    <a href="edit.php?id=<?= (int)$row['id'] ?>" class="btn btn-primary">✏️ Edit Data Ini</a>
</div>

<?php require_once dirname(__DIR__, 2) . '/templates/admin/footer.php' ?>