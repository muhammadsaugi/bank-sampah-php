

<?php
// 1. Definisikan BASE_URL supaya CSS header temanmu bisa ketemu jalannya
if (!defined('BASE_URL')) {
    define('BASE_URL', '/bank-sampah-php-main'); 
}

$pageTitle = "Layanan Kami";
$navActive = "layanan";

require_once '../templates/public/header.php';
?>

<style>
    .layanan-wrapper { 
        padding: 60px 0; 
        background-color: #f9f9f9; 
        min-height: 80vh; 
        font-family: 'Segoe UI', Tahoma, sans-serif; 
    }
    .layanan-header { text-align: center; margin-bottom: 50px; }
    .layanan-header h2 { color: #16a34a; font-size: 32px; margin-bottom: 10px; font-weight: bold; }
    
    .layanan-grid { 
        display: flex; 
        flex-wrap: wrap; 
        justify-content: center; 
        gap: 25px; 
        max-width: 1200px; 
        margin: 0 auto; 
        padding: 0 20px; 
        align-items: stretch; /* MEMAKSA SEMUA KARTU SAMA TINGGI */
    }

    .layanan-link {
        text-decoration: none; 
        color: inherit;
        display: flex; /* Biar link mengikuti tinggi grid */
    }

    .layanan-card { 
        background: #fff; 
        width: 320px; 
        border-radius: 12px; 
        overflow: hidden; 
        box-shadow: 0 5px 20px rgba(0,0,0,0.08); 
        transition: all 0.3s ease; 
        border-bottom: 5px solid #16a34a;
        
        /* MAGIC DISINI: Membuat isi kartu mengisi ruang kosong */
        display: flex;
        flex-direction: column;
    }

    .layanan-link:hover .layanan-card { 
        transform: translateY(-10px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
        border-bottom: 5px solid #004a8d;
    }

    .layanan-card img { 
        width: 100%; 
        height: 200px; 
        object-fit: cover; 
    }

    .layanan-content { 
        padding: 20px; 
        flex-grow: 1; /* MEMAKSA CONTENT MENGISI SISA RUANG SUPAYA TINGGI SAMA */
        display: flex;
        flex-direction: column;
    }

    .layanan-content h3 { color: #333; margin-bottom: 10px; font-size: 18px; font-weight: bold; }
    .layanan-content p { color: #666; font-size: 14px; line-height: 1.6; margin: 0; }
</style>

<main class="layanan-wrapper">
    <div class="layanan-header">
        <h2>LAYANAN KAMI</h2>
        <p>Klik pada kotak layanan untuk informasi lebih lanjut</p>
    </div>

    <div class="layanan-grid">
        
        <!-- 1. PIJAR -->
        <a href="<?= BASE_URL ?>/public/pijar.php" class="layanan-link">
            <div class="layanan-card">
                <img src="https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?w=500">
                <div class="layanan-content">
                    <h3>PIJAR</h3>
                    <p>Pilah Sampah Jual Dapat Rupiah. Ubah sampah keringmu menjadi saldo tabungan yang bermanfaat.</p>
                </div>
            </div>
        </a>

        <!-- 2. BU MASAROH -->
        <a href="<?= BASE_URL ?>/public/bu-masaroh.php" class="layanan-link">
            <div class="layanan-card">
                <img src="https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?q=80&w=2070&auto=format&fit=crop">
                <div class="layanan-content">
                    <h3>BU MASAROH</h3>
                    <p>Menabung Emas dari Uang Sampah. Investasi masa depan yang dikelola dari hasil pilah sampah.</p>
                </div>
            </div>
        </a>

        <!-- 3. KEMANA AKHIR KAIN KITA -->
        <a href="<?= BASE_URL ?>/public/kemana-akhir-kain-kita.php" class="layanan-link">
            <div class="layanan-card">
                <img src="https://images.unsplash.com/photo-1489987707025-afc232f7ea0f?q=80&w=2070&auto=format&fit=crop">
                <div class="layanan-content">
                    <h3>KEMANA AKHIR KAIN KITA</h3>
                    <p>Setor Kainmu Sehat Lingkunganku. Penanganan limbah tekstil agar tidak mencemari lingkungan.</p>
                </div>
            </div>
        </a>

        <!-- 4. EDUKASI -->
        <a href="<?= BASE_URL ?>/public/edukasi-pelatihan.php" class="layanan-link">
            <div class="layanan-card">
                <img src="https://images.unsplash.com/photo-1524178232363-1fb2b075b655?w=500">
                <div class="layanan-content">
                    <h3>EDUKASI & PELATIHAN</h3>
                    <p>Workshop bimbingan teknis dan pelatihan kreatif pemanfaatan daur ulang sampah.</p>
                </div>
            </div>
        </a>

        <!-- 5. KEMITRAAN -->
        <a href="<?= BASE_URL ?>/public/kemitraan.php" class="layanan-link">
            <div class="layanan-card">
                <img src="https://images.unsplash.com/photo-1521791136064-7986c2920216?w=500">
                <div class="layanan-content">
                    <h3>KEMITRAAN</h3>
                    <p>Kerjasama strategis dengan sektor industri dan instansi dalam pengelolaan limbah.</p>
                </div>
            </div>
        </a>

    </div>
</main>

<?php require_once '../templates/public/footer.php'; ?>