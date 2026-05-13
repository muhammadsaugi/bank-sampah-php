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
    
    // PENGAMANAN XSS: Hanya izinkan tag HTML dasar untuk artikel, buang script berbahaya!
    $isiRaw  = $_POST['isi'] ?? '';
    $isiAman = strip_tags($isiRaw, '<p><a><strong><b><em><i><u><ul><ol><li><br><h1><h2><h3><h4><h5><h6><blockquote>');

    return [
        'judul'    => $judul,
        'slug'     => $slug,
        'isi'      => $isiAman, // Gunakan isi yang sudah dibersihkan
        'kategori' => Request::str('kategori'),
        'tags'     => Request::str('tags'),
        'status'   => Request::str('status') === 'publish' ? 'publish' : 'draft',
    ];
}

$aksi = Request::post('aksi');

match($aksi) {
    'tambah' => (function(){
        $data = ambilDataBerita();

        // Validasi wajib isi
        if (empty($data['judul']) || empty($data['isi'])) {
            Response::redirect('/admin/berita/tambah.php', 'Judul dan Isi Berita wajib diisi!', 'error');
        }

        // Cek jika slug sudah dipakai berita lain, tambahkan angka random agar unik
        if (berita_slugExists($data['slug'], 0)) {
            $data['slug'] .= '-' . time();
        }

        $file = Request::file('foto');
        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($file['size'] > 2097152) { // Maks 2MB
                Response::redirect('/admin/berita/tambah.php', 'Ukuran foto maksimal 2MB!', 'error');
            }
            try { 
                $data['foto'] = Upload::image($file,'berita'); 
            } catch(RuntimeException $e) { 
                Response::redirect('/admin/berita/tambah.php',$e->getMessage(),'error'); 
            }
        }

        berita_tambah($data);
        Response::redirect('/admin/berita/index.php','Berita berhasil disimpan.','success');
    })(),
    
    'edit' => (function(){
        $id = Request::int('id');
        if ($id<=0) Response::redirect('/admin/berita/index.php','ID tidak valid.','error');
        
        $old  = berita_findById($id);
        $data = ambilDataBerita();

        // Validasi wajib isi
        if (empty($data['judul']) || empty($data['isi'])) {
            Response::redirect('/admin/berita/edit.php?id='.$id, 'Judul dan Isi Berita wajib diisi!', 'error');
        }

        // Cek duplikasi slug untuk aksi edit
        if (berita_slugExists($data['slug'], $id)) {
            $data['slug'] .= '-' . $id;
        }

        $file = Request::file('foto');
        if ($file && $file['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($file['size'] > 2097152) { // Maks 2MB
                Response::redirect('/admin/berita/edit.php?id='.$id, 'Ukuran foto maksimal 2MB!', 'error');
            }
            try { 
                $data['foto'] = Upload::image($file,'berita'); 
                if ($old && $old['foto']) Upload::hapus($old['foto'],'berita'); 
            } catch(RuntimeException $e) { 
                Response::redirect('/admin/berita/edit.php?id='.$id,$e->getMessage(),'error'); 
            }
        }

        berita_edit($id,$data);
        Response::redirect('/admin/berita/index.php','Berita berhasil diperbarui.','success');
    })(),
    
    'hapus' => (function(){
        $id  = Request::int('id');
        $row = $id > 0 ? berita_findById($id) : null;
        if ($row && $row['foto']) Upload::hapus($row['foto'],'berita');
        if ($row) berita_hapus($id);
        Response::redirect('/admin/berita/index.php','Berita berhasil dihapus.','success');
    })(),
    
    default => Response::redirect('/admin/berita/index.php','Aksi tidak valid.','error')
};