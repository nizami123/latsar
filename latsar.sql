-- Database
CREATE DATABASE IF NOT EXISTS peternakan;
USE peternakan;

-- Master User (pegawai dinas)
CREATE TABLE master_user (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nama_user VARCHAR(100) NOT NULL,
    jabatan VARCHAR(50),
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Master Komoditas (ternak/produk)
CREATE TABLE master_komoditas (
    id_komoditas INT AUTO_INCREMENT PRIMARY KEY,
    nama_komoditas VARCHAR(50) NOT NULL,
    satuan VARCHAR(20)
);

-- Master Layanan
CREATE TABLE master_layanan (
    id_layanan INT AUTO_INCREMENT PRIMARY KEY,
    nama_layanan VARCHAR(50) NOT NULL
);

-- Master Penyakit Hewan
CREATE TABLE master_penyakit (
    id_penyakit INT AUTO_INCREMENT PRIMARY KEY,
    nama_penyakit VARCHAR(100) NOT NULL
);

-- Master Wilayah (kecamatan di Lamongan)
CREATE TABLE master_wilayah (
    id_wilayah INT AUTO_INCREMENT PRIMARY KEY,
    nama_wilayah VARCHAR(100) NOT NULL
);

-- Transaksi Harga
CREATE TABLE trx_harga (
    id_harga INT AUTO_INCREMENT PRIMARY KEY,
    bulan TINYINT,
    tahun YEAR,
    id_komoditas INT,
    harga DECIMAL(12,2),
    id_user INT,
    tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_komoditas) REFERENCES master_komoditas(id_komoditas),
    FOREIGN KEY (id_user) REFERENCES master_user(id_user)
);

-- Transaksi Populasi
CREATE TABLE trx_populasi (
    id_populasi INT AUTO_INCREMENT PRIMARY KEY,
    bulan TINYINT,
    tahun YEAR,
    id_komoditas INT,
    jumlah INT,
    id_wilayah INT,
    id_user INT,
    tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_komoditas) REFERENCES master_komoditas(id_komoditas),
    FOREIGN KEY (id_wilayah) REFERENCES master_wilayah(id_wilayah),
    FOREIGN KEY (id_user) REFERENCES master_user(id_user)
);

-- Transaksi Pemotongan
CREATE TABLE trx_pemotongan (
    id_pemotongan INT AUTO_INCREMENT PRIMARY KEY,
    bulan TINYINT,
    tahun YEAR,
    id_komoditas INT,
    jumlah INT,
    id_wilayah INT,
    id_user INT,
    tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_komoditas) REFERENCES master_komoditas(id_komoditas),
    FOREIGN KEY (id_wilayah) REFERENCES master_wilayah(id_wilayah),
    FOREIGN KEY (id_user) REFERENCES master_user(id_user)
);

-- Transaksi Produksi
CREATE TABLE trx_produksi (
    id_produksi INT AUTO_INCREMENT PRIMARY KEY,
    bulan TINYINT,
    tahun YEAR,
    id_komoditas INT,
    jumlah INT,
    id_user INT,
    tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_komoditas) REFERENCES master_komoditas(id_komoditas),
    FOREIGN KEY (id_user) REFERENCES master_user(id_user)
);

-- Transaksi Penyakit Hewan
CREATE TABLE trx_penyakit (
    id_trx_penyakit INT AUTO_INCREMENT PRIMARY KEY,
    bulan TINYINT,
    tahun YEAR,
    id_penyakit INT,
    jumlah_kasus INT,
    id_wilayah INT,
    status VARCHAR(50), -- Baru, Dalam Penanganan, Selesai
    id_user INT,
    tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_penyakit) REFERENCES master_penyakit(id_penyakit),
    FOREIGN KEY (id_wilayah) REFERENCES master_wilayah(id_wilayah),
    FOREIGN KEY (id_user) REFERENCES master_user(id_user)
);

-- Transaksi Pengguna Layanan
CREATE TABLE trx_layanan (
    id_trx_layanan INT AUTO_INCREMENT PRIMARY KEY,
    bulan TINYINT,
    tahun YEAR,
    id_layanan INT,
    jumlah INT,
    id_user INT,
    tanggal_input TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_layanan) REFERENCES master_layanan(id_layanan),
    FOREIGN KEY (id_user) REFERENCES master_user(id_user)
);

