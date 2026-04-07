# PANDUAN SETUP CEPAT

## 1. Konfigurasi .env
Copy file `.env.example` menjadi `.env`, lalu sesuaikan:

```
BASE_URL=http://localhost/bank-sampah-induk
DB_NAME=bsi_db
DB_USER=root
DB_PASS=            ← kosongkan jika tidak ada password
```

## 2. Database
- Import file `database.sql` ke phpMyAdmin
- Nama database: `bsi_db`

## 3. Buat akun admin
Akses: http://localhost/bank-sampah-induk/seed.php
Lalu HAPUS file seed.php setelah berhasil.

## 4. Login
URL: http://localhost/bank-sampah-induk/auth/login.php

| Username    | Password         | Role              |
|-------------|------------------|-------------------|
| superadmin  | SuperAdmin123!   | Super Admin       |
| admin_data  | AdminData123!    | Admin Data        |
| admin_ops   | AdminOps123!     | Admin Operasional |

## TROUBLESHOOT
Jika muncul "Terjadi Kesalahan Sistem":
1. Pastikan file .env sudah dibuat dari .env.example
2. Pastikan DB_NAME = bsi_db (sesuai phpMyAdmin Anda)
3. Aktifkan APP_DEBUG=true sementara untuk lihat error
