<?php
// ─── admin/galeri/proses.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/core/Upload.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/models/GaleriModel.php';

Auth::cekSession();
Auth::cekRole(['admin_data', 'super_admin']);
CSRF::verify(Request::post('csrf_token'));

$aksi = Request::post('aksi');

match ($aksi) {
    'tambah' => (function () {
        $data = [
            'judul'      => Request::str('judul'),
            'deskripsi'  => Request::str('deskripsi'),
            'tanggal'    => Request::str('tanggal') ?: date('Y-m-d'),
            'kategori'   => Request::str('kategori') ?: 'Kegiatan',
        ];
        if (empty($data['judul'])) {
            Response::redirect('/admin/galeri/tambah.php', 'Judul foto wajib diisi.', 'error');
        }
        $file = Request::file('gambar');
        if (!$file) {
            Response::redirect('/admin/galeri/tambah.php', 'File foto wajib diunggah.', 'error');
        }
        try {
            $data['gambar'] = Upload::image($file, 'galeri');
        } catch (RuntimeException $e) {
            Response::redirect('/admin/galeri/tambah.php', $e->getMessage(), 'error');
        }
        galeri_tambah($data);
        Response::redirect('/admin/galeri/index.php', 'Foto berhasil ditambahkan ke galeri.', 'success');
    })(),

    'hapus' => (function () {
        $id  = Request::int('id');
        $row = $id > 0 ? galeri_findById($id) : null;
        if ($row && $row['gambar']) {
            Upload::hapus($row['gambar'], 'galeri');
        }
        galeri_hapus($id);
        Response::redirect('/admin/galeri/index.php', 'Foto berhasil dihapus.', 'success');
    })(),

    default => Response::redirect('/admin/galeri/index.php', 'Aksi tidak dikenal.', 'error'),
};
