<?php
// ─── admin/kegiatan/proses.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/core/Upload.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/models/KegiatanModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
CSRF::verify(Request::post('csrf_token'));

function ambilDataKegiatan(): array {
    return [
        'judul'           => Request::str('judul'),
        'jenis_kegiatan'  => Request::str('jenis_kegiatan'),
        'tanggal'         => Request::str('tanggal'),
        'tanggal_selesai' => Request::str('tanggal_selesai'),
        'lokasi'          => Request::str('lokasi'),
        'deskripsi'       => Request::textarea('deskripsi'),
        'status'          => Request::str('status'),
    ];
}

$aksi = Request::post('aksi');

match($aksi) {
    'tambah' => (function(){
        $data = ambilDataKegiatan();
        if (empty($data['judul']) || empty($data['tanggal'])) Response::redirect('/admin/kegiatan/tambah.php','Judul dan tanggal wajib diisi.','error');
        $file = Request::file('foto');
        if ($file) { try { $data['foto'] = Upload::image($file,'kegiatan'); } catch(RuntimeException $e) { Response::redirect('/admin/kegiatan/tambah.php',$e->getMessage(),'error'); } }
        kegiatan_tambah($data);
        Response::redirect('/admin/kegiatan/index.php','Kegiatan berhasil ditambahkan.','success');
    })(),
    'edit' => (function(){
        $id = Request::int('id');
        if ($id<=0) Response::redirect('/admin/kegiatan/index.php','ID tidak valid.','error');
        $old = kegiatan_findById($id);
        $data = ambilDataKegiatan();
        $file = Request::file('foto');
        if ($file) {
            try {
                $data['foto'] = Upload::image($file,'kegiatan');
                if ($old && $old['foto']) Upload::hapus($old['foto'],'kegiatan');
            } catch(RuntimeException $e) { Response::redirect('/admin/kegiatan/edit.php?id='.$id,$e->getMessage(),'error'); }
        }
        kegiatan_edit($id,$data);
        Response::redirect('/admin/kegiatan/index.php','Kegiatan berhasil diperbarui.','success');
    })(),
    'hapus' => (function(){
        $id = Request::int('id');
        $row = $id > 0 ? kegiatan_findById($id) : null;
        if ($row && $row['foto']) Upload::hapus($row['foto'],'kegiatan');
        kegiatan_hapus($id);
        Response::redirect('/admin/kegiatan/index.php','Kegiatan berhasil dihapus.','success');
    })(),
    default => Response::redirect('/admin/kegiatan/index.php','Aksi tidak dikenal.','error'),
};
