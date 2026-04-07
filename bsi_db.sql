-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 07, 2026 at 03:14 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bsi_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bank_sampah`
--

CREATE TABLE `bank_sampah` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelurahan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ketua` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kontak` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_berdiri` year DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `dibuat_oleh` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_sampah`
--

INSERT INTO `bank_sampah` (`id`, `nama`, `alamat`, `kelurahan`, `kecamatan`, `ketua`, `kontak`, `tahun_berdiri`, `aktif`, `dibuat_oleh`, `created_at`, `updated_at`) VALUES
(1, 'BSU Maju Bersama', 'Jl. Raya Kedungsari No. 5', 'Kedungsari', 'Magersari', 'Siti Aminah', '08123456789', 2018, 1, NULL, '2026-03-16 22:02:53', NULL),
(2, 'BSU Bersih Mandiri', 'Jl. Pahlawan No. 12', 'Gunung Gedangan', 'Magersari', 'Bambang Eko', '08234567890', 2019, 1, NULL, '2026-03-16 22:02:53', NULL),
(3, 'BSU Hijau Lestari', 'Jl. Benteng Pancasila No. 3', 'Mentikan', 'Prajurit Kulon', 'Dewi Rahayu', '08345678901', 2020, 1, NULL, '2026-03-16 22:02:53', NULL),
(4, 'BSU Sejahtera Bersama', 'Jl. Irian Jaya No. 7', 'Blooto', 'Prajurit Kulon', 'Ahmad Fauzi', '08456789012', 2017, 1, NULL, '2026-03-16 22:02:53', NULL),
(5, 'BSU Peduli Lingkungan', 'Jl. Gajah Mada No. 22', 'Kranggan', 'Kranggan', 'Rina Wulandari', '08567890123', 2021, 1, NULL, '2026-03-16 22:02:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi` longtext COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kategori` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','publish') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `slug`, `isi`, `foto`, `kategori`, `tags`, `status`) VALUES
(1, 'BSI Mojokerto Tambah 5 Unit Bank Sampah Baru di Tahun 2025', 'bsi-mojokerto-tambah-5-unit-bank-sampah-baru-2025', '<p>Bank Sampah Induk (BSI) Kota Mojokerto berhasil menambah 5 unit bank sampah baru yang tersebar di berbagai kelurahan. Penambahan ini merupakan bagian dari program pengembangan jaringan bank sampah yang digalakkan oleh Pemerintah Kota Mojokerto.</p><p>Kelima bank sampah unit baru tersebut telah melalui proses pendampingan dan pelatihan selama 3 bulan sebelum resmi beroperasi. Diharapkan kehadiran mereka dapat meningkatkan volume sampah yang tertangani di wilayah masing-masing.</p>', NULL, 'Kelembagaan', NULL, 'publish'),
(2, 'Harga Sampah Plastik Naik Signifikan di Kuartal Pertama 2025', 'harga-sampah-plastik-naik-kuartal-pertama-2025', '<p>Memasuki kuartal pertama tahun 2025, harga sampah plastik jenis PET mengalami kenaikan yang cukup signifikan. BSI Mojokerto mencatat harga botol PET bening kini mencapai Rp 3.500 per kilogram, naik dari sebelumnya Rp 2.800 per kilogram.</p><p>Kenaikan ini dipicu oleh meningkatnya permintaan bahan baku plastik daur ulang dari industri pengolahan.</p>', NULL, 'Harga & Pasar', NULL, 'publish'),
(3, 'Cara Memilah Sampah yang Benar untuk Maksimalkan Nilai Ekonomi', 'cara-memilah-sampah-yang-benar', '<p>Memilah sampah dengan benar adalah kunci untuk mendapatkan nilai ekonomi yang optimal dari sampah yang kita hasilkan. Berikut panduan lengkap cara memilah sampah yang tepat.</p><p><strong>Sampah Plastik:</strong> Pastikan sampah plastik dalam keadaan kering dan bersih. Pisahkan berdasarkan jenis: PET, HDPE, LDPE. Botol plastik sebaiknya digepengkan untuk menghemat tempat.</p><p><strong>Sampah Kertas:</strong> Kertas harus dalam kondisi kering, tidak terkena minyak atau makanan. Pisahkan antara koran, HVS, kardus, dan majalah.</p>', NULL, 'Edukasi', NULL, 'publish');

-- --------------------------------------------------------

--
-- Table structure for table `detail_rekap_sampah`
--

CREATE TABLE `detail_rekap_sampah` (
  `id` int UNSIGNED NOT NULL,
  `id_rekap` int UNSIGNED NOT NULL,
  `id_nama_sampah` int UNSIGNED NOT NULL,
  `berat` decimal(8,2) NOT NULL,
  `harga_satuan` decimal(12,2) NOT NULL COMMENT 'Snapshot harga saat input',
  `subtotal` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `detail_rekap_sampah`
--

INSERT INTO `detail_rekap_sampah` (`id`, `id_rekap`, `id_nama_sampah`, `berat`, `harga_satuan`, `subtotal`) VALUES
(1, 1, 14, '12.00', '0.00', '0.00'),
(2, 1, 9, '10.00', '0.00', '0.00'),
(3, 2, 9, '15.00', '0.00', '0.00'),
(4, 2, 9, '15.00', '0.00', '0.00'),
(5, 3, 8, '17.00', '1000.00', '17000.00'),
(6, 3, 14, '25.00', '500.00', '12500.00'),
(7, 3, 6, '11.00', '1200.00', '13200.00'),
(8, 4, 8, '10.00', '1000.00', '10000.00'),
(9, 4, 14, '50.00', '500.00', '25000.00');

-- --------------------------------------------------------

--
-- Table structure for table `galeri`
--

CREATE TABLE `galeri` (
  `id` int UNSIGNED NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date DEFAULT NULL,
  `kategori` enum('Kunjungan','Edukasi','Produk Daur Ulang','Kegiatan') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kegiatan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `harga_sampah`
--

CREATE TABLE `harga_sampah` (
  `id` int UNSIGNED NOT NULL,
  `id_nama_sampah` int UNSIGNED NOT NULL,
  `harga` decimal(12,2) NOT NULL,
  `tanggal_update` date NOT NULL,
  `diupdate_oleh` int UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `harga_sampah`
--

INSERT INTO `harga_sampah` (`id`, `id_nama_sampah`, `harga`, `tanggal_update`, `diupdate_oleh`) VALUES
(1, 1, '3500.00', '2026-03-17', NULL),
(2, 2, '1500.00', '2026-03-17', NULL),
(3, 3, '2000.00', '2026-03-17', NULL),
(4, 4, '500.00', '2026-03-17', NULL),
(5, 5, '1500.00', '2026-03-17', NULL),
(6, 6, '1200.00', '2026-03-17', NULL),
(7, 7, '1500.00', '2026-03-17', NULL),
(8, 8, '1000.00', '2026-03-17', NULL),
(9, 9, '800.00', '2026-03-17', NULL),
(10, 10, '2500.00', '2026-03-17', NULL),
(11, 11, '10000.00', '2026-03-17', NULL),
(12, 12, '50000.00', '2026-03-17', NULL),
(13, 13, '1500.00', '2026-03-17', NULL),
(14, 14, '500.00', '2026-03-17', NULL),
(15, 15, '300.00', '2026-03-17', NULL),
(16, 16, '11000.00', '2026-04-04', 3),
(17, 17, '50000.00', '2026-03-17', NULL),
(18, 18, '8000.00', '2026-03-17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `jenis_sampah`
--

CREATE TABLE `jenis_sampah` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kode` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `warna` varchar(7) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#16A34A',
  `dibuat_oleh` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_sampah`
--

INSERT INTO `jenis_sampah` (`id`, `nama`, `kode`, `warna`, `dibuat_oleh`, `created_at`) VALUES
(1, 'Plastik', 'PLT', '#3B82F6', NULL, '2026-03-16 22:02:53'),
(2, 'Kertas', 'KRT', '#F59E0B', NULL, '2026-03-16 22:02:53'),
(3, 'Logam', 'LGM', '#6B7280', NULL, '2026-03-16 22:02:53'),
(4, 'Kaca', 'KCA', '#14B8A6', NULL, '2026-03-16 22:02:53'),
(5, 'Elektronik', 'ELK', '#8B5CF6', NULL, '2026-03-16 22:02:53'),
(6, 'Organik', 'ORG', '#16A34A', NULL, '2026-03-16 22:02:53');

-- --------------------------------------------------------

--
-- Table structure for table `kegiatan`
--

CREATE TABLE `kegiatan` (
  `id` int UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `jenis_kegiatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `lokasi` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('akan_datang','berlangsung','selesai') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'akan_datang'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kegiatan`
--

INSERT INTO `kegiatan` (`id`, `judul`, `jenis_kegiatan`, `tanggal`, `tanggal_selesai`, `lokasi`, `deskripsi`, `foto`, `status`) VALUES
(1, 'Sosialisasi Pemilahan Sampah RT/RW', 'Edukasi', '2026-03-02', NULL, 'Balai RW 03 Kedungsari', 'Kegiatan sosialisasi pemilahan sampah organik dan anorganik kepada warga RW 03 Kelurahan Kedungsari. Dihadiri 45 peserta.', NULL, 'selesai'),
(2, 'Pelatihan Bank Sampah Unit Baru', 'Pelatihan', '2026-03-10', NULL, 'Kantor BSI Kota Mojokerto', 'Pelatihan operasional bank sampah untuk 3 unit bank sampah baru yang baru terdaftar.', NULL, 'selesai'),
(3, 'Kunjungan Dinas Lingkungan Hidup', 'Kunjungan', '2026-03-17', NULL, 'Kantor BSI Kota Mojokerto', 'Kunjungan monitoring dari Dinas Lingkungan Hidup Kota Mojokerto terkait perkembangan jaringan bank sampah.', NULL, 'berlangsung'),
(4, 'Expo Produk Daur Ulang Mojokerto', 'Pameran', '2026-03-31', NULL, 'Alun-alun Kota Mojokerto', 'Pameran produk kreasi daur ulang dari bank sampah unit se-Kota Mojokerto. Terbuka untuk umum.', NULL, 'akan_datang');

-- --------------------------------------------------------

--
-- Table structure for table `nama_sampah`
--

CREATE TABLE `nama_sampah` (
  `id` int UNSIGNED NOT NULL,
  `id_jenis` int UNSIGNED NOT NULL,
  `nama` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `satuan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kg',
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `dibuat_oleh` int UNSIGNED DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `nama_sampah`
--

