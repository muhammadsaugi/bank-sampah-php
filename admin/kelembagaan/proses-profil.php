<?php
// ─── admin/kelembagaan/proses-profil.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/core/Upload.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/models/ProfilModel.php';

Auth::cekSession();
Auth::cekRole(['super_admin']);

if (!Request::isPost()) {
    Response::redirect('/admin/kelembagaan/profil.php');
}

CSRF::verify(Request::post('csrf_token'));

$data = [
    'nama_lembaga'  => Request::str('nama_lembaga'),
    'tagline'       => Request::str('tagline'),
    'deskripsi'     => Request::textarea('deskripsi'),
    'visi'          => Request::textarea('visi'),
    'misi'          => Request::textarea('misi'),
    'alamat'        => Request::textarea('alamat'),
    'telepon'       => Request::str('telepon'),
    'email'         => Request::str('email'),
    'website'       => Request::str('website'),
    'tahun_berdiri' => Request::int('tahun_berdiri') ?: null,
];

if (empty($data['nama_lembaga'])) {
    Response::redirect('/admin/kelembagaan/profil.php', 'Nama lembaga wajib diisi.', 'error');
}

// Handle upload logo
$file = Request::file('logo');
if ($file) {
    $profilLama = profil_get();
    try {
        $data['logo'] = Upload::image($file, 'profil');
        // Hapus logo lama jika ada
        if (!empty($profilLama['logo'])) {
            Upload::hapus($profilLama['logo'], 'profil');
        }
    } catch (RuntimeException $e) {
        Response::redirect('/admin/kelembagaan/profil.php', $e->getMessage(), 'error');
    }
}

profil_update($data);
Response::redirect('/admin/kelembagaan/profil.php', 'Profil kelembagaan berhasil diperbarui.', 'success');
