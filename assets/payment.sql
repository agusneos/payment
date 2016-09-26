-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2016 at 10:42 AM
-- Server version: 5.5.27
-- PHP Version: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `payment`
--
CREATE DATABASE `payment` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `payment`;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Admin Menu' AUTO_INCREMENT=26 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `parentId`, `uri`, `allowed`, `iconCls`, `type`) VALUES
(1, 'Master', 0, '', '+1+2+5+', 'icon-master', ''),
(2, 'Transaksi', 0, '', '+1+2+5+', 'icon-transaksi', ''),
(3, 'Report', 0, '', '+1+2+5+', 'icon-print', ''),
(4, 'Admin', 0, '', '+1+', 'icon-admin', ''),
(5, 'Setting', 0, '', '+1+2+', 'icon-setup', ''),
(6, 'Admin User', 4, 'admin/user', '+1+', 'icon-user', 'tabs'),
(7, 'Admin Menu', 4, 'admin/menu', '+1+', 'icon-menu', 'tabs'),
(8, 'General', 5, '', '+1+2+', 'icon-general', 'dialog'),
(10, 'Vendor', 1, 'master/vendor', '+1+2+5+', 'icon-master', 'tabs'),
(14, 'Invoice', 1, 'master/invoice', '+1+2+5+', 'icon-invoices', 'tabs'),
(18, 'Cek Invoice', 2, 'transaksi/check', '+1+2+5+', 'icon-transaksi', 'tabs'),
(19, 'Pembayaran', 2, 'transaksi/payment', '+1+2+5+', 'icon-transaksi', 'tabs'),
(20, 'Saldo Supplier', 3, 'report/saldo_supplier', '+1+2+5+', 'icon-print', 'dialog'),
(21, 'Total Pembelian Supplier', 3, 'report/total_supplier', '+1+2+5+', 'icon-print', 'dialog'),
(23, 'Hutang Supplier', 3, 'report/hutang_supplier', '+1+2+5+', 'icon-print', 'dialog'),
(24, 'Voucher', 3, 'report/voucher', '+1+2+5+', 'icon-print', 'dialog'),
(25, 'Paymen Harian', 3, 'report/payment', '+1+2+5+', 'icon-print', 'dialog');

-- --------------------------------------------------------

--
-- Table structure for table `s_ci_sessions`
--

