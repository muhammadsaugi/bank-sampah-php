<?php
// ─── core/Helper.php ───
// Fungsi global reusable: escape, format, slug, dll.

/**
 * Escape output HTML — wajib dipakai di semua echo ke template.
 */
function e(mixed $val): string
{
    return htmlspecialchars((string)$val, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Format angka ke Rupiah.
 * Contoh: 150000 → "Rp 150.000"
 */
function formatRupiah(float|int|string $angka): string
{
    return 'Rp ' . number_format((float)$angka, 0, ',', '.');
}

/**
 * Format tanggal dari string Y-m-d ke format yang diinginkan.
 * Default: "12 Jan 2024"
 */
function formatTanggal(string $tanggal, string $format = 'd M Y'): string
{
    if (empty($tanggal) || $tanggal === '0000-00-00') return '-';
    try {
        return (new DateTime($tanggal))->format($format);
    } catch (Exception) {
        return $tanggal;
    }
}

/**
 * Buat slug URL-safe dari string.
 * Contoh: "Bank Sampah #1" → "bank-sampah-1"
 */
function buatSlug(string $teks): string
{
    // Transliterasi huruf beraksara latin
    $teks = mb_strtolower(trim($teks), 'UTF-8');
    // Ganti karakter umum Indonesia
    $map  = ['á'=>'a','à'=>'a','ä'=>'a','â'=>'a','ã'=>'a',
             'é'=>'e','è'=>'e','ë'=>'e','ê'=>'e',
             'í'=>'i','ì'=>'i','ï'=>'i','î'=>'i',
             'ó'=>'o','ò'=>'o','ö'=>'o','ô'=>'o','õ'=>'o',
             'ú'=>'u','ù'=>'u','ü'=>'u','û'=>'u',
             'ñ'=>'n','ç'=>'c'];
    $teks = strtr($teks, $map);
    $teks = preg_replace('/[^a-z0-9\s\-]/', '', $teks);
    $teks = preg_replace('/[\s\-]+/', '-', $teks);
    return trim($teks, '-');
}

/**
 * Potong teks panjang, tambah "..." di akhir.
 */
function truncate(string $teks, int $panjang = 150): string
{
    $teks = strip_tags($teks);
    return mb_strlen($teks) > $panjang
        ? mb_substr($teks, 0, $panjang) . '...'
        : $teks;
}

/**
 * Sanitasi HTML: hanya izinkan tag aman (untuk isi berita).
 * Tag yang diizinkan: p, br, strong, em, ul, ol, li, a (href saja)
 */
function sanitizeHtml(string $html): string
{
    // Strip semua atribut berbahaya
    $html = preg_replace('/(<[a-z][a-z0-9]*)\s+[^>]*(href\s*=\s*["\'][^"\']*["\'])?[^>]*/i', '$1$2', $html);

    // Izinkan hanya tag tertentu
    return strip_tags($html, '<p><br><strong><em><ul><ol><li><a><h2><h3><blockquote>');
}

/**
 * Buat inisial dari nama (untuk avatar).
 * Contoh: "Ahmad Budi" → "AB"
 */
function inisialNama(string $nama): string
{
    $kata = explode(' ', trim($nama));
    if (count($kata) >= 2) {
        return strtoupper(mb_substr($kata[0], 0, 1) . mb_substr($kata[1], 0, 1));
    }
    return strtoupper(mb_substr($nama, 0, 2));
}

/**
 * Kembalikan label badge HTML untuk status rekap/kegiatan/berita.
 */
function badgeStatus(string $status): string
{
    $map = [
        'akan_datang'  => ['class' => 'badge-info',    'label' => 'Akan Datang'],
        'berlangsung'  => ['class' => 'badge-berlangsung', 'label' => 'Berlangsung'],
        'selesai'      => ['class' => 'badge-success', 'label' => 'Selesai'],
        'draft'        => ['class' => 'badge-draft',   'label' => 'Draft'],
        'publish'      => ['class' => 'badge-success', 'label' => 'Publish'],
    ];
    $d = $map[$status] ?? ['class' => 'badge-gray', 'label' => ucfirst($status)];
    return '<span class="badge ' . $d['class'] . '">' . $d['label'] . '</span>';
}

/**
 * Kembalikan label badge HTML untuk role user.
 */
function badgeRole(string $role): string
{
    $map = [
        'super_admin'       => ['class' => 'badge-purple',  'label' => 'Super Admin'],
        'admin_data'        => ['class' => 'badge-success', 'label' => 'Admin Data'],
        'admin_operasional' => ['class' => 'badge-info',    'label' => 'Admin Ops'],
    ];
    $d = $map[$role] ?? ['class' => 'badge-gray', 'label' => $role];
    return '<span class="badge ' . $d['class'] . '">' . $d['label'] . '</span>';
}

/**
 * Generate unique filename untuk upload.
 * Format: uniqid_timestamp.ext
 */
function generateFilename(string $ext): string
{
    return uniqid('', true) . '_' . time() . '.' . strtolower($ext);
}

/**
 * Hitung persentase (aman dari division by zero).
 */
function persen(float $bagian, float $total): float
{
    if ($total <= 0) return 0;
    return round(($bagian / $total) * 100, 1);
}
