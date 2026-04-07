<?php
// ─── core/Upload.php ───
// Pengelolaan upload file: validasi MIME, ekstensi, ukuran, simpan, hapus.

class Upload
{
    private static array $allowedMime = [
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png',
        'webp' => 'image/webp',
    ];

    /**
     * Proses upload file gambar ke folder yang ditentukan.
     *
     * @param array  $file     Elemen dari $_FILES['namafield']
     * @param string $subfolder Subfolder dalam UPLOAD_PATH (e.g. 'berita', 'galeri')
     * @return string Nama file hasil upload
     * @throws RuntimeException jika validasi gagal atau upload gagal
     */
    public static function image(array $file, string $subfolder): string
    {
        // Cek error upload dasar
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload gagal. Kode error: ' . $file['error']);
        }

        // Validasi ukuran file
        if ($file['size'] > UPLOAD_MAX_SIZE) {
            throw new RuntimeException('Ukuran file maksimal 2MB.');
        }

        // Ambil ekstensi dari nama file asli
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Validasi ekstensi
        if (!array_key_exists($ext, self::$allowedMime)) {
            throw new RuntimeException('Format file tidak didukung. Gunakan JPG, PNG, atau WebP.');
        }

        // Validasi MIME type dari isi file (bukan dari $_FILES['type'] yang bisa dipalsukan)
        $finfo    = new finfo(FILEINFO_MIME_TYPE);
        $mimeReal = $finfo->file($file['tmp_name']);

        if ($mimeReal !== self::$allowedMime[$ext]) {
            throw new RuntimeException('Tipe file tidak valid. Pastikan file adalah gambar.');
        }

        // Generate nama file unik
        $namaFile = generateFilename($ext);

        // Pastikan folder tujuan ada
        $folder = UPLOAD_PATH . '/' . trim($subfolder, '/');
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        // Pindahkan file dari temp
        $tujuan = $folder . '/' . $namaFile;
        if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
            throw new RuntimeException('Gagal menyimpan file. Periksa izin folder uploads/.');
        }

        return $namaFile;
    }

    /**
     * Hapus file dari subfolder.
     * Tidak melempar exception jika file tidak ditemukan.
     */
    public static function hapus(string $namaFile, string $subfolder): bool
    {
        if (empty($namaFile)) return false;

        $path = UPLOAD_PATH . '/' . trim($subfolder, '/') . '/' . basename($namaFile);
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    /**
     * Generate URL akses gambar via serve-file.php.
     * (atau langsung via uploads/ jika folder accessible)
     */
    public static function url(string $namaFile, string $subfolder): string
    {
        if (empty($namaFile)) return '';
        return BASE_URL . '/uploads/' . trim($subfolder, '/') . '/' . e($namaFile);
    }
}
