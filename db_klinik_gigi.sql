/*
SQLyog Community v13.3.1 (64 bit)
MySQL - 10.4.32-MariaDB : Database - klinik_gigi
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`klinik_gigi` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `klinik_gigi`;

/*Table structure for table `booking` */

DROP TABLE IF EXISTS `booking`;

CREATE TABLE `booking` (
  `id_booking` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date DEFAULT NULL,
  `jam` time DEFAULT NULL,
  `status` enum('Pending','Disetujui','Selesai','Ditolak') DEFAULT 'Pending',
  `id_users` int(11) DEFAULT NULL,
  `id_dokter` int(11) DEFAULT NULL,
  `keluhan` text DEFAULT NULL,
  PRIMARY KEY (`id_booking`),
  KEY `id_users` (`id_users`),
  KEY `id_dokter` (`id_dokter`),
  CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`id_users`) REFERENCES `users` (`id_users`),
  CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`id_dokter`) REFERENCES `dokter` (`id_dokter`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `booking` */

insert  into `booking`(`id_booking`,`tanggal`,`jam`,`status`,`id_users`,`id_dokter`,`keluhan`) values 
(1,'2025-12-05','15:00:00','Selesai',4,1,NULL),
(4,'2026-05-18','09:00:00','Disetujui',6,1,NULL),
(5,'2026-05-25','09:00:00','Disetujui',7,1,NULL),
(6,'2026-06-01','10:00:00','Selesai',4,1,'sakit hati diputusin ayang'),
(7,'2026-06-04','09:09:00','Selesai',4,3,'sakit pinggang');

/*Table structure for table `dokter` */

DROP TABLE IF EXISTS `dokter`;

CREATE TABLE `dokter` (
  `id_dokter` int(11) NOT NULL AUTO_INCREMENT,
  `nama_dokter` varchar(100) DEFAULT NULL,
  `spesialis` varchar(100) DEFAULT NULL,
  `jadwal` varchar(100) DEFAULT NULL,
  `alamat_klinik` varchar(250) DEFAULT NULL,
  `foto_dokter` varchar(255) DEFAULT NULL,
  `sertifikat` varchar(255) DEFAULT NULL,
  `deskripsi` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_dokter`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `dokter` */

insert  into `dokter`(`id_dokter`,`nama_dokter`,`spesialis`,`jadwal`,`alamat_klinik`,`foto_dokter`,`sertifikat`,`deskripsi`) values 
(1,'drg. Afif','Dokter Gigi Umum','Senin 08:00-13:00','Jl. Paus Dalam No.7, RT.1/RW.7, Rawamangun, Kec. Pulo Gadung, Kota Jakarta Timur','dr-alif.jpg','sertifikat1.jpg','saya adalah dokter spesialis gigi umum yang berpengalaman lebih dari 5 th'),
(3,'drg.Diana','Konservasi Gigi','Kamis 07:00-15:00','Jl. Pahlawan Revolusi No.16B, RT.5/RW.4, Pd. Bambu, Kec. Duren Sawit, Kota Jakarta Timur.','dr-diana.jpg','sertifikat-drg-Diana.webp','saya adalah dokter spesialis gigi di bagian konservasi gigi yang berpengalaman lebih dari 10th'),
(10,'drg.surya','Prostodonti','Selasa 08:00-15:00','Jl. Merdeka No. 5, RT 02/RW 04, Kelurahan Menteng, Kecamatan Gambir, Jakarta Pusat, DKI Jakarta 10310.','dr-surya.jpeg','sertifikat-3.webp','saya merupakan dokter dengan spesialis prostodonti yang berpengalaman lebih dari 4 tahun'),
(11,'drg.andika','Konservasi Gigi','Jumat 13:00-21:00','Jl. Jenderal Sudirman Kav. 21, RT 01/RW 03, Kelurahan Karet Semanggi, Kecamatan Setiabudi, Jakarta Selatan, DKI Jakarta 12930.','dr-andika.jpg','sertfikat-andika.jpg','saya dokter yang perngalaman di bidang konservasi gigi selama 7 tahun lebih.'),
(12,'drg.anisa','Periodonsia','Rabu 08:00-14:00','Jl. Ahmad Yani No. 117, RT 04/RW 02, Kelurahan Jemur Wonosari, Kecamatan Wonocolo, Kota Surabaya, Jawa Timur 60237.','dr-anisa.jpg','sertifikat-4.webp','saya dokter  pada bidang periodonsia yang telah berpengalaman lebih dari 5 tahun'),
(13,'drg.ayuthya','Dokter Gigi Umum','Jumat 07:00-14:00',' Jl. Raya Trunojoyo No. 7, RT 02/RW 01, Kelurahan Pejagan, Kecamatan Bangkalan, Kabupaten Bangkalan, Jawa Timur 69112.','dr-ayuthya.jpg','sertifikat-ayuthya.jpg','saya seorang dokter gigi umum yang yang perkecipung dibidang ini selama hampir 10 tahun');

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id_users` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','pasien') DEFAULT 'pasien',
  PRIMARY KEY (`id_users`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

/*Data for the table `users` */

insert  into `users`(`id_users`,`nama`,`email`,`password`,`role`) values 
(1,'yanto','blabla@gmail.com','$2y$10$rT7xSfzY86K/fUnr/EYkCOCu6EMh.fxsybPc2Uv0SU6lp.4fcFblK','pasien'),
(3,'yanto','yanto@gmail.com','$2y$10$ZeBGMhw0Wp1wEuKpkuQGWuZAXLt7lP..SfKjOfaR1AJs/177xiaDm','pasien'),
(4,'yayan','yayan@gmail.com','$2y$10$0YOgGU.FPvVYZgcDGgmEHuLYzlpKCSF9NG/yr7tCTb1Ni5fy/Xbl.','pasien'),
(5,'admin','admin@gmail.com','$2y$10$sLnbrr3gr5WCrL6mU/uUGOOAD1sK1eEGcsVCDnptVsxIG5DoqM9R6','admin'),
(6,'ujang','ujang@gmail.com','$2y$10$TR1QjSQ.QZvMxQzHjnqqrutv/JExFdLWKaZ6WHfMYKY5hwU25mIi2','pasien'),
(7,'aku','aku@gmail.com','$2y$10$rirTzR8rewoAahuDSDSBxOhkFjx/7dOr007UAWiJpPs/gVKQDkxbO','pasien');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
