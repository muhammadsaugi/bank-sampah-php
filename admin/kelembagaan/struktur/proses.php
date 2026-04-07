<?php
// ─── admin/kelembagaan/struktur/proses.php ───
require_once dirname(__DIR__, 3) . '/config/app.php';
require_once dirname(__DIR__, 3) . '/config/session.php';
require_once dirname(__DIR__, 3) . '/config/database.php';
require_once dirname(__DIR__, 3) . '/core/Auth.php';
require_once dirname(__DIR__, 3) . '/core/CSRF.php';
require_once dirname(__DIR__, 3) . '/core/Request.php';
require_once dirname(__DIR__, 3) . '/core/Response.php';
require_once dirname(__DIR__, 3) . '/core/Upload.php';
require_once dirname(__DIR__, 3) . '/core/Helper.php';
require_once dirname(__DIR__, 3) . '/models/StrukturModel.php';

Auth::cekSession();
Auth::cekRole(['super_admin']);
CSRF::verify(Request::post('csrf_token'));

$aksi = Request::post('aksi');

match ($aksi) {
    'tambah' => (function () {
        $data = [
            'judul'      => Request::str('judul'),
            'periode'    => Request::str('periode'),
            'keterangan' => Request::str('keterangan'),
            'urutan'     => Request::int('urutan'),
        ];
        if (empty($data['judul'])) {
            Response::redirect('/admin/kelembagaan/struktur/tambah.php', 'Judul wajib diisi.', 'error');
        }
        $file = Request::file('gambar');
        if (!$file) {
            Response::redirect('/admin/kelembagaan/struktur/tambah.php', 'File gambar wajib diunggah.', 'error');
        }
        try {
            $data['gambar'] = Upload::image($file, 'struktur');
        } catch (RuntimeException $e) {
            Response::redirect('/admin/kelembagaan/struktur/tambah.php', $e->getMessage(), 'error');
        }
        try {
            struktur_tambah($data);
        } catch (Throwable $e) {
            // Jangan tampilkan pesan generic "kesalahan sistem" saat insert gagal.
            error_log('[BSI STRUKTUR TAMBAH] ' . $e->getMessage());
            Response::redirect(
                '/admin/kelembagaan/struktur/tambah.php',
                'Gagal menyimpan struktur: ' . $e->getMessage(),
                'error'
            );
        }

        Response::redirect('/admin/kelembagaan/struktur/index.php', 'Struktur organisasi berhasil ditambahkan.', 'success');
    })(),

    'edit' => (function () {
        $id  = Request::int('id');
        $old = $id > 0 ? struktur_findById($id) : null;
        if (!$old) Response::redirect('/admin/kelembagaan/struktur/index.php', 'ID tidak valid.', 'error');

        $data = [
            'judul'      => Request::str('judul'),
            'periode'    => Request::str('periode'),
            'keterangan' => Request::str('keterangan'),
            'urutan'     => Request::int('urutan'),
            'aktif'      => Request::int('aktif'),
        ];

        $file = Request::file('gambar');
        if ($file) {
            try {
                $data['gambar'] = Upload::image($file, 'struktur');
                if ($old['gambar']) Upload::hapus($old['gambar'], 'struktur');
            } catch (RuntimeException $e) {
                Response::redirect('/admin/kelembagaan/struktur/edit.php?id=' . $id, $e->getMessage(), 'error');
            }
        }

        try {
            struktur_edit($id, $data);
        } catch (Throwable $e) {
            error_log('[BSI STRUKTUR EDIT] ' . $e->getMessage());
            Response::redirect('/admin/kelembagaan/struktur/edit.php?id=' . $id, 'Gagal menyimpan perubahan: ' . $e->getMessage(), 'error');
        }

        Response::redirect('/admin/kelembagaan/struktur/index.php', 'Struktur organisasi berhasil diperbarui.', 'success');
    })(),

    'hapus' => (function () {
        $id  = Request::int('id');
        $row = $id > 0 ? struktur_findById($id) : null;
        if ($row && $row['gambar']) Upload::hapus($row['gambar'], 'struktur');

        try {
            struktur_hapus($id);
        } catch (Throwable $e) {
            error_log('[BSI STRUKTUR HAPUS] ' . $e->getMessage());
            Response::redirect('/admin/kelembagaan/struktur/index.php', 'Gagal menghapus struktur: ' . $e->getMessage(), 'error');
        }

        Response::redirect('/admin/kelembagaan/struktur/index.php', 'Struktur organisasi berhasil dihapus.', 'success');
    })(),

    default => Response::redirect('/admin/kelembagaan/struktur/index.php', 'Aksi tidak dikenal.', 'error'),
};
