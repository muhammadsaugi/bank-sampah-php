<?php
// ─── admin/jenis-sampah/proses.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/models/JenisSampahModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
CSRF::verify(Request::post('csrf_token'));
$aksi = Request::post('aksi');

match($aksi) {
    'tambah' => (function(){
        $nama = Request::str('nama');
        if (empty($nama)) Response::redirect('/admin/jenis-sampah/tambah.php','Nama jenis wajib diisi.','error');
        if (jenis_namaExists($nama)) Response::redirect('/admin/jenis-sampah/tambah.php','Nama jenis sudah ada.','error');
        jenis_tambah(['nama'=>$nama,'kode'=>Request::str('kode'),'warna'=>Request::str('warna') ?: '#16A34A'], Auth::getUserId());
        Response::redirect('/admin/jenis-sampah/index.php','Jenis sampah berhasil ditambahkan.','success');
    })(),
    'edit' => (function(){
        $id = Request::int('id');
        $nama = Request::str('nama');
        if ($id<=0||empty($nama)) Response::redirect('/admin/jenis-sampah/index.php','Data tidak valid.','error');
        if (jenis_namaExists($nama,$id)) Response::redirect('/admin/jenis-sampah/edit.php?id='.$id,'Nama jenis sudah digunakan.','error');
        jenis_edit($id,['nama'=>$nama,'kode'=>Request::str('kode'),'warna'=>Request::str('warna') ?: '#16A34A']);
        Response::redirect('/admin/jenis-sampah/index.php','Jenis sampah berhasil diperbarui.','success');
    })(),
    'hapus' => (function(){
        $id = Request::int('id');
        if ($id<=0) Response::redirect('/admin/jenis-sampah/index.php','ID tidak valid.','error');
        jenis_hapus($id);
        Response::redirect('/admin/jenis-sampah/index.php','Jenis sampah berhasil dihapus.','success');
    })(),
    default => Response::redirect('/admin/jenis-sampah/index.php','Aksi tidak dikenal.','error'),
};
