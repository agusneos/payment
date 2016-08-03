-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 26, 2015 at 12:30 PM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eform`
--

-- --------------------------------------------------------

--
-- Table structure for table `departemen`
--

CREATE TABLE IF NOT EXISTS `departemen` (
  `departemen_id` int(1) NOT NULL AUTO_INCREMENT,
  `departemen_induk` int(1) NOT NULL,
  `departemen_nama` varchar(30) NOT NULL,
  PRIMARY KEY (`departemen_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Master Departemen' AUTO_INCREMENT=24 ;

--
-- Dumping data for table `departemen`
--

INSERT INTO `departemen` (`departemen_id`, `departemen_induk`, `departemen_nama`) VALUES
(1, 0, 'ACCOUNTING'),
(2, 0, 'ENG'),
(3, 0, 'HRD / GA'),
(4, 0, 'PPIC'),
(5, 0, 'PRODUKSI 1'),
(6, 0, 'PRODUKSI 2'),
(7, 0, 'PURCHASING'),
(8, 0, 'QA'),
(9, 0, 'QHSE'),
(10, 0, 'WAREHOUSE'),
(11, 1, 'Finance'),
(12, 2, 'ISIR'),
(13, 0, 'test'),
(14, 5, 'Rolling'),
(16, 0, 'ty'),
(18, 0, 'hh'),
(19, 0, 'aa'),
(21, 8, 'COI'),
(23, 0, 'baru');

-- --------------------------------------------------------

--
-- Table structure for table `fabsenman`
--

