<?php
// ─── admin/users/proses.php ───
require_once dirname(__DIR__, 2) . '/config/app.php';
require_once dirname(__DIR__, 2) . '/config/session.php';
require_once dirname(__DIR__, 2) . '/config/database.php';
require_once dirname(__DIR__, 2) . '/core/Auth.php';
require_once dirname(__DIR__, 2) . '/core/CSRF.php';
require_once dirname(__DIR__, 2) . '/core/Request.php';
require_once dirname(__DIR__, 2) . '/core/Response.php';
require_once dirname(__DIR__, 2) . '/core/Validator.php';
require_once dirname(__DIR__, 2) . '/models/UserModel.php';

Auth::cekSession();
Auth::cekRole(['super_admin']);
CSRF::verify(Request::post('csrf_token'));

$aksi = Request::post('aksi');

match ($aksi) {
    'tambah' => (function () {
        $data = [
            'nama'     => Request::str('nama'),
            'username' => Request::str('username'),
            'email'    => Request::str('email'),
            'role'     => Request::str('role'),
        ];
        $password = $_POST['password'] ?? '';

        $v = new Validator(array_merge($data, ['password' => $password]));
        $v->required('nama','Nama')->required('username','Username')
          ->required('email','Email')->required('password','Password')
          ->email('email')->minLength('password', 8, 'Password');

        if ($v->hasErrors()) {
            Response::redirect('/admin/users/tambah.php', $v->firstError(), 'error');
        }

        if (user_usernameExists($data['username'])) {
            Response::redirect('/admin/users/tambah.php', 'Username sudah digunakan.', 'error');
        }
        if (user_emailExists($data['email'])) {
            Response::redirect('/admin/users/tambah.php', 'Email sudah digunakan.', 'error');
        }

        $data['password']   = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $data['dibuat_oleh'] = Auth::getUserId();

        user_create($data);
        Response::redirect('/admin/users/index.php', 'User berhasil ditambahkan.', 'success');
    })(),

    'edit' => (function () {
        $id = Request::int('id');
        if ($id <= 0) Response::redirect('/admin/users/index.php', 'ID tidak valid.', 'error');

        $data = [
            'nama'  => Request::str('nama'),
            'email' => Request::str('email'),
            'role'  => Request::str('role'),
            'aktif' => Request::int('aktif'),
        ];

        if (empty($data['nama']) || empty($data['email'])) {
            Response::redirect('/admin/users/edit.php?id=' . $id, 'Nama dan email wajib diisi.', 'error');
        }

        if (user_emailExists($data['email'], $id)) {
            Response::redirect('/admin/users/edit.php?id=' . $id, 'Email sudah digunakan user lain.', 'error');
        }

        $password = $_POST['password'] ?? '';
        if ($password !== '') {
            if (strlen($password) < 8) {
                Response::redirect('/admin/users/edit.php?id=' . $id, 'Password minimal 8 karakter.', 'error');
            }
            $data['password'] = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        }

        user_update($id, $data);
        Response::redirect('/admin/users/index.php', 'User berhasil diperbarui.', 'success');
    })(),

    'hapus' => (function () {
        $id = Request::int('id');
        if ($id === Auth::getUserId()) {
            Response::redirect('/admin/users/index.php', 'Tidak bisa menghapus akun sendiri.', 'error');
        }
        user_delete($id);
        Response::redirect('/admin/users/index.php', 'User berhasil dihapus.', 'success');
    })(),

    default => Response::redirect('/admin/users/index.php', 'Aksi tidak dikenal.', 'error'),
};
