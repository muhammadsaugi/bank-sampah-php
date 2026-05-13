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
        
        // Validasi input kosong
        if (empty($data['judul'])) {
            Response::redirect('/admin/galeri/tambah.php', 'Judul foto wajib diisi.', 'error');
        }
        
        $file = Request::file('gambar');
        if (!$file || $file['error'] === UPLOAD_ERR_NO_FILE) {
            Response::redirect('/admin/galeri/tambah.php', 'File foto wajib diunggah.', 'error');
        }

        // VALIDASI SERVER-SIDE: Ukuran maksimal 2MB (2 * 1024 * 1024 bytes)
        if ($file['size'] > 2097152) {
            Response::redirect('/admin/galeri/tambah.php', 'Gagal: Ukuran foto maksimal 2MB!', 'error');
        }

        try {
            $data['gambar'] = Upload::image($file, 'galeri');
        } catch (RuntimeException $e) {
            Response::redirect('/admin/galeri/tambah.php', $e->getMessage(), 'error');
        }
        
        galeri_tambah($data);
        Response::redirect('/admin/galeri/index.php', 'Foto berhasil ditambahkan ke galeri.', 'success');
    })(),

    'edit' => (function () {
        // Saya siapkan blok 'edit' di proses.php jika nanti Anda ingin membuat form editnya
        $id = Request::int('id');
        if ($id <= 0) Response::redirect('/admin/galeri/index.php', 'ID tidak valid.', 'error');
        
        $old = galeri_findById($id);
        if (!$old) Response::redirect('/admin/galeri/index.php', 'Data tidak ditemukan.', 'error');

        $data = [
            'judul'      => Request::str('judul'),
            'deskripsi'  => Request::str('deskripsi'),
            'tanggal'    => Request::str('tanggal') ?: date('Y-m-d'),
            'kategori'   => Request::str('kategori') ?: 'Kegiatan',
        ];

        if (empty($data['judul'])) {
            Response::redirect('/admin/galeri/edit.php?id='.$id, 'Judul foto wajib diisi.', 'error');
        }

        $file = Request::file('gambar');
        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($file['size'] > 2097152) {
                Response::redirect('/admin/galeri/edit.php?id='.$id, 'Gagal: Ukuran foto maksimal 2MB!', 'error');
            }
            try {
                $data['gambar'] = Upload::image($file, 'galeri');
                if ($old['gambar']) Upload::hapus($old['gambar'], 'galeri');
            } catch (RuntimeException $e) {
                Response::redirect('/admin/galeri/edit.php?id='.$id, $e->getMessage(), 'error');
            }
        }

        galeri_edit($id, $data);
        Response::redirect('/admin/galeri/index.php', 'Data galeri berhasil diperbarui.', 'success');
    })(),

    'hapus' => (function () {
        $id  = Request::int('id');
        $row = $id > 0 ? galeri_findById($id) : null;
        if ($row && $row['gambar']) {
            Upload::hapus($row['gambar'], 'galeri');
        }
        if ($row) {
            galeri_hapus($id);
        }
        Response::redirect('/admin/galeri/index.php', 'Foto berhasil dihapus dari galeri.', 'success');
    })(),

    default => Response::redirect('/admin/galeri/index.php', 'Aksi tidak valid.', 'error')
};