CREATE TABLE IF NOT EXISTS `fabsenman` (
  `fabsenman_id` int(1) NOT NULL AUTO_INCREMENT,
  `fabsenman_tanggal` date NOT NULL,
  `fabsenman_nik` varchar(6) NOT NULL,
  `fabsenman_bagian` varchar(30) NOT NULL,
  `fabsenman_datang` time NOT NULL,
  `fabsenman_pulang` time NOT NULL,
  `fabsenman_alasan` text NOT NULL,
  `fabsenman_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fabsenman_disetujui` enum('N','Y') NOT NULL DEFAULT 'N',
  `fabsenman_diketahui` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`fabsenman_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `fabsenman`
--

INSERT INTO `fabsenman` (`fabsenman_id`, `fabsenman_tanggal`, `fabsenman_nik`, `fabsenman_bagian`, `fabsenman_datang`, `fabsenman_pulang`, `fabsenman_alasan`, `fabsenman_timestamp`, `fabsenman_disetujui`, `fabsenman_diketahui`) VALUES
(1, '2015-01-07', '080091', '21', '07:15:00', '03:00:00', 'finger tidak kebaca', '2015-01-06 09:18:58', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `fbpb`
--

CREATE TABLE IF NOT EXISTS `fbpb` (
  `fbpb_id` int(1) NOT NULL AUTO_INCREMENT,
  `fbpb_tanggal` date NOT NULL,
  `fbpb_nik` varchar(6) NOT NULL,
  `fbpb_bagian` int(1) NOT NULL,
  `fbpb_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `fbpb_disetujui` enum('N','Y') NOT NULL DEFAULT 'N',
  `fbpb_diketahui` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`fbpb_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `fbpb`
--

INSERT INTO `fbpb` (`fbpb_id`, `fbpb_tanggal`, `fbpb_nik`, `fbpb_bagian`, `fbpb_timestamp`, `fbpb_disetujui`, `fbpb_diketahui`) VALUES
(1, '2015-01-19', '080091', 11, '2015-01-19 04:30:32', 'N', 'N'),
(2, '2015-02-25', '234', 11, '2015-02-25 01:14:49', 'N', 'N'),
(3, '2015-02-25', '090900', 14, '2015-02-25 02:22:19', 'N', 'N'),
(4, '2015-02-25', '123', 12, '2015-02-25 06:39:08', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `fbpb_detail`
--

CREATE TABLE IF NOT EXISTS `fbpb_detail` (
  `fbpb_detail_id` int(1) NOT NULL AUTO_INCREMENT,
  `fbpb_detail_header` int(1) NOT NULL,
  `fbpb_detail_barang` text NOT NULL,
  `fbpb_detail_qty` double NOT NULL,
  `fbpb_detail_digunakan` date NOT NULL,
  `fbpb_detail_stock` double NOT NULL,
  `fbpb_detail_pemakaian` double NOT NULL,
  `fbpb_detail_ket` text NOT NULL,
  PRIMARY KEY (`fbpb_detail_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `fbpb_detail`
--

INSERT INTO `fbpb_detail` (`fbpb_detail_id`, `fbpb_detail_header`, `fbpb_detail_barang`, `fbpb_detail_qty`, `fbpb_detail_digunakan`, `fbpb_detail_stock`, `fbpb_detail_pemakaian`, `fbpb_detail_ket`) VALUES
(1, 1, 'HARDDISK', 1, '2015-01-20', 0, 1, 'RRRae'),
(2, 1, 'RAM', 2, '2015-01-20', 0, 1, 'BUAT PC BARU'),
(4, 1, 'HARDDISK', 1, '2015-01-20', 0, 1, 'buat pc baru'),
(5, 1, 'VGA', 2, '2015-01-20', 0, 1, 'BUAT PC BARU'),
(6, 1, 'RAM', 2, '2015-01-20', 0, 1, 'BUAT PC BARU'),
(7, 1, 'HARDDISK', 1, '2015-01-20', 0, 1, 'buat pc baru'),
(8, 1, 'RAM', 2, '2015-01-20', 0, 1, 'BUAT PC BARU'),
(9, 2, 'asasa', 2, '2015-02-25', 2, 2, 'dsfsdds'),
(10, 3, 'MOUSE', 1, '2015-03-04', 0, 1, 'STOCK'),
(11, 4, 'sdhfbjsh', 2, '2015-02-25', 8, 8, 'afba');

-- --------------------------------------------------------

--
-- Table structure for table `fcuti`
--

CREATE TABLE IF NOT EXISTS `fcuti` (
  `fcuti_id` int(1) NOT NULL AUTO_INCREMENT,
  `fcuti_tanggal` date NOT NULL,
  `fcuti_nik` varchar(6) NOT NULL,
  `fcuti_bagian` varchar(30) NOT NULL,
  `fcuti_dari` date NOT NULL,
  `fcuti_sampai` date NOT NULL,
  `fcuti_keperluan` text NOT NULL,
  `fcuti_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fcuti_disetujui` enum('N','Y') NOT NULL DEFAULT 'N',
  `fcuti_diketahui` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`fcuti_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `fcuti`
--

INSERT INTO `fcuti` (`fcuti_id`, `fcuti_tanggal`, `fcuti_nik`, `fcuti_bagian`, `fcuti_dari`, `fcuti_sampai`, `fcuti_keperluan`, `fcuti_timestamp`, `fcuti_disetujui`, `fcuti_diketahui`) VALUES
(1, '2015-01-05', '080091', '11', '2015-01-09', '2015-01-09', 'nnnnnnnnnnn', '2015-01-06 08:43:38', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `fdl`
--

CREATE TABLE IF NOT EXISTS `fdl` (
  `fdl_id` int(1) NOT NULL AUTO_INCREMENT,
  `fdl_tanggal` date NOT NULL,
  `fdl_nik` varchar(6) NOT NULL,
  `fdl_bagian` varchar(30) NOT NULL,
  `fdl_dinas` enum('LUAR KOTA','LUAR NEGERI') NOT NULL,
  `fdl_dari` date NOT NULL,
  `fdl_sampai` date NOT NULL,
  `fdl_jam` time NOT NULL,
  `fdl_tujuan` text NOT NULL,
  `fdl_bersama` text NOT NULL,
  `fdl_keperluan` text NOT NULL,
  `fdl_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fdl_disetujui` enum('N','Y') NOT NULL DEFAULT 'N',
  `fdl_diketahui` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`fdl_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `fdl`
--

INSERT INTO `fdl` (`fdl_id`, `fdl_tanggal`, `fdl_nik`, `fdl_bagian`, `fdl_dinas`, `fdl_dari`, `fdl_sampai`, `fdl_jam`, `fdl_tujuan`, `fdl_bersama`, `fdl_keperluan`, `fdl_timestamp`, `fdl_disetujui`, `fdl_diketahui`) VALUES
(1, '2015-01-07', '080091', '21', 'LUAR NEGERI', '2015-02-25', '2015-02-25', '13:00:00', 'HJP-CBT', 'Feri', 'tarik kabel', '2015-01-06 09:18:58', 'N', 'N'),
(4, '2015-02-25', '123', '21', 'LUAR NEGERI', '2015-02-17', '2015-02-26', '01:00:00', 'was', 'asdsa', 'asdas', '2015-02-25 05:02:48', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `fizin`
--

CREATE TABLE IF NOT EXISTS `fizin` (
  `fizin_id` int(1) NOT NULL AUTO_INCREMENT,
  `fizin_tanggal` date NOT NULL,
  `fizin_nik` varchar(6) NOT NULL,
  `fizin_bagian` varchar(30) NOT NULL,
  `fizin_jenis` enum('','TIDAK HADIR','P. CEPAT','D.TERLAMBAT','KELUAR') NOT NULL,
  `fizin_dari` datetime NOT NULL,
  `fizin_sampai` datetime NOT NULL,
  `fizin_keperluan` text NOT NULL,
  `fizin_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fizin_disetujui` enum('N','Y') NOT NULL DEFAULT 'N',
  `fizin_diketahui` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`fizin_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `fizin`
--

INSERT INTO `fizin` (`fizin_id`, `fizin_tanggal`, `fizin_nik`, `fizin_bagian`, `fizin_jenis`, `fizin_dari`, `fizin_sampai`, `fizin_keperluan`, `fizin_timestamp`, `fizin_disetujui`, `fizin_diketahui`) VALUES
(1, '2014-12-01', '080091', '11', 'D.TERLAMBAT', '2014-12-01 00:00:00', '2014-12-02 13:00:00', 'MAKAN MAKAN', '2014-12-01 09:15:47', 'N', 'N'),
(2, '2015-02-20', '090900', '14', 'TIDAK HADIR', '2015-02-23 14:15:03', '2015-02-23 14:15:45', 'Kep. Keluarga', '2015-02-20 07:16:08', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `fpjken`
--

CREATE TABLE IF NOT EXISTS `fpjken` (
  `fpjken_id` int(1) NOT NULL AUTO_INCREMENT,
  `fpjken_tanggal` date NOT NULL,
  `fpjken_nik` varchar(6) NOT NULL,
  `fpjken_bagian` varchar(30) NOT NULL,
  `fpjken_pinjam` date NOT NULL,
  `fpjken_mobil` varchar(13) NOT NULL,
  `fpjken_keperluan` text NOT NULL,
  `fpjken_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fpjken_disetujui` enum('N','Y') NOT NULL DEFAULT 'N',
  `fpjken_diketahui` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`fpjken_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `fpjken`
--

INSERT INTO `fpjken` (`fpjken_id`, `fpjken_tanggal`, `fpjken_nik`, `fpjken_bagian`, `fpjken_pinjam`, `fpjken_mobil`, `fpjken_keperluan`, `fpjken_timestamp`, `fpjken_disetujui`, `fpjken_diketahui`) VALUES
(2, '2015-01-07', '080091', '11', '2015-01-08', 'B 1234 ZZZ', 'EEEEEEEE', '2015-01-06 09:18:58', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `fterlambat`
--

CREATE TABLE IF NOT EXISTS `fterlambat` (
  `fterlambat_id` int(1) NOT NULL AUTO_INCREMENT,
  `fterlambat_tanggal` date NOT NULL,
  `fterlambat_nik` varchar(6) NOT NULL,
  `fterlambat_bagian` varchar(30) NOT NULL,
  `fterlambat_shift` enum('1','2','3') NOT NULL,
  `fterlambat_waktu` time NOT NULL,
  `fterlambat_alasan` text NOT NULL,
  `fterlambat_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fterlambat_disetujui` enum('N','Y') NOT NULL DEFAULT 'N',
  `fterlambat_diketahui` enum('N','Y') NOT NULL DEFAULT 'N',
  PRIMARY KEY (`fterlambat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `fterlambat`
--

INSERT INTO `fterlambat` (`fterlambat_id`, `fterlambat_tanggal`, `fterlambat_nik`, `fterlambat_bagian`, `fterlambat_shift`, `fterlambat_waktu`, `fterlambat_alasan`, `fterlambat_timestamp`, `fterlambat_disetujui`, `fterlambat_diketahui`) VALUES
(1, '2015-01-07', '080091', '21', '1', '08:15:00', 'macet', '2015-01-06 09:18:58', 'N', 'N');

-- --------------------------------------------------------

--
-- Table structure for table `karyawan`
--

CREATE TABLE IF NOT EXISTS `karyawan` (
  `karyawan_nik` varchar(6) NOT NULL,
  `karyawan_nama` varchar(50) NOT NULL,
  `karyawan_bagian` int(1) NOT NULL,
  PRIMARY KEY (`karyawan_nik`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `karyawan`
--

INSERT INTO `karyawan` (`karyawan_nik`, `karyawan_nama`, `karyawan_bagian`) VALUES
('080091', 'Agus Setiawan', 11),
('090900', 'Romi Rafael', 12),
('121212', 'Arie Untung', 21),
('123', 'Feri Der Panzer', 11),
('234', 'Budi Doremi', 11);

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `parentId` int(1) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `allowed` varchar(255) NOT NULL,
  `iconCls` varchar(255) NOT NULL,
  `type` enum('dialog','messager','tabs','window') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Admin Menu' AUTO_INCREMENT=20 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `parentId`, `uri`, `allowed`, `iconCls`, `type`) VALUES
(1, 'Master', 0, '', '+1+2+', 'icon-master', ''),
(2, 'Transaksi', 0, '', '+1+3+', 'icon-transaksi', ''),
(3, 'Report', 0, '', '+1+', 'icon-print', ''),
(4, 'Admin', 0, '', '+1+', 'icon-admin', ''),
(5, 'Setting', 0, '', '+1+', 'icon-setup', ''),
(6, 'Admin User', 4, 'admin/user', '+1+', 'icon-user', 'tabs'),
(7, 'Admin Menu', 4, 'admin/menu', '+1+', 'icon-menu', 'tabs'),
(8, 'General', 5, '', '+1+', 'icon-general', 'dialog'),
(9, 'Master Karyawan', 1, 'master/karyawan', '+1+2+', 'icon-master', 'tabs'),
(10, 'Master Departemen', 1, 'master/departemen', '+1+2+', 'icon-master', 'tabs'),
(12, 'Permohonan Izin', 2, 'transaksi/izin', '+1+3+', 'icon-transaksi', 'tabs'),
(13, 'Permintaan Cuti', 2, 'transaksi/cuti', '+1+2+', 'icon-transaksi', 'tabs'),
(14, 'Pinjam Kendaraan', 2, 'transaksi/pjken', '+1+2+', 'icon-transaksi', 'tabs'),
(15, 'Bon Permintaan Barang', 2, 'transaksi/bpb', '+1+2+', 'icon-transaksi', 'tabs'),
(16, 'Approval', 0, '', '+1+2+', '', ''),
(17, 'Daftar Keterlambatan', 2, 'transaksi/terlambat', '+1+2+', 'icon-transaksi', 'tabs'),
(18, 'Absensi Manual', 2, 'transaksi/absenman', '+1+2+', 'icon-transaksi', 'tabs'),
(19, 'Dinas Luar', 2, 'transaksi/dl', '+1+2+', 'icon-transaksi', 'tabs');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(1) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `username` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `password` varchar(100) NOT NULL,
  `level` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Master User' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `password`, `level`) VALUES
(1, 'Agus Setiawan', 'agus', 'c4ca4238a0b923820dcc509a6f75849b', '+1+'),
(2, 'Karyadi', 'kkyadi', '2efdd753174db2b1965109b5f810eca0', '+2+'),
(3, 'Operator', 'operator', 'dba112ea4706f5a0a21bfe8d50221532', '+3+');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