INSERT INTO `nama_sampah` (`id`, `id_jenis`, `nama`, `satuan`, `keterangan`, `dibuat_oleh`, `created_at`) VALUES
(1, 1, 'Botol PET Bening', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(2, 1, 'Botol PET Berwarna', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(3, 1, 'Plastik Keras (HD)', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(4, 1, 'Kresek', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(5, 1, 'Ember Plastik', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(6, 2, 'Koran', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(7, 2, 'Kertas HVS', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(8, 2, 'Kardus', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(9, 2, 'Majalah/Buku', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(10, 3, 'Besi', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(11, 3, 'Aluminium', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(12, 3, 'Tembaga', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(13, 3, 'Seng', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(14, 4, 'Botol Kaca', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(15, 4, 'Pecahan Kaca', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(16, 5, 'HP/Smartphone', 'Unit', NULL, NULL, '2026-03-16 22:02:53'),
(17, 5, 'Laptop/Komputer', 'Unit', NULL, NULL, '2026-03-16 22:02:53'),
(18, 5, 'Kabel', 'Kg', NULL, NULL, '2026-03-16 22:02:53'),
(19, 2, 'kertas karton', 'Kg', '', 2, '2026-04-04 06:30:29');

-- --------------------------------------------------------

--
-- Table structure for table `profil_kelembagaan`
--

CREATE TABLE `profil_kelembagaan` (
  `id` int UNSIGNED NOT NULL,
  `nama_lembaga` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Bank Sampah Induk Kota Mojokerto',
  `tagline` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `visi` text COLLATE utf8mb4_unicode_ci,
  `misi` text COLLATE utf8mb4_unicode_ci,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tahun_berdiri` year DEFAULT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `profil_kelembagaan`
--

INSERT INTO `profil_kelembagaan` (`id`, `nama_lembaga`, `tagline`, `deskripsi`, `visi`, `misi`, `alamat`, `telepon`, `email`, `website`, `tahun_berdiri`, `logo`) VALUES
(1, 'Bank Sampah Induk Kota Mojokerto', 'Mengelola Sampah, Membangun Nilai', 'Bank Sampah Induk (BSI) Kota Mojokerto adalah lembaga pengelola sampah terpadu yang bertugas mengkoordinasikan jaringan bank sampah unit di seluruh wilayah Kota Mojokerto. BSI berperan sebagai pusat pengumpulan, pemilahan, dan penjualan sampah hasil dari bank sampah unit di bawah koordinasinya.', 'Terwujudnya pengelolaan sampah yang berwawasan lingkungan, berkelanjutan, dan memberdayakan masyarakat Kota Mojokerto menuju kota yang bersih, sehat, dan sejahtera.', 'Mengembangkan sistem pengelolaan sampah berbasis masyarakat yang terintegrasi dan berkelanjutan\nMeningkatkan kesadaran masyarakat tentang pentingnya pengelolaan sampah yang bertanggung jawab\nMemberdayakan ekonomi masyarakat melalui pengelolaan sampah yang bernilai ekonomi\nMemperkuat jaringan bank sampah unit di seluruh wilayah Kota Mojokerto\nMendorong inovasi dalam pengelolaan sampah dan daur ulang', 'Jl. Hayam Wuruk No. 71, Kota Mojokerto, Jawa Timur 61321', '(0321) 394444', 'bsi@mojokerto.go.id', NULL, 2015, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rekap_sampah`
--

CREATE TABLE `rekap_sampah` (
  `id` int UNSIGNED NOT NULL,
  `id_bank_sampah` int UNSIGNED NOT NULL,
  `id_user` int UNSIGNED DEFAULT NULL,
  `tanggal` date NOT NULL,
  `total_berat` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_harga` decimal(14,2) NOT NULL DEFAULT '0.00',
  `sumber_data` enum('manual','import_csv','import_excel') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rekap_sampah`
--

INSERT INTO `rekap_sampah` (`id`, `id_bank_sampah`, `id_user`, `tanggal`, `total_berat`, `total_harga`, `sumber_data`, `catatan`, `created_at`) VALUES
(1, 2, 2, '2026-03-29', '22.00', '0.00', 'manual', 'halo', '2026-03-29 13:23:07'),
(2, 3, 1, '2026-03-30', '30.00', '0.00', 'manual', '', '2026-03-30 02:04:39'),
(3, 5, 2, '2026-04-01', '53.00', '42700.00', 'manual', '', '2026-04-01 10:26:40'),
(4, 2, 2, '2026-04-04', '60.00', '35000.00', 'manual', '', '2026-04-04 06:31:02');

-- --------------------------------------------------------

--
-- Table structure for table `struktur_organisasi`
--

CREATE TABLE `struktur_organisasi` (
  `id` int UNSIGNED NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `periode` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keterangan` text COLLATE utf8mb4_unicode_ci,
  `urutan` tinyint UNSIGNED NOT NULL DEFAULT '0',
  `aktif` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int UNSIGNED NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin_data','admin_operasional','super_admin') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin_data',
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dibuat_oleh` int UNSIGNED DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `username`, `email`, `password`, `role`, `foto`, `dibuat_oleh`, `aktif`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrator', 'superadmin', 'superadmin@bsi-mojokerto.go.id', '$2y$12$0rR49VMwoGsNIDRdTPfpGua3iv/zDwwNqKek2128MvLMQrMSoRb5i', 'super_admin', NULL, NULL, 1, '2026-04-06 02:12:49', '2026-03-16 22:04:50', '2026-04-06 02:12:49'),
(2, 'Admin Data', 'admin_data', 'admindata@bsi-mojokerto.go.id', '$2y$12$4Oon3UYYrkFjQF6W08CHwOQ/gIEf2qoshCq/Sejec7TrNX0Twjc02', 'admin_data', NULL, NULL, 1, '2026-04-04 06:12:36', '2026-03-16 22:04:50', '2026-04-04 06:12:36'),
(3, 'Admin Operasional', 'admin_ops', 'adminops@bsi-mojokerto.go.id', '$2y$12$JJJBFlgsg0h/lvhXO5SXxe2AgA.7yTCl0ozMt9oOMxanD8qi4yCZe', 'admin_operasional', NULL, NULL, 1, '2026-04-06 02:15:11', '2026-03-16 22:04:50', '2026-04-06 02:15:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank_sampah`
--
ALTER TABLE `bank_sampah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aktif` (`aktif`),
  ADD KEY `idx_dibuat` (`dibuat_oleh`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_slug` (`slug`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `detail_rekap_sampah`
--
ALTER TABLE `detail_rekap_sampah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_rekap` (`id_rekap`),
  ADD KEY `idx_nama` (`id_nama_sampah`);

--
-- Indexes for table `galeri`
--
ALTER TABLE `galeri`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_kategori` (`kategori`),
  ADD KEY `idx_tanggal` (`tanggal`);

--
-- Indexes for table `harga_sampah`
--
ALTER TABLE `harga_sampah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_nama_sampah` (`id_nama_sampah`),
  ADD KEY `idx_diupdate` (`diupdate_oleh`);

--
-- Indexes for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_nama` (`nama`),
  ADD KEY `idx_dibuat` (`dibuat_oleh`);

--
-- Indexes for table `kegiatan`
--
ALTER TABLE `kegiatan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `nama_sampah`
--
ALTER TABLE `nama_sampah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_jenis` (`id_jenis`),
  ADD KEY `idx_dibuat` (`dibuat_oleh`);

--
-- Indexes for table `profil_kelembagaan`
--
ALTER TABLE `profil_kelembagaan`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rekap_sampah`
--
ALTER TABLE `rekap_sampah`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_bank` (`id_bank_sampah`),
  ADD KEY `idx_tanggal` (`tanggal`),
  ADD KEY `idx_user` (`id_user`),
  ADD KEY `idx_tgl_bank` (`tanggal`,`id_bank_sampah`);

--
-- Indexes for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_aktif_urutan` (`aktif`,`urutan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_username` (`username`),
  ADD UNIQUE KEY `uq_email` (`email`),
  ADD KEY `idx_role` (`role`),
  ADD KEY `idx_aktif` (`aktif`),
  ADD KEY `fk_users_dibuat` (`dibuat_oleh`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank_sampah`
--
ALTER TABLE `bank_sampah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `detail_rekap_sampah`
--
ALTER TABLE `detail_rekap_sampah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `galeri`
--
ALTER TABLE `galeri`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `harga_sampah`
--
ALTER TABLE `harga_sampah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `kegiatan`
--
ALTER TABLE `kegiatan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `nama_sampah`
--
ALTER TABLE `nama_sampah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `profil_kelembagaan`
--
ALTER TABLE `profil_kelembagaan`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rekap_sampah`
--
ALTER TABLE `rekap_sampah`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `struktur_organisasi`
--
ALTER TABLE `struktur_organisasi`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bank_sampah`
--
ALTER TABLE `bank_sampah`
  ADD CONSTRAINT `fk_bs_user` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `detail_rekap_sampah`
--
ALTER TABLE `detail_rekap_sampah`
  ADD CONSTRAINT `fk_detail_nama` FOREIGN KEY (`id_nama_sampah`) REFERENCES `nama_sampah` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_detail_rekap` FOREIGN KEY (`id_rekap`) REFERENCES `rekap_sampah` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `harga_sampah`
--
ALTER TABLE `harga_sampah`
  ADD CONSTRAINT `fk_harga_nama` FOREIGN KEY (`id_nama_sampah`) REFERENCES `nama_sampah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_harga_user` FOREIGN KEY (`diupdate_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jenis_sampah`
--
ALTER TABLE `jenis_sampah`
  ADD CONSTRAINT `fk_jenis_user` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `nama_sampah`
--
ALTER TABLE `nama_sampah`
  ADD CONSTRAINT `fk_nama_jenis` FOREIGN KEY (`id_jenis`) REFERENCES `jenis_sampah` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_nama_user` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rekap_sampah`
--
ALTER TABLE `rekap_sampah`
  ADD CONSTRAINT `fk_rekap_bank` FOREIGN KEY (`id_bank_sampah`) REFERENCES `bank_sampah` (`id`) ON DELETE RESTRICT,
  ADD CONSTRAINT `fk_rekap_user` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_dibuat` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
