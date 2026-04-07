<?php
// ─── admin/nama-sampah/proses.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/NamaSampahModel.php';

Auth::cekSession();
Auth::cekRole(['admin_data', 'super_admin']);
CSRF::verify(Request::post('csrf_token'));

$aksi = Request::post('aksi');

match ($aksi) {
    'tambah' => (function () {
        $data = [
            'id_jenis'    => Request::int('id_jenis'),
            'nama'        => Request::str('nama'),
            'satuan'      => Request::str('satuan') ?: 'Kg',
            'keterangan'  => Request::str('keterangan'),
        ];
        if ($data['id_jenis'] <= 0 || empty($data['nama'])) {
            Response::redirect('/admin/nama-sampah/tambah.php', 'Jenis dan nama item wajib diisi.', 'error');
        }
        nama_tambah($data, Auth::getUserId());
        Response::redirect('/admin/nama-sampah/index.php', 'Item sampah berhasil ditambahkan.', 'success');
    })(),
    'edit' => (function () {
        $id   = Request::int('id');
        $data = [
            'id_jenis'   => Request::int('id_jenis'),
            'nama'       => Request::str('nama'),
            'satuan'     => Request::str('satuan') ?: 'Kg',
            'keterangan' => Request::str('keterangan'),
        ];
        if ($id <= 0 || $data['id_jenis'] <= 0 || empty($data['nama'])) {
            Response::redirect('/admin/nama-sampah/index.php', 'Data tidak valid.', 'error');
        }
        nama_edit($id, $data);
        Response::redirect('/admin/nama-sampah/index.php', 'Item sampah berhasil diperbarui.', 'success');
    })(),
    'hapus' => (function () {
        $id = Request::int('id');
        if ($id <= 0) Response::redirect('/admin/nama-sampah/index.php', 'ID tidak valid.', 'error');
        try {
            nama_hapus($id);
            Response::redirect('/admin/nama-sampah/index.php', 'Item sampah berhasil dihapus.', 'success');
        } catch (PDOException) {
            Response::redirect('/admin/nama-sampah/index.php', 'Gagal hapus: item masih memiliki data rekap atau harga.', 'error');
        }
    })(),
    default => Response::redirect('/admin/nama-sampah/index.php', 'Aksi tidak dikenal.', 'error'),
};
