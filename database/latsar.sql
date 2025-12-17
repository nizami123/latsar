-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2025 at 01:46 PM
-- Server version: 10.11.14-MariaDB-cll-lve
-- PHP Version: 8.4.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dewujosf_siternal`
--

-- --------------------------------------------------------

--
-- Table structure for table `master_desa`
--

CREATE TABLE `master_desa` (
  `kode_desa` varchar(100) NOT NULL,
  `nama_desa` varchar(100) NOT NULL,
  `kode` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `master_desa`
--

INSERT INTO `master_desa` (`kode_desa`, `nama_desa`, `kode`) VALUES
('35.24.01.2001', 'Sembung', '35.24.01'),
('35.24.01.2002', 'Banggle', '35.24.01'),
('35.24.01.2003', 'Kedungkumpul', '35.24.01'),
('35.24.01.2004', 'Sukorame', '35.24.01'),
('35.24.01.2005', 'Mragel', '35.24.01'),
('35.24.01.2006', 'Kedungrejo', '35.24.01'),
('35.24.01.2007', 'Sewor', '35.24.01'),
('35.24.01.2008', 'Wedoro', '35.24.01'),
('35.24.01.2009', 'Pendowokumpul', '35.24.01'),
('35.24.02.2001', 'Talunrejo', '35.24.02'),
('35.24.02.2002', 'Banjargondang', '35.24.02'),
('35.24.02.2003', 'Bluluk', '35.24.02'),
('35.24.02.2004', 'Cangkring', '35.24.02'),
('35.24.02.2005', 'Bronjong', '35.24.02'),
('35.24.02.2006', 'Songowareng', '35.24.02'),
('35.24.02.2007', 'Kuwurejo', '35.24.02'),
('35.24.02.2008', 'Sumberbanjar', '35.24.02'),
('35.24.02.2009', 'Primpen', '35.24.02'),
('35.24.03.2001', 'Jegreg', '35.24.03'),
('35.24.03.2002', 'Kedungpengaron', '35.24.03'),
('35.24.03.2003', 'Sumberagung', '35.24.03'),
('35.24.03.2004', 'Kedunglerep', '35.24.03'),
('35.24.03.2005', 'Jatipayak', '35.24.03'),
('35.24.03.2006', 'Kedungwaras', '35.24.03'),
('35.24.03.2007', 'Sidomulyo', '35.24.03'),
('35.24.03.2008', 'Sambangrejo', '35.24.03'),
('35.24.03.2009', 'Medalem', '35.24.03'),
('35.24.03.2010', 'Sidodowo', '35.24.03'),
('35.24.03.2011', 'Mojorejo', '35.24.03'),
('35.24.03.2012', 'Sambungrejo', '35.24.03'),
('35.24.03.2013', 'Kedungrejo', '35.24.03'),
('35.24.03.2014', 'Kacangan', '35.24.03'),
('35.24.03.2015', 'Nguwok', '35.24.03'),
('35.24.03.2016', 'Pule', '35.24.03'),
('35.24.03.2017', 'Yungyang', '35.24.03'),
('35.24.04.2001', 'Kedungmentawar', '35.24.04'),
('35.24.04.2002', 'Cerme', '35.24.04'),
('35.24.04.2003', 'Gebangangkrik', '35.24.04'),
('35.24.04.2004', 'Ngasemlemahbang', '35.24.04'),
('35.24.04.2005', 'Slaharwotan', '35.24.04'),
('35.24.04.2006', 'Ganggangtingan', '35.24.04'),
('35.24.04.2007', 'Jejel', '35.24.04'),
('35.24.04.2008', 'Purwokerto', '35.24.04'),
('35.24.04.2009', 'Kakatpenjalin', '35.24.04'),
('35.24.04.2010', 'Tlemang', '35.24.04'),
('35.24.04.2011', 'Mendogo', '35.24.04'),
('35.24.04.2012', 'Lawak', '35.24.04'),
('35.24.04.2013', 'Drujugurit', '35.24.04'),
('35.24.04.2014', 'Girik', '35.24.04'),
('35.24.04.2015', 'Munungrejo', '35.24.04'),
('35.24.04.2016', 'Ngimbang', '35.24.04'),
('35.24.04.2017', 'Durikedungjero', '35.24.04'),
('35.24.04.2018', 'Lamongrejo', '35.24.04'),
('35.24.04.2019', 'Sendangrejo', '35.24.04'),
('35.24.05.1001', 'Banaran', '35.24.05'),
('35.24.05.1003', 'Babat', '35.24.05'),
('35.24.05.2002', 'Karangkembang', '35.24.05'),
('35.24.05.2004', 'Puncakwangi', '35.24.05'),
('35.24.05.2005', 'Sogo', '35.24.05'),
('35.24.05.2006', 'Bedahan', '35.24.05'),
('35.24.05.2007', 'Truni', '35.24.05'),
('35.24.05.2008', 'Gendongkulon', '35.24.05'),
('35.24.05.2009', 'Plaosan', '35.24.05'),
('35.24.05.2010', 'Trepan', '35.24.05'),
('35.24.05.2011', 'Kuripan', '35.24.05'),
('35.24.05.2012', 'Kebalanpelang', '35.24.05'),
('35.24.05.2013', 'Sumurgenuk', '35.24.05'),
('35.24.05.2014', 'Gembong', '35.24.05'),
('35.24.05.2015', 'Bulumargi', '35.24.05'),
('35.24.05.2016', 'Datinawong', '35.24.05'),
('35.24.05.2017', 'Kebalandono', '35.24.05'),
('35.24.05.2018', 'Patihan', '35.24.05'),
('35.24.05.2019', 'Moropelang', '35.24.05'),
('35.24.05.2020', 'Keyongan', '35.24.05'),
('35.24.05.2021', 'Sambangan', '35.24.05'),
('35.24.05.2022', 'Tritunggal', '35.24.05'),
('35.24.05.2023', 'Kebonagung', '35.24.05'),
('35.24.06.2001', 'Dradahblumbang', '35.24.06'),
('35.24.06.2002', 'Kalen', '35.24.06'),
('35.24.06.2003', 'Mojodadi', '35.24.06'),
('35.24.06.2004', 'Gunungrejo', '35.24.06'),
('35.24.06.2005', 'Warungring', '35.24.06'),
('35.24.06.2006', 'Jatidrojog', '35.24.06'),
('35.24.06.2007', 'Kradenanrejo', '35.24.06'),
('35.24.06.2008', 'Kandangrejo', '35.24.06'),
('35.24.06.2009', 'Kedungpring', '35.24.06'),
('35.24.06.2010', 'Tlanak', '35.24.06'),
('35.24.06.2011', 'Sukomalo', '35.24.06'),
('35.24.06.2012', 'Mlati', '35.24.06'),
('35.24.06.2013', 'Karangcangkring', '35.24.06'),
('35.24.06.2014', 'Mekanderejo', '35.24.06'),
('35.24.06.2015', 'Banjarejo', '35.24.06'),
('35.24.06.2016', 'Sidobangun', '35.24.06'),
('35.24.06.2017', 'Blawirejo', '35.24.06'),
('35.24.06.2018', 'Maindu', '35.24.06'),
('35.24.06.2019', 'Tenggerejo', '35.24.06'),
('35.24.06.2020', 'Majenang', '35.24.06'),
('35.24.06.2021', 'Sidomlangean', '35.24.06'),
('35.24.06.2022', 'Nglebur', '35.24.06'),
('35.24.06.2023', 'Sumengko', '35.24.06'),
('35.24.07.1010', 'Brondong', '35.24.07'),
('35.24.07.2001', 'Lohgung', '35.24.07'),
('35.24.07.2002', 'Labuhan', '35.24.07'),
('35.24.07.2003', 'Sidomukti', '35.24.07'),
('35.24.07.2004', 'Brengkok', '35.24.07'),
('35.24.07.2005', 'Tlogoretno', '35.24.07'),
('35.24.07.2006', 'Sendangharjo', '35.24.07'),
('35.24.07.2007', 'Lembor', '35.24.07'),
('35.24.07.2008', 'Sedayulawas', '35.24.07'),
('35.24.07.2009', 'Sumberagung', '35.24.07'),
('35.24.08.2001', 'Dateng', '35.24.08'),
('35.24.08.2002', 'Jabung', '35.24.08'),
('35.24.08.2003', 'Keduyung', '35.24.08'),
('35.24.08.2004', 'Centini', '35.24.08'),
('35.24.08.2005', 'Durikulon', '35.24.08'),
('35.24.08.2006', 'Pesanggrahan', '35.24.08'),
('35.24.08.2007', 'Gelap', '35.24.08'),
('35.24.08.2008', 'Mojoasem', '35.24.08'),
('35.24.08.2009', 'Siser', '35.24.08'),
('35.24.08.2010', 'Bulutigo', '35.24.08'),
('35.24.08.2011', 'Pelangwot', '35.24.08'),
('35.24.08.2012', 'Laren', '35.24.08'),
('35.24.08.2013', 'Gampangsejati', '35.24.08'),
('35.24.08.2014', 'Tamanprijeg', '35.24.08'),
('35.24.08.2015', 'Karangtawar', '35.24.08'),
('35.24.08.2016', 'Tejoasri', '35.24.08'),
('35.24.08.2017', 'Godog', '35.24.08'),
('35.24.08.2018', 'Bulubrangsi', '35.24.08'),
('35.24.08.2019', 'Karangwungulor', '35.24.08'),
('35.24.08.2020', 'Brangsi', '35.24.08'),
('35.24.09.2001', 'Besur', '35.24.09'),
('35.24.09.2002', 'Titik', '35.24.09'),
('35.24.09.2003', 'Kendal', '35.24.09'),
('35.24.09.2004', 'Keting', '35.24.09'),
('35.24.09.2005', 'Ngarum', '35.24.09'),
('35.24.09.2006', 'Kebalankulon', '35.24.09'),
('35.24.09.2007', 'Kudikan', '35.24.09'),
('35.24.09.2008', 'Jugo', '35.24.09'),
('35.24.09.2009', 'Manyar', '35.24.09'),
('35.24.09.2010', 'Trosono', '35.24.09'),
('35.24.09.2011', 'Latek', '35.24.09'),
('35.24.09.2012', 'Miru', '35.24.09'),
('35.24.09.2013', 'Sekaran', '35.24.09'),
('35.24.09.2014', 'Moro', '35.24.09'),
('35.24.09.2015', 'Karang', '35.24.09'),
('35.24.09.2016', 'Kembangan', '35.24.09'),
('35.24.09.2017', 'Siman', '35.24.09'),
('35.24.09.2018', 'Bulutengger', '35.24.09'),
('35.24.09.2019', 'Porodeso', '35.24.09'),
('35.24.09.2020', 'Bugel', '35.24.09'),
('35.24.09.2021', 'Sungegeneng', '35.24.09'),
('35.24.10.2001', 'Duriwetan', '35.24.10'),
('35.24.10.2002', 'Taji', '35.24.10'),
('35.24.10.2003', 'Brumbun', '35.24.10'),
('35.24.10.2004', 'Siwuran', '35.24.10'),
('35.24.10.2005', 'Klagensrampat', '35.24.10'),
('35.24.10.2006', 'Pangean', '35.24.10'),
('35.24.10.2007', 'Maduran', '35.24.10'),
('35.24.10.2008', 'Jangkungsomo', '35.24.10'),
('35.24.10.2009', 'Parengan', '35.24.10'),
('35.24.10.2010', 'Pangkatrejo', '35.24.10'),
('35.24.10.2011', 'Kanugrahan', '35.24.10'),
('35.24.10.2012', 'Gumantuk', '35.24.10'),
('35.24.10.2013', 'Ngayung', '35.24.10'),
('35.24.10.2014', 'Pringgoboyo', '35.24.10'),
('35.24.10.2015', 'Gedangan', '35.24.10'),
('35.24.10.2016', 'Turi', '35.24.10'),
('35.24.10.2017', 'Blumbang', '35.24.10'),
('35.24.11.2001', 'Sidokumpul', '35.24.11'),
('35.24.11.2002', 'Pasarlegi', '35.24.11'),
('35.24.11.2003', 'Semampirejo', '35.24.11'),
('35.24.11.2004', 'Wateswinangun', '35.24.11'),
('35.24.11.2005', 'Sumbersari', '35.24.11'),
('35.24.11.2006', 'Pataan', '35.24.11'),
('35.24.11.2007', 'Tenggiring', '35.24.11'),
('35.24.11.2008', 'Garung', '35.24.11'),
('35.24.11.2009', 'Wonorejo', '35.24.11'),
('35.24.11.2010', 'Ardirejo', '35.24.11'),
('35.24.11.2011', 'Sekidang', '35.24.11'),
('35.24.11.2012', 'Kedungwangi', '35.24.11'),
('35.24.11.2013', 'Jatipandak', '35.24.11'),
('35.24.11.2014', 'Candisari', '35.24.11'),
('35.24.11.2015', 'Nogojatisari', '35.24.11'),
('35.24.11.2016', 'Pamotan', '35.24.11'),
('35.24.11.2017', 'Wudi', '35.24.11'),
('35.24.11.2018', 'Barurejo', '35.24.11'),
('35.24.11.2019', 'Kedungbanjar', '35.24.11'),
('35.24.11.2020', 'Gempolmanis', '35.24.11'),
('35.24.11.2021', 'Selorejo', '35.24.11'),
('35.24.11.2022', 'Kreteranggon', '35.24.11'),
('35.24.12.2001', 'Sidobogem', '35.24.12'),
('35.24.12.2002', 'Kalitengah', '35.24.12'),
('35.24.12.2003', 'Kedungdadi', '35.24.12'),
('35.24.12.2004', 'Sidorejo', '35.24.12'),
('35.24.12.2005', 'Kedungbanjar', '35.24.12'),
('35.24.12.2006', 'Bedingin', '35.24.12'),
('35.24.12.2007', 'Bakalrejo', '35.24.12'),
('35.24.12.2008', 'Gondanglor', '35.24.12'),
('35.24.12.2009', 'Pangkatrejo', '35.24.12'),
('35.24.12.2010', 'Karangsambigalih', '35.24.12'),
('35.24.12.2011', 'Supenuh', '35.24.12'),
('35.24.12.2012', 'Lebakadi', '35.24.12'),
('35.24.12.2013', 'Deketagung', '35.24.12'),
('35.24.12.2014', 'Jubellor', '35.24.12'),
('35.24.12.2015', 'Sugio', '35.24.12'),
('35.24.12.2016', 'Daliwangun', '35.24.12'),
('35.24.12.2017', 'Jubelkidul', '35.24.12'),
('35.24.12.2018', 'Lawanganagung', '35.24.12'),
('35.24.12.2019', 'Kalipang', '35.24.12'),
('35.24.12.2020', 'German', '35.24.12'),
('35.24.12.2021', 'Sekarbagus', '35.24.12'),
('35.24.13.2001', 'Pucuk', '35.24.13'),
('35.24.13.2002', 'Gempolpading', '35.24.13'),
('35.24.13.2003', 'Kesambi', '35.24.13'),
('35.24.13.2004', 'Plososetro', '35.24.13'),
('35.24.13.2005', 'Wanar', '35.24.13'),
('35.24.13.2006', 'Karangtinggil', '35.24.13'),
('35.24.13.2007', 'Warukulon', '35.24.13'),
('35.24.13.2008', 'Waruwetan', '35.24.13'),
('35.24.13.2009', 'Kedali', '35.24.13'),
('35.24.13.2010', 'Paji', '35.24.13'),
('35.24.13.2011', 'Sumberjo', '35.24.13'),
('35.24.13.2012', 'Cungkup', '35.24.13'),
('35.24.13.2013', 'Bugoharjo', '35.24.13'),
('35.24.13.2014', 'Ngambeg', '35.24.13'),
('35.24.13.2015', 'Babatkumpul', '35.24.13'),
('35.24.13.2016', 'Tanggungan', '35.24.13'),
('35.24.13.2017', 'Padenganploso', '35.24.13'),
('35.24.14.1001', 'Blimbing', '35.24.14'),
('35.24.14.2002', 'Kandangsemangkon', '35.24.14'),
('35.24.14.2003', 'Paciran', '35.24.14'),
('35.24.14.2004', 'Sumurgayam', '35.24.14'),
('35.24.14.2005', 'Sendangagung', '35.24.14'),
('35.24.14.2006', 'Sendangduwur', '35.24.14'),
('35.24.14.2007', 'Tunggul', '35.24.14'),
('35.24.14.2008', 'Kranji', '35.24.14'),
('35.24.14.2009', 'Drajat', '35.24.14'),
('35.24.14.2010', 'Banjarwati', '35.24.14'),
('35.24.14.2011', 'Kemantren', '35.24.14'),
('35.24.14.2012', 'Sidokelar', '35.24.14'),
('35.24.14.2013', 'Tlogosadang', '35.24.14'),
('35.24.14.2014', 'Paloh', '35.24.14'),
('35.24.14.2015', 'Weru', '35.24.14'),
('35.24.14.2016', 'Sidokumpul', '35.24.14'),
('35.24.14.2017', 'Warulor', '35.24.14'),
('35.24.15.2001', 'Dadapan', '35.24.15'),
('35.24.15.2002', 'Tebluru', '35.24.15'),
('35.24.15.2003', 'Sugihan', '35.24.15'),
('35.24.15.2004', 'Tenggulun', '35.24.15'),
('35.24.15.2005', 'Payaman', '35.24.15'),
('35.24.15.2006', 'Solokuro', '35.24.15'),
('35.24.15.2007', 'Takerharjo', '35.24.15'),
('35.24.15.2008', 'Dagan', '35.24.15'),
('35.24.15.2009', 'Banyubang', '35.24.15'),
('35.24.15.2010', 'Bluri', '35.24.15'),
('35.24.16.2001', 'Sukobendu', '35.24.16'),
('35.24.16.2002', 'Tunggunjagir', '35.24.16'),
('35.24.16.2003', 'Sumberbendo', '35.24.16'),
('35.24.16.2004', 'Mantup', '35.24.16'),
('35.24.16.2005', 'Kedukbembem', '35.24.16'),
('35.24.16.2006', 'Sumberdadi', '35.24.16'),
('35.24.16.2007', 'Kedungsoko', '35.24.16'),
('35.24.16.2008', 'Tugu', '35.24.16'),
('35.24.16.2009', 'Sukosari', '35.24.16'),
('35.24.16.2010', 'Sumberagung', '35.24.16'),
('35.24.16.2011', 'Sidomulyo', '35.24.16'),
('35.24.16.2012', 'Mojosari', '35.24.16'),
('35.24.16.2013', 'Plabuhanrejo', '35.24.16'),
('35.24.16.2014', 'Sumberkerep', '35.24.16'),
('35.24.16.2015', 'Rumpuk', '35.24.16'),
('35.24.17.2001', 'Siwalanrejo', '35.24.17'),
('35.24.17.2002', 'Kebonsari', '35.24.17'),
('35.24.17.2003', 'Sukolilo', '35.24.17'),
('35.24.17.2004', 'Pajangan', '35.24.17'),
('35.24.17.2005', 'Kadungrembug', '35.24.17'),
('35.24.17.2006', 'Sumberagung', '35.24.17'),
('35.24.17.2007', 'Sukodadi', '35.24.17'),
('35.24.17.2008', 'Sumberaji', '35.24.17'),
('35.24.17.2009', 'Menongo', '35.24.17'),
('35.24.17.2010', 'Madulegi', '35.24.17'),
('35.24.17.2011', 'Banjarejo', '35.24.17'),
('35.24.17.2012', 'Plumpang', '35.24.17'),
('35.24.17.2013', 'Bandungsari', '35.24.17'),
('35.24.17.2014', 'Sidogembul', '35.24.17'),
('35.24.17.2015', 'Balongtawun', '35.24.17'),
('35.24.17.2016', 'Gedangan', '35.24.17'),
('35.24.17.2017', 'Tlogorejo', '35.24.17'),
('35.24.17.2018', 'Baturono', '35.24.17'),
('35.24.17.2019', 'Surabayan', '35.24.17'),
('35.24.17.2020', 'Sugihrejo', '35.24.17'),
('35.24.18.2001', 'Bantengputih', '35.24.18'),
('35.24.18.2002', 'Karangrejo', '35.24.18'),
('35.24.18.2003', 'Latukan', '35.24.18'),
('35.24.18.2004', 'Guci', '35.24.18'),
('35.24.18.2005', 'Kaligerman', '35.24.18'),
('35.24.18.2006', 'Sungelebak', '35.24.18'),
('35.24.18.2007', 'Prijekngablak', '35.24.18'),
('35.24.18.2008', 'Tracal', '35.24.18'),
('35.24.18.2009', 'Sonoadi', '35.24.18'),
('35.24.18.2010', 'Kalanganyar', '35.24.18'),
('35.24.18.2011', 'Banjarmadu', '35.24.18'),
('35.24.18.2012', 'Kendalkemlagi', '35.24.18'),
('35.24.18.2013', 'Kawistolegi', '35.24.18'),
('35.24.18.2014', 'Jagran', '35.24.18'),
('35.24.18.2015', 'Karangwungu', '35.24.18'),
('35.24.18.2016', 'Sumberwudi', '35.24.18'),
('35.24.18.2017', 'Karanggeneng', '35.24.18'),
('35.24.18.2018', 'Mertani', '35.24.18'),
('35.24.19.2001', 'Tlogoagung', '35.24.19'),
('35.24.19.2002', 'Kedungmegarih', '35.24.19'),
('35.24.19.2003', 'Sidomukti', '35.24.19'),
('35.24.19.2004', 'Sukosongo', '35.24.19'),
('35.24.19.2005', 'Kaliwates', '35.24.19'),
('35.24.19.2006', 'Kedungasri', '35.24.19'),
('35.24.19.2007', 'Doyomulyo', '35.24.19'),
('35.24.19.2008', 'Kembangbahu', '35.24.19'),
('35.24.19.2009', 'Maor', '35.24.19'),
('35.24.19.2010', 'Moronyamplung', '35.24.19'),
('35.24.19.2011', 'Mangkujajar', '35.24.19'),
('35.24.19.2012', 'Puter', '35.24.19'),
('35.24.19.2013', 'Pelang', '35.24.19'),
('35.24.19.2014', 'Dumpiagung', '35.24.19'),
('35.24.19.2015', 'Randubener', '35.24.19'),
('35.24.19.2016', 'Lopang', '35.24.19'),
('35.24.19.2017', 'Gintungan', '35.24.19'),
('35.24.19.2018', 'Katemas', '35.24.19'),
('35.24.20.2001', 'Pucangro', '35.24.20'),
('35.24.20.2002', 'Pucangtelu', '35.24.20'),
('35.24.20.2003', 'Somosari', '35.24.20'),
('35.24.20.2004', 'Jelakcatur', '35.24.20'),
('35.24.20.2005', 'Mungli', '35.24.20'),
('35.24.20.2006', 'Pengangsalan', '35.24.20'),
('35.24.20.2007', 'Dibee', '35.24.20'),
('35.24.20.2008', 'Butungan', '35.24.20'),
('35.24.20.2009', 'Lukrejo', '35.24.20'),
('35.24.20.2010', 'Tiwet', '35.24.20'),
('35.24.20.2011', 'Blajo', '35.24.20'),
('35.24.20.2012', 'Kalitengah', '35.24.20'),
('35.24.20.2013', 'Gambuhan', '35.24.20'),
('35.24.20.2014', 'Cluring', '35.24.20'),
('35.24.20.2015', 'Bojosari', '35.24.20'),
('35.24.20.2016', 'Kediren', '35.24.20'),
('35.24.20.2017', 'Kuluran', '35.24.20'),
('35.24.20.2018', 'Canditunggal', '35.24.20'),
('35.24.20.2019', 'Sugihwaras', '35.24.20'),
('35.24.20.2020', 'Tanjungmekar', '35.24.20'),
('35.24.21.2001', 'Geger', '35.24.21'),
('35.24.21.2002', 'Badurame', '35.24.21'),
('35.24.21.2003', 'Karangwedoro', '35.24.21'),
('35.24.21.2004', 'Wangunrejo', '35.24.21'),
('35.24.21.2005', 'Putatkumpul', '35.24.21'),
('35.24.21.2006', 'Keben', '35.24.21'),
('35.24.21.2007', 'Sukoanyar', '35.24.21'),
('35.24.21.2008', 'Kemlagigede', '35.24.21'),
('35.24.21.2009', 'Turi', '35.24.21'),
('35.24.21.2010', 'Kemlagilor', '35.24.21'),
('35.24.21.2011', 'Sukorejo', '35.24.21'),
('35.24.21.2012', 'Tawangrejo', '35.24.21'),
('35.24.21.2013', 'Bambang', '35.24.21'),
('35.24.21.2014', 'Pomahanjanggan', '35.24.21'),
('35.24.21.2015', 'Tambakploso', '35.24.21'),
('35.24.21.2016', 'Balun', '35.24.21'),
('35.24.21.2017', 'Ngujungrejo', '35.24.21'),
('35.24.21.2018', 'Kepudibener', '35.24.21'),
('35.24.21.2019', 'Gedungboyountung', '35.24.21'),
('35.24.22.1012', 'Sukorejo', '35.24.22'),
('35.24.22.1013', 'Sukomulyo', '35.24.22'),
('35.24.22.1014', 'Sidoharjo', '35.24.22'),
('35.24.22.1015', 'Tumenggungan', '35.24.22'),
('35.24.22.1017', 'Sidokumpul', '35.24.22'),
('35.24.22.1018', 'Tlogoanyar', '35.24.22'),
('35.24.22.1019', 'Jetis', '35.24.22'),
('35.24.22.1020', 'Banjarmendalan', '35.24.22'),
('35.24.22.2001', 'Rancangkencono', '35.24.22'),
('35.24.22.2002', 'Karanglangit', '35.24.22'),
('35.24.22.2003', 'Pangkatrejo', '35.24.22'),
('35.24.22.2004', 'Kebet', '35.24.22'),
('35.24.22.2005', 'Sendangrejo', '35.24.22'),
('35.24.22.2006', 'Sumberejo', '35.24.22'),
('35.24.22.2007', 'Tanjung', '35.24.22'),
('35.24.22.2008', 'Plosowahyu', '35.24.22'),
('35.24.22.2009', 'Wajik', '35.24.22'),
('35.24.22.2010', 'Kramat', '35.24.22'),
('35.24.22.2011', 'Made', '35.24.22'),
('35.24.22.2016', 'Sidomukti', '35.24.22'),
('35.24.23.2001', 'Wonokromo', '35.24.23'),
('35.24.23.2002', 'Soko', '35.24.23'),
('35.24.23.2003', 'Guminingrejo', '35.24.23'),
('35.24.23.2004', 'Bakalanpule', '35.24.23'),
('35.24.23.2005', 'Takeranklating', '35.24.23'),
('35.24.23.2006', 'Kelorarum', '35.24.23'),
('35.24.23.2007', 'Jotosanur', '35.24.23'),
('35.24.23.2008', 'Pengumbulanadi', '35.24.23'),
('35.24.23.2009', 'Jatirejo', '35.24.23'),
('35.24.23.2010', 'Dukuhagung', '35.24.23'),
('35.24.23.2011', 'Tambakrigadung', '35.24.23'),
('35.24.23.2012', 'Botoputih', '35.24.23'),
('35.24.23.2013', 'Balongwangi', '35.24.23'),
('35.24.24.2001', 'Waruk', '35.24.24'),
('35.24.24.2002', 'Sukorejo', '35.24.24'),
('35.24.24.2003', 'Karanganom', '35.24.24'),
('35.24.24.2004', 'Somowinangun', '35.24.24'),
('35.24.24.2005', 'Ketapangtelu', '35.24.24'),
('35.24.24.2006', 'Mayong', '35.24.24'),
('35.24.24.2007', 'Palangan', '35.24.24'),
('35.24.24.2008', 'Blawi', '35.24.24'),
('35.24.24.2009', 'Banjarejo', '35.24.24'),
('35.24.24.2010', 'Putatbangah', '35.24.24'),
('35.24.24.2011', 'Banyuurip', '35.24.24'),
('35.24.24.2012', 'Pendowolimo', '35.24.24'),
('35.24.24.2013', 'Baranggayam', '35.24.24'),
('35.24.24.2014', 'Watangpanjang', '35.24.24'),
('35.24.24.2015', 'Sambopinggir', '35.24.24'),
('35.24.24.2016', 'Priyoso', '35.24.24'),
('35.24.24.2017', 'Windu', '35.24.24'),
('35.24.24.2018', 'Bogobabadan', '35.24.24'),
('35.24.24.2019', 'Gawerejo', '35.24.24'),
('35.24.24.2020', 'Kuro', '35.24.24'),
('35.24.24.2021', 'Karangbinangun', '35.24.24'),
('35.24.25.2001', 'Sidorejo', '35.24.25'),
('35.24.25.2002', 'Dlanggu', '35.24.25'),
('35.24.25.2003', 'Laladan', '35.24.25'),
('35.24.25.2004', 'Deketkulon', '35.24.25'),
('35.24.25.2005', 'Deketwetan', '35.24.25'),
('35.24.25.2006', 'Sugihwaras', '35.24.25'),
('35.24.25.2007', 'Dinoyo', '35.24.25'),
('35.24.25.2008', 'Sidomulyo', '35.24.25'),
('35.24.25.2009', 'Rejosari', '35.24.25'),
('35.24.25.2010', 'Pandanpancur', '35.24.25'),
('35.24.25.2011', 'Sidobinangun', '35.24.25'),
('35.24.25.2012', 'Babatagung', '35.24.25'),
('35.24.25.2013', 'Tukkerto', '35.24.25'),
('35.24.25.2014', 'Weduni', '35.24.25'),
('35.24.25.2015', 'Rejotengah', '35.24.25'),
('35.24.25.2016', 'Srirande', '35.24.25'),
('35.24.25.2017', 'Plosobuden', '35.24.25'),
('35.24.26.2001', 'Soko', '35.24.26'),
('35.24.26.2002', 'Morocalan', '35.24.26'),
('35.24.26.2003', 'Gempolpendowo', '35.24.26'),
('35.24.26.2004', 'Pasi', '35.24.26'),
('35.24.26.2005', 'Rayunggumuk', '35.24.26'),
('35.24.26.2006', 'Menganti', '35.24.26'),
('35.24.26.2007', 'Margoanyar', '35.24.26'),
('35.24.26.2008', 'Began', '35.24.26'),
('35.24.26.2009', 'Mendogo', '35.24.26'),
('35.24.26.2010', 'Kentong', '35.24.26'),
('35.24.26.2011', 'Sudangan', '35.24.26'),
('35.24.26.2012', 'Medang', '35.24.26'),
('35.24.26.2013', 'Duduklor', '35.24.26'),
('35.24.26.2014', 'Glagah', '35.24.26'),
('35.24.26.2015', 'Wangen', '35.24.26'),
('35.24.26.2016', 'Tanggungprigel', '35.24.26'),
('35.24.26.2017', 'Karangagung', '35.24.26'),
('35.24.26.2018', 'Bangkok', '35.24.26'),
('35.24.26.2019', 'Jatirenggo', '35.24.26'),
('35.24.26.2020', 'Bapuhbaru', '35.24.26'),
('35.24.26.2021', 'Bapuhbandung', '35.24.26'),
('35.24.26.2022', 'Meluntur', '35.24.26'),
('35.24.26.2023', 'Konang', '35.24.26'),
('35.24.26.2024', 'Dukuhtunggal', '35.24.26'),
('35.24.26.2025', 'Panggang', '35.24.26'),
('35.24.26.2026', 'Wonorejo', '35.24.26'),
('35.24.26.2027', 'Wedoro', '35.24.26'),
('35.24.26.2028', 'Karangturi', '35.24.26'),
('35.24.26.2029', 'Meluwur', '35.24.26'),
('35.24.27.2001', 'Kedungkumpul', '35.24.27'),
('35.24.27.2002', 'Dermolemahbang', '35.24.27'),
('35.24.27.2003', 'Simbatan', '35.24.27'),
('35.24.27.2004', 'Sumberjo', '35.24.27'),
('35.24.27.2005', 'Canggah', '35.24.27'),
('35.24.27.2006', 'Beru', '35.24.27'),
('35.24.27.2007', 'Tambakmenjangan', '35.24.27'),
('35.24.27.2008', 'Gempoltukmloko', '35.24.27'),
('35.24.27.2009', 'Sarirejo', '35.24.27');

-- --------------------------------------------------------

--
-- Table structure for table `master_komoditas`
--

CREATE TABLE `master_komoditas` (
  `id_komoditas` int(11) NOT NULL,
  `nama_komoditas` varchar(50) NOT NULL,
  `satuan` varchar(20) DEFAULT NULL,
  `jenis` varchar(100) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `urut` int(11) DEFAULT NULL,
  `klasifikasi` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `master_komoditas`
--

INSERT INTO `master_komoditas` (`id_komoditas`, `nama_komoditas`, `satuan`, `jenis`, `created_by`, `created_at`, `urut`, `klasifikasi`) VALUES
(1, 'Sapi Potong', 'ekor', 'Ternak besar', 0, '0000-00-00 00:00:00', 1, NULL),
(2, 'Kambing', 'ekor', 'Ternak Kecil', 0, '0000-00-00 00:00:00', 5, NULL),
(5, 'Susu', 'liter', 'Hasil Ternak', 0, '0000-00-00 00:00:00', NULL, NULL),
(6, 'Daging', 'kg', 'Hasil Ternak', 0, '0000-00-00 00:00:00', NULL, NULL),
(7, 'Sapi', 'kg', 'Komoditas', 0, '2025-09-24 06:58:50', NULL, NULL),
(8, 'Daging Sapi', 'kg', 'Komoditas', 0, '2025-09-24 06:58:50', NULL, NULL),
(9, 'Ayam Broiler', 'kg', 'Komoditas', 0, '2025-09-24 06:58:50', NULL, NULL),
(10, 'Karkas Ayam Broiler', 'kg', 'Komoditas', 0, '2025-09-24 06:58:50', NULL, NULL),
(11, 'Telur Ayam Ras (P)', 'kg', 'Komoditas', 0, '2025-09-24 06:58:50', NULL, NULL),
(12, 'Telur Ayam Ras (K)', 'kg', 'Komoditas', 0, '2025-09-24 06:58:50', NULL, NULL),
(13, 'Sapi Perah', 'ekor', 'Ternak besar', 0, '2025-09-24 07:03:48', 2, NULL),
(14, 'Kerbau', 'ekor', 'Ternak besar', 0, '2025-09-24 07:03:48', 3, NULL),
(15, 'Kuda', 'ekor', 'Ternak besar', 0, '2025-09-24 07:03:48', 4, NULL),
(16, 'Domba', 'ekor', 'Ternak Kecil', 0, '2025-09-24 07:03:48', 6, NULL),
(17, 'Ayam Buras', 'ekor', 'Unggas', 0, '2025-09-24 07:03:48', 7, NULL),
(18, 'Ayam Pedaging', 'ekor', 'Unggas', 0, '2025-09-24 07:03:48', 8, NULL),
(19, 'Ayam Petelur', 'ekor', 'Unggas', 0, '2025-09-24 07:03:48', 9, NULL),
(20, 'Itik', 'ekor', 'Unggas', 0, '2025-09-24 07:03:48', 10, NULL),
(21, 'Entog', 'ekor', 'Unggas', 0, '2025-09-24 07:03:48', 11, NULL),
(22, 'Kelinci', 'ekor', 'Aneka Ternak', 0, '2025-09-24 07:03:48', 12, NULL),
(24, 'Burung Puyuh', 'ekor', 'Aneka Ternak', 0, '2025-09-24 07:03:48', 14, NULL),
(25, 'Burung Walet', 'ekor', 'Aneka Ternak', 0, '2025-09-24 07:03:48', 15, NULL),
(26, 'Babi', 'ekor', 'Ternak Kecil', 0, '2025-09-24 07:03:48', 16, NULL),
(27, 'Burung Dara', 'ekor', 'Unggas', 3, '2025-09-26 08:02:49', 13, NULL),
(28, 'Kerbau', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 1, 'A. Ternak Hidup'),
(29, 'Sapi Potong', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 2, 'A. Ternak Hidup'),
(30, 'Bakalan Kereman', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 3, 'A. Ternak Hidup'),
(31, 'Sapi PO', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 4, 'A. Ternak Hidup'),
(32, 'Sapi Cross', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 5, 'A. Ternak Hidup'),
(33, 'Domba', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 6, 'A. Ternak Hidup'),
(34, 'Kambing', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 7, 'A. Ternak Hidup'),
(35, 'Ayam Buras', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 8, 'A. Ternak Hidup'),
(36, 'Ayam Ras', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 9, 'A. Ternak Hidup'),
(37, 'Itik', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 10, 'A. Ternak Hidup'),
(38, 'Itik Manila/Entog', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 11, 'A. Ternak Hidup'),
(39, 'Burung Puyuh', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 12, 'A. Ternak Hidup'),
(40, 'DOC Broiler', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 13, 'A. Ternak Hidup'),
(41, 'DOC Layer', 'Ekor', 'Komoditas', 1, '2025-12-15 03:45:03', 14, 'A. Ternak Hidup'),
(42, 'Daging Sapi', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 1, 'B. Produksi Ternak'),
(43, 'Hati Sapi', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 2, 'B. Produksi Ternak'),
(44, 'Jeroan Sapi', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 3, 'B. Produksi Ternak'),
(45, 'Daging Kambing/Domba', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 4, 'B. Produksi Ternak'),
(46, 'Daging Ayam Buras', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 5, 'B. Produksi Ternak'),
(47, 'Daging Ayam Broiler', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 6, 'B. Produksi Ternak'),
(48, 'Daging Ayam Layer', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 7, 'B. Produksi Ternak'),
(49, 'Daging Itik', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 8, 'B. Produksi Ternak'),
(50, 'Daging Itik Manila', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 9, 'B. Produksi Ternak'),
(51, 'Daging Puyuh', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 10, 'B. Produksi Ternak'),
(52, 'Telur Ayam Ras', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 11, 'B. Produksi Ternak'),
(53, 'Telur Puyuh', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:04', 12, 'B. Produksi Ternak'),
(54, 'Telur Itik', 'Butir', 'Komoditas', 1, '2025-12-15 03:45:04', 13, 'B. Produksi Ternak'),
(55, 'Telur Ayam Buras', 'Butir', 'Komoditas', 1, '2025-12-15 03:45:04', 14, 'B. Produksi Ternak'),
(56, 'Telur Itik Manila/Entog', 'Butir', 'Komoditas', 1, '2025-12-15 03:45:04', 15, 'B. Produksi Ternak'),
(57, 'Telur Asin', 'Butir', 'Komoditas', 1, '2025-12-15 03:45:04', 16, 'B. Produksi Ternak'),
(58, 'Susu Segar', 'Liter', 'Komoditas', 1, '2025-12-15 03:45:04', 17, 'B. Produksi Ternak'),
(59, 'Pakan Layer Starter', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:06', 1, 'C. Sapronak'),
(60, 'Pakan Layer Grower', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:06', 2, 'C. Sapronak'),
(61, 'Pakan Layer Finisher', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:06', 3, 'C. Sapronak'),
(62, 'Konsentrat Layer', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:06', 4, 'C. Sapronak'),
(63, 'Pakan Broiler Starter', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:06', 5, 'C. Sapronak'),
(64, 'Pakan Broiler Finisher', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:06', 6, 'C. Sapronak'),
(65, 'Bekatul', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:06', 7, 'C. Sapronak'),
(66, 'Dedak Halus', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:06', 8, 'C. Sapronak'),
(67, 'Jagung Giling', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:06', 9, 'C. Sapronak'),
(68, 'Kulit Kerbau Basah', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:07', 1, 'D. Hasil Ikutan'),
(69, 'Kulit Sapi Basah', 'Kg', 'Komoditas', 1, '2025-12-15 03:45:07', 2, 'D. Hasil Ikutan'),
(70, 'Kulit Domba Basah', 'Lembar', 'Komoditas', 1, '2025-12-15 03:45:07', 3, 'D. Hasil Ikutan'),
(71, 'Kulit Kambing Basah', 'Lembar', 'Komoditas', 1, '2025-12-15 03:45:07', 4, 'D. Hasil Ikutan');

-- --------------------------------------------------------

--
-- Table structure for table `master_layanan`
--

CREATE TABLE `master_layanan` (
  `id_layanan` int(11) NOT NULL,
  `nama_layanan` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `master_layanan`
--

INSERT INTO `master_layanan` (`id_layanan`, `nama_layanan`) VALUES
(1, 'IB'),
(2, 'Vaksinasi'),
(4, 'Konsultasi');

-- --------------------------------------------------------

--
-- Table structure for table `master_penyakit`
--

CREATE TABLE `master_penyakit` (
  `id_penyakit` int(11) NOT NULL,
  `nama_penyakit` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `master_penyakit`
--

INSERT INTO `master_penyakit` (`id_penyakit`, `nama_penyakit`) VALUES
(1, 'PMK'),
(2, 'Flu Burung'),
(3, 'Anthrax');

-- --------------------------------------------------------

--
-- Table structure for table `master_user`
--

CREATE TABLE `master_user` (
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(100) NOT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `remember_token` varchar(64) DEFAULT NULL,
  `kode` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `master_user`
--

INSERT INTO `master_user` (`id_user`, `nama_user`, `jabatan`, `username`, `password`, `created_at`, `remember_token`, `kode`) VALUES
(3, 'Admin Dinas', 'Admin Dinas', 'admin', '0192023a7bbd73250516f069df18b500', '2025-09-23 05:27:24', '9398ef6b75066b49c8aeee9a183eaddf2c2e40738e8e14d5973fe5172465c1f8', NULL),
(85, 'Sukorame', 'Admin Kecamatan', 'sukorame', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', 'a80ec8c24795d6ca3d64868679e4be4b638aee40683257f02862c160233e57c8', '35.24.01'),
(86, 'Bluluk', 'Admin Kecamatan', 'bluluk', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', 'e0b43d116d4c426ae9dd80038df28383dbf00f92f97154b0b0c31dbde7461d78', '35.24.02'),
(87, 'Modo', 'Admin Kecamatan', 'modo', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '8076f9d41c251db540adb306093eec7b7fbe6528e1b40510233e788e4d40e049', '35.24.03'),
(88, 'Ngimbang', 'Admin Kecamatan', 'ngimbang', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', 'a83f24141022fc54aa8a3e836150e24d35092ca48870fca0a2d861656df40765', '35.24.04'),
(89, 'Babat', 'Admin Kecamatan', 'babat', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.05'),
(90, 'Kedungpring', 'Admin Kecamatan', 'kedungpring', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '8387dfe5b97ac578d68af70f21ebaf899e7ea86dc7839b4e6d5148adc4d1db37', '35.24.06'),
(91, 'Brondong', 'Admin Kecamatan', 'brondong', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.07'),
(92, 'Laren', 'Admin Kecamatan', 'laren', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '65532e8954ffea5d806dbfda09a635d61c94d4a0115bf065f88ce73ea018d9bf', '35.24.08'),
(93, 'Sekaran', 'Admin Kecamatan', 'sekaran', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.09'),
(94, 'Maduran', 'Admin Kecamatan', 'maduran', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.10'),
(95, 'Sambeng', 'Admin Kecamatan', 'sambeng', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', 'ee7d6f1d42e024377923ba90050b53fccc803ffd4888270c69e263b56fce5e6d', '35.24.11'),
(96, 'Sugio', 'Admin Kecamatan', 'sugio', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '8e752548e33ea551b1dc2904c5dc15b42d91bbfbe9fab463a8a266b4f88ef23b', '35.24.12'),
(97, 'Pucuk', 'Admin Kecamatan', 'pucuk', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.13'),
(98, 'Paciran', 'Admin Kecamatan', 'paciran', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.14'),
(99, 'Solokuro', 'Admin Kecamatan', 'solokuro', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '06c8345939378b088e0abe2f8d192e05d22d7f95bef2b3489be4607856955ef3', '35.24.15'),
(100, 'Mantup', 'Admin Kecamatan', 'mantup', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '616f69455e4ae817925db3c9707b3db8cd660883f60a9b15159c0dd9e217119c', '35.24.16'),
(101, 'Sukodadi', 'Admin Kecamatan', 'sukodadi', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.17'),
(102, 'Karanggeneng', 'Admin Kecamatan', 'karanggeneng', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.18'),
(103, 'Kembangbahu', 'Admin Kecamatan', 'kembangbahu', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', 'b6073337999a20fa1f14e6b414bf5c38193bf49b186f6ea1930e3a1d030b7c1a', '35.24.19'),
(104, 'Kalitengah', 'Admin Kecamatan', 'kalitengah', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.20'),
(105, 'Turi', 'Admin Kecamatan', 'turi', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.21'),
(106, 'Lamongan', 'Admin Kecamatan', 'lamongan', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.22'),
(107, 'Tikung', 'Admin Kecamatan', 'tikung', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', 'a61126f76fead6989d1bad1f200cb7089af011b2b899609af9b64fb360128e2a', '35.24.23'),
(108, 'Karangbinangun', 'Admin Kecamatan', 'karangbinangun', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '9b947384ab305f078f786ad2acc9215afad5ae455281f0869294b141fd21776f', '35.24.24'),
(109, 'Deket', 'Admin Kecamatan', 'deket', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '35e68599b54dcf32188d01743bb18d4bc0bee033da20a360d3f5088e32599e51', '35.24.25'),
(110, 'Glagah', 'Admin Kecamatan', 'glagah', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '82b770011b7c6704143385a9fcbcb085acc5783d570ec6de011801cf8b03a45d', '35.24.26'),
(111, 'Sarirejo', 'Admin Kecamatan', 'sarirejo', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '0ee1bba516ed2c6d17f1e151557edd343a7ac9d3b1720240c0aa952271b4f781', '35.24.27'),
(112, 'Bidang', 'Bidang', 'bidang', '0192023a7bbd73250516f069df18b500', '2025-10-22 02:15:05', '', '35.24.05');

-- --------------------------------------------------------

--
-- Table structure for table `master_wilayah`
--

CREATE TABLE `master_wilayah` (
  `id_wilayah` int(11) NOT NULL,
  `nama_wilayah` varchar(100) NOT NULL,
  `urut` int(11) DEFAULT NULL,
  `kode` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `master_wilayah`
--

INSERT INTO `master_wilayah` (`id_wilayah`, `nama_wilayah`, `urut`, `kode`) VALUES
(5, 'Babat', 10, '35.24.05'),
(6, 'Bluluk', 2, '35.24.02'),
(7, 'Brondong', 27, '35.24.07'),
(8, 'Deket', 16, '35.24.25'),
(9, 'Glagah', 17, '35.24.26'),
(10, 'Kalitengah', 21, '35.24.20'),
(11, 'Karangbinangun', 18, '35.24.24'),
(12, 'Karanggeneng', 20, '35.24.18'),
(13, 'Kedungpring', 8, '35.24.06'),
(14, 'Kembangbahu', 6, '35.24.19'),
(15, 'Lamongan', 13, '35.24.22'),
(16, 'Laren', 24, '35.24.08'),
(17, 'Maduran', 23, '35.24.10'),
(18, 'Mantup', 5, '35.24.16'),
(19, 'Modo', 9, '35.24.03'),
(20, 'Ngimbang', 3, '35.24.04'),
(21, 'Paciran', 26, '35.24.14'),
(22, 'Pucuk', 11, '35.24.13'),
(23, 'Sambeng', 4, '35.24.11'),
(24, 'Sarirejo', 15, '35.24.27'),
(25, 'Sekaran', 22, '35.24.09'),
(26, 'Solokuro', 25, '35.24.15'),
(27, 'Sugio', 7, '35.24.12'),
(28, 'Sukodadi', 12, '35.24.17'),
(29, 'Sukorame', 1, '35.24.01'),
(30, 'Tikung', 14, '35.24.23'),
(31, 'Turi', 19, '35.24.21'),
(32, 'Pemda', 101, NULL),
(33, 'Swasta', 102, NULL),
(34, 'Luar', 103, NULL),
(35, 'TPU', 104, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `trx_harga`
--

CREATE TABLE `trx_harga` (
  `id_harga` int(11) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `harga` decimal(12,2) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_harga_komoditas`