CREATE TABLE IF NOT EXISTS `s_ci_sessions` (
  `session_id` varchar(40) NOT NULL DEFAULT '0',
  `ip_address` varchar(45) NOT NULL DEFAULT '0',
  `user_agent` varchar(120) NOT NULL,
  `last_activity` int(10) unsigned NOT NULL DEFAULT '0',
  `user_data` text NOT NULL,
  PRIMARY KEY (`session_id`,`ip_address`),
  KEY `last_activity_idx` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `s_ci_sessions`
--

INSERT INTO `s_ci_sessions` (`session_id`, `ip_address`, `user_agent`, `last_activity`, `user_data`) VALUES
('9fd1d2e7e6e985f20bcbc7aa3638f5fe', '::1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', 1473382574, ''),
('d5a3390a5d8bb10ac9fee9d77c7e6562', '::1', 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36', 1473386686, '');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Master User' AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `username`, `password`, `level`) VALUES
(1, 'Agus Setiawan', 'agus', 'c4ca4238a0b923820dcc509a6f75849b', '+1+'),
(2, 'Wong Kian Hin', 'kianhin', 'cbdb25d48ccdca0e1cff6c6999544184', '+2+'),
(5, 'Sagateknindo', 'sagatek', '9908601e70c9a7f3063a9169fc24c94d', '+2+');

-- --------------------------------------------------------

--
-- Table structure for table `vendinvoicejour`
--

CREATE TABLE IF NOT EXISTS `vendinvoicejour` (
  `Id` bigint(1) unsigned NOT NULL AUTO_INCREMENT,
  `OrderAccount` varchar(20) NOT NULL,
  `InvoiceId` varchar(30) NOT NULL,
  `InvoiceDate` date NOT NULL,
  `Qty` decimal(10,0) NOT NULL,
  `SalesBalance` decimal(17,2) NOT NULL,
  `CurrencyCode` varchar(3) NOT NULL,
  `ExchRate` decimal(6,0) NOT NULL,
  `CheckDate` datetime NOT NULL,
  `Timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `AcceptDate` date NOT NULL,
  `PayDate` date NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Invoice Journal' AUTO_INCREMENT=13 ;

--
-- Dumping data for table `vendinvoicejour`
--

INSERT INTO `vendinvoicejour` (`Id`, `OrderAccount`, `InvoiceId`, `InvoiceDate`, `Qty`, `SalesBalance`, `CurrencyCode`, `ExchRate`, `CheckDate`, `Timestamp`, `AcceptDate`, `PayDate`) VALUES
(5, 'JKP', '08/JKP/VII/2016', '2016-07-12', 44000, 1966096.00, 'IDR', 1, '2016-09-07 15:11:19', '2016-09-07 08:08:34', '2016-09-21', '2016-09-30'),
(7, 'JKP', '019/JKP/VII/2016', '2016-07-27', 20000, 30319960.00, 'IDR', 1, '2016-09-08 10:39:31', '2016-09-07 08:08:34', '2016-09-26', '0000-00-00'),
(8, 'VANTAGE', '1050729002-STS', '2016-07-29', 2180290, 16151.20, 'USD', 13108, '0000-00-00 00:00:00', '2016-09-08 03:42:25', '0000-00-00', '0000-00-00'),
(9, 'VANTAGE', '1050715002-STS', '2016-07-15', 14657268, 92220.60, 'USD', 13169, '2016-09-08 10:42:46', '2016-09-08 03:42:25', '2016-09-06', '0000-00-00'),
(10, 'VANTAGE', '1050722001-STS', '2016-07-22', 5402450, 48748.74, 'USD', 13103, '2016-09-08 10:42:46', '2016-09-08 03:42:25', '2016-09-06', '0000-00-00'),
(11, 'VANTAGE', '1050725002-STS-IV', '2016-07-25', 9500, 71.35, 'USD', 13103, '2016-09-08 10:42:46', '2016-09-08 03:42:25', '2016-09-06', '0000-00-00'),
(12, 'JKP', '009/JKP/VII/2016', '2016-07-12', 10150, 21439990.00, 'IDR', 1, '2016-09-08 10:42:46', '2016-09-08 03:42:25', '2016-09-06', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `vendor`
--

CREATE TABLE IF NOT EXISTS `vendor` (
  `Id` varchar(20) NOT NULL,
  `Name` varchar(60) NOT NULL,
  `PayTerm` int(1) NOT NULL,
  `VendGroup` enum('LOKAL','IMPORT') NOT NULL,
  `Tax` enum('PPN','NON PPN') NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Master Vendor';

--
-- Dumping data for table `vendor`
--

INSERT INTO `vendor` (`Id`, `Name`, `PayTerm`, `VendGroup`, `Tax`) VALUES
('AKB', 'ANUGRAH KEMENANGAN BERSAMA', 14, 'LOKAL', 'NON PPN'),
('GIP', 'GALUNGGUNG INDOSTEEL PERKASA', 60, 'LOKAL', 'PPN'),
('GLOBAL', 'GLOBAL, INC.', 30, 'IMPORT', 'NON PPN'),
('HJP.CBT', 'HOTMAL JAYA PERKASA-CIBITUNG', 30, 'LOKAL', 'PPN'),
('HJP.TGR', 'HOTMAL JASA PERKASA-TANGERANG', 30, 'LOKAL', 'PPN'),
('HMP', 'HIKARI METALINDO PRATAMA', 30, 'LOKAL', 'PPN'),
('JKP', 'JAYA KURNIA PERKASA', 30, 'LOKAL', 'PPN'),
('MAM', 'MANDIRI AKSARA MULIA', 30, 'LOKAL', 'PPN'),
('MCM', 'MENARA CIPTA METALINDO', 30, 'LOKAL', 'PPN'),
('OMI', 'OCHIAI MENARA INDONESIA', 30, 'LOKAL', 'PPN'),
('PNC', 'PANACIPTA SEINAN COMP.', 30, 'LOKAL', 'PPN'),
('RKC', 'ROOTS KYUSHU CO.,LTD.', 30, 'IMPORT', 'NON PPN'),
('ROOTS', 'ROOTS TRADING CO.,LTD', 30, 'IMPORT', 'NON PPN'),
('SIM', 'SIGMA INDONESIA MANUFACTURING', 30, 'LOKAL', 'PPN'),
('SM', 'SAN MARGA', 30, 'LOKAL', 'NON PPN'),
('TAIYO', 'TAIYO FASTENER (THAILAND) CO.LTD.', 30, 'IMPORT', 'NON PPN'),
('TBM', 'THREE BOND MANUFACTURING IND.', 30, 'LOKAL', 'PPN'),
('VANTAGE', 'VANTAGE ASPIRE CO., LTD', 30, 'IMPORT', 'NON PPN'),
('VELASARI', 'VELASARI INTERNATIONAL CO, LTD', 30, 'IMPORT', 'NON PPN'),
('ZHEJIANG', 'ZHEJIANG TOKUHATSU METALPROD.CORP', 30, 'IMPORT', 'NON PPN'),
('ZUNLI', 'ZUNLI INTERNATIONAL CO.,LTD', 30, 'IMPORT', 'NON PPN');

-- --------------------------------------------------------

--
-- Table structure for table `voucher`
--

CREATE TABLE IF NOT EXISTS `voucher` (
  `Voucher_Id` bigint(1) NOT NULL AUTO_INCREMENT,
  `VendInvoiceJour_Id` bigint(1) unsigned NOT NULL,
  `OrderAccount` varchar(20) NOT NULL,
  `PaymentNumber` varchar(30) NOT NULL,
  `PaymentDate` date NOT NULL,
  `Note` text NOT NULL,
  `DebetUSD` decimal(17,2) NOT NULL,
  `DebetIDR` decimal(17,2) NOT NULL,
  `KreditUSD` decimal(17,2) NOT NULL,
  `KreditIDR` decimal(17,2) NOT NULL,
  `PaymentCreateDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Voucher_Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='Tabel Voucher' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `voucher`
--

INSERT INTO `voucher` (`Voucher_Id`, `VendInvoiceJour_Id`, `OrderAccount`, `PaymentNumber`, `PaymentDate`, `Note`, `DebetUSD`, `DebetIDR`, `KreditUSD`, `KreditIDR`, `PaymentCreateDate`) VALUES
(2, 5, 'JKP', '08/JKP/VII/2016', '2016-07-12', '', 0.00, 0.00, 0.00, 1966096.00, '2016-09-07 08:11:19'),
(3, 5, 'JKP', 'dfds', '2016-09-30', '08/JKP/VII/2016', 0.00, 1966096.00, 0.00, 0.00, '2016-09-07 08:38:22'),
(7, 7, 'JKP', '019/JKP/VII/2016', '2016-07-27', '', 0.00, 0.00, 0.00, 30319960.00, '2016-09-08 03:39:31'),
(8, 12, 'JKP', '009/JKP/VII/2016', '2016-09-06', '', 0.00, 0.00, 0.00, 21439990.00, '2016-09-08 03:42:46'),
(9, 10, 'VANTAGE', '1050722001-STS', '2016-09-06', '', 0.00, 0.00, 48748.74, 638754740.00, '2016-09-08 03:42:46'),
(10, 9, 'VANTAGE', '1050715002-STS', '2016-09-06', '', 0.00, 0.00, 92220.60, 1214453081.00, '2016-09-08 03:42:47'),
(11, 11, 'VANTAGE', '1050725002-STS-IV', '2016-09-06', '', 0.00, 0.00, 71.35, 934899.00, '2016-09-08 03:42:47');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
