<?php
// ─── admin/bank-sampah/proses.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/core/Validator.php';
require_once dirname(__DIR__, 2) . '/models/BankSampahModel.php';

Auth::cekSession();
Auth::cekRole(['admin_data', 'super_admin']);
CSRF::verify(Request::post('csrf_token'));

$aksi = Request::post('aksi');

match ($aksi) {
    'tambah' => prosesTambah(),
    'edit'   => prosesEdit(),
    'hapus'  => prosesHapus(),
    'toggle' => prosesToggle(),
    default  => Response::redirect('/admin/bank-sampah/index.php', 'Aksi tidak dikenal.', 'error'),
};

function prosesTambah(): void
{
    $data = [
        'nama'          => Request::str('nama'),
        'alamat'        => Request::str('alamat'),
        'kelurahan'     => Request::str('kelurahan'),
        'kecamatan'     => Request::str('kecamatan'),
        'ketua'         => Request::str('ketua'),
        'kontak'        => Request::str('kontak'),
        'tahun_berdiri' => Request::int('tahun_berdiri'),
    ];

    $v = new Validator($data);
    $v->required('nama', 'Nama bank sampah')->required('ketua', 'Nama ketua')->required('alamat', 'Alamat');

    if ($v->hasErrors()) {
        Response::redirect('/admin/bank-sampah/tambah.php', $v->firstError(), 'error');
    }

    bs_tambah($data, Auth::getUserId());
    Response::redirect('/admin/bank-sampah/index.php', 'Bank sampah berhasil ditambahkan.', 'success');
}

function prosesEdit(): void
{
    $id = Request::int('id');
    if ($id <= 0) Response::redirect('/admin/bank-sampah/index.php', 'ID tidak valid.', 'error');

    $data = [
        'nama'          => Request::str('nama'),
        'alamat'        => Request::str('alamat'),
        'kelurahan'     => Request::str('kelurahan'),
        'kecamatan'     => Request::str('kecamatan'),
        'ketua'         => Request::str('ketua'),
        'kontak'        => Request::str('kontak'),
        'tahun_berdiri' => Request::int('tahun_berdiri'),
        'aktif'         => Request::int('aktif'),
    ];

    $v = new Validator($data);
    $v->required('nama', 'Nama bank sampah')->required('ketua', 'Nama ketua');

    if ($v->hasErrors()) {
        Response::redirect('/admin/bank-sampah/edit.php?id=' . $id, $v->firstError(), 'error');
    }

    bs_edit($id, $data);
    Response::redirect('/admin/bank-sampah/index.php', 'Bank sampah berhasil diperbarui.', 'success');
}

function prosesHapus(): void
{
    $id = Request::int('id');
    if ($id <= 0) Response::redirect('/admin/bank-sampah/index.php', 'ID tidak valid.', 'error');

    try {
        bs_hapus($id);
        Response::redirect('/admin/bank-sampah/index.php', 'Bank sampah berhasil dihapus.', 'success');
    } catch (PDOException $e) {
        // Kemungkinan ada rekap yang masih terhubung (RESTRICT FK)
        Response::redirect('/admin/bank-sampah/index.php', 'Gagal menghapus: bank sampah ini masih memiliki data rekap.', 'error');
    }
}

function prosesToggle(): void
{
    $id = Request::int('id');
    if ($id <= 0) Response::redirect('/admin/bank-sampah/index.php', 'ID tidak valid.', 'error');
    bs_toggle($id);
    Response::redirect('/admin/bank-sampah/index.php', 'Status bank sampah berhasil diubah.', 'success');
}