--

CREATE TABLE `trx_harga_komoditas` (
  `id_harga` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `harga` decimal(12,2) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp(),
  `id_wilayah` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_kelahiran`
--

CREATE TABLE `trx_kelahiran` (
  `id_kelahiran` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp(),
  `kode_desa` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Jantan','Betina') DEFAULT NULL,
  `umur` enum('Anak','Muda','Dewasa') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_keluar`
--

CREATE TABLE `trx_keluar` (
  `id_keluar` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp(),
  `kode_desa` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Jantan','Betina') DEFAULT NULL,
  `umur` enum('Anak','Muda','Dewasa') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_kematian`
--

CREATE TABLE `trx_kematian` (
  `id_kematian` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp(),
  `kode_desa` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Jantan','Betina') DEFAULT NULL,
  `umur` enum('Anak','Muda','Dewasa') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_layanan`
--

CREATE TABLE `trx_layanan` (
  `id_trx_layanan` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_layanan` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_masuk`
--

CREATE TABLE `trx_masuk` (
  `id_masuk` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp(),
  `kode_desa` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Jantan','Betina') DEFAULT NULL,
  `umur` enum('Anak','Muda','Dewasa') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_pasar_hewan`
--

CREATE TABLE `trx_pasar_hewan` (
  `id_masuk` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp(),
  `kode_desa` varchar(100) DEFAULT NULL,
  `status_pasar` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_pemotongan`
--

CREATE TABLE `trx_pemotongan` (
  `id_pemotongan` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp(),
  `kode_desa` varchar(100) DEFAULT NULL,
  `jenis_kelamin` varchar(100) DEFAULT NULL,
  `umur` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_penyakit`
--

CREATE TABLE `trx_penyakit` (
  `id_trx_penyakit` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_penyakit` int(11) DEFAULT NULL,
  `jumlah_kasus` int(11) DEFAULT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_populasi`
--

CREATE TABLE `trx_populasi` (
  `id_populasi` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `id_wilayah` int(11) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp(),
  `kode_desa` varchar(100) DEFAULT NULL,
  `jenis_kelamin` enum('Jantan','Betina') DEFAULT NULL,
  `umur` enum('Anak','Muda','Dewasa') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trx_produksi`
--

CREATE TABLE `trx_produksi` (
  `id_produksi` int(11) NOT NULL,
  `bulan` tinyint(4) DEFAULT NULL,
  `tahun` year(4) DEFAULT NULL,
  `id_komoditas` int(11) DEFAULT NULL,
  `jumlah` int(11) DEFAULT NULL,
  `jenis` varchar(100) DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `tanggal_input` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_populasi`
-- (See below for the actual view)
--
CREATE TABLE `v_populasi` (
`id_populasi` int(11)
,`bulan` tinyint(4)
,`tahun` year(4)
,`id_komoditas` int(11)
,`jumlah` int(11)
,`id_wilayah` int(11)
,`id_user` int(11)
,`tanggal_input` timestamp
,`kode_desa` varchar(100)
,`jenis_kelamin` enum('Jantan','Betina')
,`umur` enum('Anak','Muda','Dewasa')
,`jumlah2` bigint(15)
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `master_desa`
--
ALTER TABLE `master_desa`
  ADD PRIMARY KEY (`kode_desa`);

--
-- Indexes for table `master_komoditas`
--
ALTER TABLE `master_komoditas`
  ADD PRIMARY KEY (`id_komoditas`);

--
-- Indexes for table `master_layanan`
--
ALTER TABLE `master_layanan`
  ADD PRIMARY KEY (`id_layanan`);

--
-- Indexes for table `master_penyakit`
--
ALTER TABLE `master_penyakit`
  ADD PRIMARY KEY (`id_penyakit`);

--
-- Indexes for table `master_user`
--
ALTER TABLE `master_user`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `master_wilayah`
--
ALTER TABLE `master_wilayah`
  ADD PRIMARY KEY (`id_wilayah`);

--
-- Indexes for table `trx_harga`
--
ALTER TABLE `trx_harga`
  ADD PRIMARY KEY (`id_harga`),
  ADD KEY `id_komoditas` (`id_komoditas`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_harga_komoditas`
--
ALTER TABLE `trx_harga_komoditas`
  ADD PRIMARY KEY (`id_harga`),
  ADD KEY `idx_komoditas` (`id_komoditas`),
  ADD KEY `idx_user` (`id_user`);

--
-- Indexes for table `trx_kelahiran`
--
ALTER TABLE `trx_kelahiran`
  ADD PRIMARY KEY (`id_kelahiran`),
  ADD KEY `id_komoditas` (`id_komoditas`),
  ADD KEY `id_wilayah` (`id_wilayah`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_keluar`
--
ALTER TABLE `trx_keluar`
  ADD PRIMARY KEY (`id_keluar`),
  ADD KEY `id_komoditas` (`id_komoditas`),
  ADD KEY `id_wilayah` (`id_wilayah`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_kematian`
--
ALTER TABLE `trx_kematian`
  ADD PRIMARY KEY (`id_kematian`),
  ADD KEY `id_komoditas` (`id_komoditas`),
  ADD KEY `id_wilayah` (`id_wilayah`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_layanan`
--
ALTER TABLE `trx_layanan`
  ADD PRIMARY KEY (`id_trx_layanan`),
  ADD KEY `id_layanan` (`id_layanan`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_masuk`
--
ALTER TABLE `trx_masuk`
  ADD PRIMARY KEY (`id_masuk`),
  ADD KEY `id_komoditas` (`id_komoditas`),
  ADD KEY `id_wilayah` (`id_wilayah`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_pasar_hewan`
--
ALTER TABLE `trx_pasar_hewan`
  ADD PRIMARY KEY (`id_masuk`),
  ADD KEY `id_komoditas` (`id_komoditas`),
  ADD KEY `id_wilayah` (`id_wilayah`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_pemotongan`
--
ALTER TABLE `trx_pemotongan`
  ADD PRIMARY KEY (`id_pemotongan`),
  ADD KEY `id_komoditas` (`id_komoditas`),
  ADD KEY `id_wilayah` (`id_wilayah`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_penyakit`
--
ALTER TABLE `trx_penyakit`
  ADD PRIMARY KEY (`id_trx_penyakit`),
  ADD KEY `id_penyakit` (`id_penyakit`),
  ADD KEY `id_wilayah` (`id_wilayah`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_populasi`
--
ALTER TABLE `trx_populasi`
  ADD PRIMARY KEY (`id_populasi`),
  ADD KEY `id_komoditas` (`id_komoditas`),
  ADD KEY `id_wilayah` (`id_wilayah`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `trx_produksi`
--
ALTER TABLE `trx_produksi`
  ADD PRIMARY KEY (`id_produksi`),
  ADD KEY `id_komoditas` (`id_komoditas`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `master_komoditas`
--
ALTER TABLE `master_komoditas`
  MODIFY `id_komoditas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `master_layanan`
--
ALTER TABLE `master_layanan`
  MODIFY `id_layanan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_penyakit`
--
ALTER TABLE `master_penyakit`
  MODIFY `id_penyakit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `master_user`
--
ALTER TABLE `master_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `master_wilayah`
--
ALTER TABLE `master_wilayah`
  MODIFY `id_wilayah` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `trx_harga`
--
ALTER TABLE `trx_harga`
  MODIFY `id_harga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_harga_komoditas`
--
ALTER TABLE `trx_harga_komoditas`
  MODIFY `id_harga` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_kelahiran`
--
ALTER TABLE `trx_kelahiran`
  MODIFY `id_kelahiran` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_keluar`
--
ALTER TABLE `trx_keluar`
  MODIFY `id_keluar` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_kematian`
--
ALTER TABLE `trx_kematian`
  MODIFY `id_kematian` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_layanan`
--
ALTER TABLE `trx_layanan`
  MODIFY `id_trx_layanan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_masuk`
--
ALTER TABLE `trx_masuk`
  MODIFY `id_masuk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_pasar_hewan`
--
ALTER TABLE `trx_pasar_hewan`
  MODIFY `id_masuk` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_pemotongan`
--
ALTER TABLE `trx_pemotongan`
  MODIFY `id_pemotongan` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_penyakit`
--
ALTER TABLE `trx_penyakit`
  MODIFY `id_trx_penyakit` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_populasi`
--
ALTER TABLE `trx_populasi`
  MODIFY `id_populasi` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trx_produksi`
--
ALTER TABLE `trx_produksi`
  MODIFY `id_produksi` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure for view `v_populasi`
--
DROP TABLE IF EXISTS `v_populasi`;

CREATE ALGORITHM=UNDEFINED DEFINER=`dewujosf_siternal`@`%` SQL SECURITY DEFINER VIEW `v_populasi`  AS WITH   data as (select `trx_populasi`.`id_populasi` AS `id_populasi`,`trx_populasi`.`bulan` AS `bulan`,`trx_populasi`.`tahun` AS `tahun`,`trx_populasi`.`id_komoditas` AS `id_komoditas`,`trx_populasi`.`jumlah` AS `jumlah`,`trx_populasi`.`id_wilayah` AS `id_wilayah`,`trx_populasi`.`id_user` AS `id_user`,`trx_populasi`.`tanggal_input` AS `tanggal_input`,`trx_populasi`.`kode_desa` AS `kode_desa`,`trx_populasi`.`jenis_kelamin` AS `jenis_kelamin`,`trx_populasi`.`umur` AS `umur` from `trx_populasi`)select `a`.`id_populasi` AS `id_populasi`,`a`.`bulan` AS `bulan`,`a`.`tahun` AS `tahun`,`a`.`id_komoditas` AS `id_komoditas`,`a`.`jumlah` AS `jumlah`,`a`.`id_wilayah` AS `id_wilayah`,`a`.`id_user` AS `id_user`,`a`.`tanggal_input` AS `tanggal_input`,`a`.`kode_desa` AS `kode_desa`,`a`.`jenis_kelamin` AS `jenis_kelamin`,`a`.`umur` AS `umur`,case when `a`.`id_komoditas` = 1 then case when `a`.`umur` = 'Anak' then floor(0.9 * `a`.`jumlah`) when `a`.`umur` = 'Muda' then floor(coalesce(`a`.`jumlah`,0) + 0.1 * coalesce(`b`.`jumlah`,0) - case when `a`.`jenis_kelamin` = 'Betina' then 0.04 else 0.06 end * coalesce(`a`.`jumlah`,0)) when `a`.`umur` = 'Dewasa' then floor(coalesce(`a`.`jumlah`,0) + case when `a`.`jenis_kelamin` = 'Betina' then 0.04 else 0.06 end * coalesce(`c`.`jumlah`,0)) else `a`.`jumlah` end else `a`.`jumlah` end AS `jumlah2` from ((`data` `a` left join `data` `b` on(`a`.`id_komoditas` = `b`.`id_komoditas` and `a`.`jenis_kelamin` = `b`.`jenis_kelamin` and `b`.`umur` = 'Anak' and `a`.`bulan` = `b`.`bulan` and `a`.`tahun` = `b`.`tahun` and `a`.`id_wilayah` = `b`.`id_wilayah` and `a`.`kode_desa` = `b`.`kode_desa`)) left join `data` `c` on(`a`.`id_komoditas` = `c`.`id_komoditas` and `a`.`jenis_kelamin` = `c`.`jenis_kelamin` and `c`.`umur` = 'Muda' and `a`.`bulan` = `c`.`bulan` and `a`.`tahun` = `c`.`tahun` and `a`.`id_wilayah` = `c`.`id_wilayah` and `a`.`kode_desa` = `c`.`kode_desa`))  ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `trx_harga`
--
ALTER TABLE `trx_harga`
  ADD CONSTRAINT `trx_harga_ibfk_1` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`),
  ADD CONSTRAINT `trx_harga_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_harga_komoditas`
--
ALTER TABLE `trx_harga_komoditas`
  ADD CONSTRAINT `fk_trx_harga_komoditas_komoditas` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_trx_harga_komoditas_user` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`) ON UPDATE CASCADE;

--
-- Constraints for table `trx_kelahiran`
--
ALTER TABLE `trx_kelahiran`
  ADD CONSTRAINT `trx_kelahiran_ibfk_1` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`),
  ADD CONSTRAINT `trx_kelahiran_ibfk_2` FOREIGN KEY (`id_wilayah`) REFERENCES `master_wilayah` (`id_wilayah`),
  ADD CONSTRAINT `trx_kelahiran_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_keluar`
--
ALTER TABLE `trx_keluar`
  ADD CONSTRAINT `trx_keluar_ibfk_1` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`),
  ADD CONSTRAINT `trx_keluar_ibfk_2` FOREIGN KEY (`id_wilayah`) REFERENCES `master_wilayah` (`id_wilayah`),
  ADD CONSTRAINT `trx_keluar_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_kematian`
--
ALTER TABLE `trx_kematian`
  ADD CONSTRAINT `trx_kematian_ibfk_1` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`),
  ADD CONSTRAINT `trx_kematian_ibfk_2` FOREIGN KEY (`id_wilayah`) REFERENCES `master_wilayah` (`id_wilayah`),
  ADD CONSTRAINT `trx_kematian_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_layanan`
--
ALTER TABLE `trx_layanan`
  ADD CONSTRAINT `trx_layanan_ibfk_1` FOREIGN KEY (`id_layanan`) REFERENCES `master_layanan` (`id_layanan`),
  ADD CONSTRAINT `trx_layanan_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_masuk`
--
ALTER TABLE `trx_masuk`
  ADD CONSTRAINT `trx_masuk_ibfk_1` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`),
  ADD CONSTRAINT `trx_masuk_ibfk_2` FOREIGN KEY (`id_wilayah`) REFERENCES `master_wilayah` (`id_wilayah`),
  ADD CONSTRAINT `trx_masuk_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_pasar_hewan`
--
ALTER TABLE `trx_pasar_hewan`
  ADD CONSTRAINT `trx_pasar_hewan_ibfk_1` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`),
  ADD CONSTRAINT `trx_pasar_hewan_ibfk_2` FOREIGN KEY (`id_wilayah`) REFERENCES `master_wilayah` (`id_wilayah`),
  ADD CONSTRAINT `trx_pasar_hewan_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_pemotongan`
--
ALTER TABLE `trx_pemotongan`
  ADD CONSTRAINT `trx_pemotongan_ibfk_1` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`),
  ADD CONSTRAINT `trx_pemotongan_ibfk_2` FOREIGN KEY (`id_wilayah`) REFERENCES `master_wilayah` (`id_wilayah`),
  ADD CONSTRAINT `trx_pemotongan_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_penyakit`
--
ALTER TABLE `trx_penyakit`
  ADD CONSTRAINT `trx_penyakit_ibfk_1` FOREIGN KEY (`id_penyakit`) REFERENCES `master_penyakit` (`id_penyakit`),
  ADD CONSTRAINT `trx_penyakit_ibfk_2` FOREIGN KEY (`id_wilayah`) REFERENCES `master_wilayah` (`id_wilayah`),
  ADD CONSTRAINT `trx_penyakit_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_populasi`
--
ALTER TABLE `trx_populasi`
  ADD CONSTRAINT `trx_populasi_ibfk_1` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`),
  ADD CONSTRAINT `trx_populasi_ibfk_2` FOREIGN KEY (`id_wilayah`) REFERENCES `master_wilayah` (`id_wilayah`),
  ADD CONSTRAINT `trx_populasi_ibfk_3` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);

--
-- Constraints for table `trx_produksi`
--
ALTER TABLE `trx_produksi`
  ADD CONSTRAINT `trx_produksi_ibfk_1` FOREIGN KEY (`id_komoditas`) REFERENCES `master_komoditas` (`id_komoditas`),
  ADD CONSTRAINT `trx_produksi_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `master_user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
