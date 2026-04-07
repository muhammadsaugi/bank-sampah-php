<?php
// ─── admin/berita/proses.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/core/Upload.php';
require_once dirname(__DIR__, 2) . '/core/Helper.php';
require_once dirname(__DIR__, 2) . '/models/BeritaModel.php';

Auth::cekSession(); Auth::cekRole(['admin_data','super_admin']);
CSRF::verify(Request::post('csrf_token'));

function ambilDataBerita(): array {
    $judul = Request::str('judul');
    $slug  = buatSlug($judul);
    return [
        'judul'    => $judul,
        'slug'     => $slug,
        'isi'      => $_POST['isi'] ?? '', // bisa berisi HTML
        'kategori' => Request::str('kategori'),
        'tags'     => Request::str('tags'),
        'status'   => Request::str('status') === 'publish' ? 'publish' : 'draft',
    ];
}

$aksi = Request::post('aksi');
match($aksi) {
    'tambah' => (function(){
        $data = ambilDataBerita();
        if (empty($data['judul'])) Response::redirect('/admin/berita/tambah.php','Judul wajib diisi.','error');
        if (berita_slugExists($data['slug'])) $data['slug'] .= '-' . time();
        $file = Request::file('foto');
        if ($file) { try { $data['foto'] = Upload::image($file,'berita'); } catch(RuntimeException $e) { Response::redirect('/admin/berita/tambah.php',$e->getMessage(),'error'); } }
        berita_tambah($data);
        Response::redirect('/admin/berita/index.php','Berita berhasil disimpan.','success');
    })(),
    'edit' => (function(){
        $id = Request::int('id');
        if ($id<=0) Response::redirect('/admin/berita/index.php','ID tidak valid.','error');
        $old  = berita_findById($id);
        $data = ambilDataBerita();
        if (berita_slugExists($data['slug'],$id)) $data['slug'] .= '-' . $id;
        $file = Request::file('foto');
        if ($file) {
            try { $data['foto'] = Upload::image($file,'berita'); if ($old&&$old['foto']) Upload::hapus($old['foto'],'berita'); }
            catch(RuntimeException $e) { Response::redirect('/admin/berita/edit.php?id='.$id,$e->getMessage(),'error'); }
        }
        berita_edit($id,$data);
        Response::redirect('/admin/berita/index.php','Berita berhasil diperbarui.','success');
    })(),
    'hapus' => (function(){
        $id  = Request::int('id');
        $row = $id > 0 ? berita_findById($id) : null;
        if ($row && $row['foto']) Upload::hapus($row['foto'],'berita');
        berita_hapus($id);
        Response::redirect('/admin/berita/index.php','Berita berhasil dihapus.','success');
    })(),
    default => Response::redirect('/admin/berita/index.php','Aksi tidak dikenal.','error'),
};
