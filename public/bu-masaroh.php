<?php
if (!defined('BASE_URL')) { define('BASE_URL', '/bank-sampah-php-main'); }
$pageTitle = "Layanan BU MASAROH";
$navActive = "layanan"; 
require_once '../templates/public/header.php';
?>

<style>
/* CSS DISAMAKAN DENGAN STRUKTUR SEBELUMNYA */
#bu-masaroh-content { font-family: 'Segoe UI', sans-serif; background: #fdfdfd; color: #333; line-height: 1.6; }

#bu-masaroh-content .hero-layanan {
   background: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1591604129939-f1efa4d9f7fa?q=80&w=2070&auto=format&fit=crop');
   
   background-size: cover;      /* Menutup seluruh area hero */
    background-position: center;  /* Gambar fokus di tengah */
    background-repeat: no-repeat;

   padding: 100px 0; 
    color: white; 
    text-align: center;
}

#bu-masaroh-content .hero-layanan h1 { color: #ffffff !important; font-size: 3rem !important; font-weight: 800 !important; text-shadow: 2px 2px 10px rgba(0,0,0,0.5) !important; margin-bottom: 10px !important; }
#bu-masaroh-content .hero-layanan p { color: #ffffff !important; font-weight: 600 !important; letter-spacing: 2px !important; border-top: 2px solid #ffd700; border-bottom: 2px solid #ffd700; display: inline-block; padding: 5px 25px; text-transform: uppercase; }
#bu-masaroh-content .container-layanan { max-width: 1100px; margin: auto; padding: 0 20px; }
#bu-masaroh-content .section-title { color: #004a8d; font-weight: 700; margin: 50px 0 20px; padding-left: 15px; border-left: 5px solid #16a34a; }
#bu-masaroh-content .alur-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 40px 0; }
#bu-masaroh-content .alur-card { background: #16a34a; color: white; padding: 30px 20px; border-radius: 15px; text-align: center; }
#bu-masaroh-content .step-number { background: white; color: #16a34a; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; margin: 0 auto 15px; }
#bu-masaroh-content .contact-section { background: white; padding: 50px; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); margin: 50px 0 80px; text-align: center; border: 1px solid #f0f0f0; }

#bu-masaroh-content .btn-whatsapp { 
    display: inline-flex; align-items: center; justify-content: center; 
    background: #25D366; color: white; padding: 15px 40px; border-radius: 50px; 
    font-weight: bold; text-decoration: none; transition: 0.3s; font-size: 1.1rem; border: none; cursor: pointer; 
}
#bu-masaroh-content .btn-whatsapp:hover { background: #128c7e; transform: translateY(-3px); color: white; box-shadow: 0 5px 15px rgba(37, 211, 102, 0.4); }

#bu-masaroh-content .wa-icon {
    width: 24px; height: 24px; margin-right: 12px;
    background-image: url('data:image/svg+xml;charset=utf-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="%23ffffff" d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-5.5-2.8-23.2-8.5-44.2-27.1-16.4-14.6-27.4-32.7-30.6-38.1-3.2-5.5-.3-8.5 2.4-11.2 2.5-2.4 5.5-6.5 8.3-9.7 2.8-3.3 3.7-5.5 5.5-9.2 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 13.2 5.8 23.5 9.2 31.6 11.8 13.3 4.2 25.4 3.6 35 2.2 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>');
    background-repeat: no-repeat; background-position: center; background-size: contain;
}
</style>

<div id="bu-masaroh-content">
    <section class="hero-layanan">
        <div class="container-layanan">
            <h1>LAYANAN BU MASAROH</h1>
            <p>Buku Menabung Sampah Menjadi Umroh</p>
        </div>
    </section>

    <div class="container-layanan">
        <h2 class="section-title">Apa itu BU MASAROH?</h2>
        <p style="font-size: 1.1rem; color: #555; text-align: justify;">Bu Masaroh ini merupakan program khusus BSI yang bekerjasama dengan PT.Pegadaian. Dalam program Syafar pengurus BSI dan BSU atau masyarakat pada umumnya bisa mengikuti program ini dengan membuka rekening Tabungan Emas (TE) dari uang sampah yang dihasilkan. </p>

        <h2 class="section-title">Cara Kerja BU MASAROH</h2>
        <div class="alur-container">
            <div class="alur-card"><div class="step-number">1</div><p>Calon nasabah bisa membuka rekening TE melalui BSI. Selanjutnya petugas BSI mengajukan voucher kepada FORSEPSI.
Calon nasabah mengisi formulir beserta persyaratan lainnya termasuk voucher dari FORSEPSI.
Manfaat voucher ini adalah bebas admin</p></div>
            <div class="alur-card"><div class="step-number">2</div><p>Petugas BSI melaporkan kepada PT.Pegadaian untuk membuka rekening TE dalam program Syafar.</p></div>
            <div class="alur-card"><div class="step-number">3</div><p>Petugas BSI menyerahkan buku tabungan emas kepada nasabah. Untuk tabungan selanjutnya bisa dilakukan bersama-sama melalui BSI atau nasabang bisa nabung sendiri melalaui tring.</p></div>
            <div class="alur-card"><div class="step-number">4</div><p>Jadwal keberangkatan ibadah umroh bisa disepakati bersama ketika tabungan emas sudah mencapai minimal 25 % dari total biaya umroh. Selebihkan difasilitas pinjaman oleh PT.Pegadaian  sesuai dengan ketentuan yang berlaku.</p></div>
        </div>

        <div class="contact-section">
            <h3 style="margin-bottom: 15px;">Mulai Kelola Investasi Anda</h3>
            <p style="margin-bottom: 25px;">Hubungi Admin kami untuk pendaftaran program BU MASAROH.</p>
            <a href="https://wa.me/6281330325911?text=Halo%20Admin%20BSI%2C%20saya%20ingin%20tanya%20mengenai%20layanan%20BU%20MASAROH." target="_blank" class="btn-whatsapp">
    <span class="wa-icon"></span> Hubungi Admin BU MASAROH
</a>
            </a>
        </div>
    </div>
</div>

<?php require_once '../templates/public/footer.php'; ?>