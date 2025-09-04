# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.7.28)
# Database: scl_sanmarudb
# Generation Time: 2019-11-22 07:17:06 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table course
# ------------------------------------------------------------

DROP TABLE IF EXISTS `course`;

CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `course_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `course` WRITE;
/*!40000 ALTER TABLE `course` DISABLE KEYS */;

INSERT INTO `course` (`id`, `name`, `course_code`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`)
VALUES
	(1,'Matematika','001','2019-11-22 02:12:59','2019-11-22 02:12:59','0000-00-00 00:00:00',0);

/*!40000 ALTER TABLE `course` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table course_teacher
# ------------------------------------------------------------

DROP TABLE IF EXISTS `course_teacher`;

CREATE TABLE `course_teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `teacher_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `teacher_id` (`teacher_id`),
  KEY `course_id` (`course_id`),
  CONSTRAINT `course_teacher_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teacher` (`id`),
  CONSTRAINT `course_teacher_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2014_10_12_000000_create_users_table',1),
	(2,'2014_10_12_100000_create_password_resets_table',1);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table password_resets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table payment_agreement
# ------------------------------------------------------------

DROP TABLE IF EXISTS `payment_agreement`;

CREATE TABLE `payment_agreement` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `desc` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `payment_agreement` WRITE;
/*!40000 ALTER TABLE `payment_agreement` DISABLE KEYS */;

INSERT INTO `payment_agreement` (`id`, `name`, `desc`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`)
VALUES
	(1,'luluq','setuju','2019-11-22 01:30:04','0000-00-00 00:00:00','0000-00-00 00:00:00',0),
	(2,'Yulius antoni','Melunasi atau membayar secara tunai minimal 50% pada saat penyelesaian administrasi','2019-11-22 07:01:07','2019-11-22 07:01:07','0000-00-00 00:00:00',0),
	(3,'Yulius antoni','Melunasi atau membayar secara tunai minimal 50% pada saat penyelesaian administrasi','2019-11-22 07:13:40','2019-11-22 07:13:40','0000-00-00 00:00:00',0),
	(4,'Yulius antoni','Melunasi atau membayar secara tunai minimal 50% pada saat penyelesaian administrasi','2019-11-22 07:09:05','2019-11-22 07:09:05','0000-00-00 00:00:00',0),
	(5,'Yulius antoni','Membayar SPP rutin setiap bulannya','2019-11-22 07:15:20','2019-11-22 07:15:20','0000-00-00 00:00:00',0);

/*!40000 ALTER TABLE `payment_agreement` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table student
# ------------------------------------------------------------

DROP TABLE IF EXISTS `student`;

CREATE TABLE `student` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `address` text,
  `father_name` varchar(255) DEFAULT NULL,
  `mother_name` varchar(255) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `payment_agreement_id` int(11) DEFAULT NULL,
  `school_year` varchar(255) DEFAULT NULL,
  `register_number` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `unit_id` (`unit_id`),
  KEY `payment_agreement_id` (`payment_agreement_id`),
  CONSTRAINT `student_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `student_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`),
  CONSTRAINT `student_ibfk_3` FOREIGN KEY (`payment_agreement_id`) REFERENCES `payment_agreement` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;

INSERT INTO `student` (`id`, `user_id`, `nik`, `name`, `email`, `phone`, `mobile_phone`, `address`, `father_name`, `mother_name`, `unit_id`, `payment_agreement_id`, `school_year`, `register_number`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`)
VALUES
	(2,1,'3507090804900003','luluq','luluq.ye@gmail.com','08563531001','08563531001','Malang','Suharsosno','Suningsih Setyaningrum',1,1,'2013','08563531001','2019-11-22 01:30:21','2019-11-22 01:30:21','0000-00-00 00:00:00',0),
	(3,NULL,NULL,'Yulius antoni','chamiedha@ymail.com','23234234','234234','Tanggung','Suharsosno','Suningsih Setyaningrum',NULL,5,NULL,NULL,'2019-11-22 07:15:20','2019-11-22 07:15:20','0000-00-00 00:00:00',0);

/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table teacher
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teacher`;

CREATE TABLE `teacher` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nik` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `mobile_phone` varchar(255) DEFAULT NULL,
  `address` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `teacher_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `teacher` WRITE;
/*!40000 ALTER TABLE `teacher` DISABLE KEYS */;

INSERT INTO `teacher` (`id`, `user_id`, `nik`, `name`, `email`, `phone`, `mobile_phone`, `address`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`)
VALUES
	(1,1,'3507090804900003','Luluq Miftakhul huda','luluq.ye@gmail.com','08563531001','08563531001','Malang','2019-11-22 00:03:55','2019-11-22 00:03:55','0000-00-00 00:00:00',0),
	(2,2,'3507090804900002','Lilik Rofiatul Chamida','lilik@gmail.com','08563531001','08563531001','Malang','2019-11-22 00:09:41','2019-11-22 00:09:41','0000-00-00 00:00:00',0);

/*!40000 ALTER TABLE `teacher` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table uniform
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uniform`;

CREATE TABLE `uniform` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `level` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `prize_basic` float DEFAULT NULL,
  `prize_male` float DEFAULT NULL,
  `prize_female` float DEFAULT NULL,
  `brand` varchar(255) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `is_published` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `unit_id` (`unit_id`),
  CONSTRAINT `uniform_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `uniform` WRITE;
/*!40000 ALTER TABLE `uniform` DISABLE KEYS */;

INSERT INTO `uniform` (`id`, `name`, `level`, `size`, `gender`, `prize_basic`, `prize_male`, `prize_female`, `brand`, `unit_id`, `is_published`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`)
VALUES
	(1,'Seragam Abu Abu','Intermediate','large','male',500000,600000,700000,'Cardinal',1,0,'2019-11-22 02:49:06','2019-11-22 02:49:06','0000-00-00 00:00:00',0);

/*!40000 ALTER TABLE `uniform` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table uniform_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `uniform_order`;

CREATE TABLE `uniform_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_no` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `uniform_id` int(11) DEFAULT NULL,
  `unit_id` int(11) DEFAULT NULL,
  `order_type` varchar(255) DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `discount_free` varchar(255) DEFAULT NULL,
  `notes` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `uniform_id` (`uniform_id`),
  KEY `unit_id` (`unit_id`),
  CONSTRAINT `uniform_order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `student` (`id`),
  CONSTRAINT `uniform_order_ibfk_2` FOREIGN KEY (`uniform_id`) REFERENCES `uniform` (`id`),
  CONSTRAINT `uniform_order_ibfk_3` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;



# Dump of table unit
# ------------------------------------------------------------

DROP TABLE IF EXISTS `unit`;

CREATE TABLE `unit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `unit_code` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `unit` WRITE;
/*!40000 ALTER TABLE `unit` DISABLE KEYS */;

INSERT INTO `unit` (`id`, `name`, `city`, `unit_code`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`)
VALUES
	(1,'Unit Siswa','Malang','001','2019-11-22 01:56:22','2019-11-22 01:56:22','0000-00-00 00:00:00',0),
	(2,'Unit Guru','Malang','003','2019-11-22 01:55:49','2019-11-22 01:55:49','0000-00-00 00:00:00',0);

/*!40000 ALTER TABLE `unit` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `is_active` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` int(11) DEFAULT '0',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `remember_token` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;

INSERT INTO `user` (`id`, `user_type`, `username`, `password`, `is_active`, `created_at`, `updated_at`, `is_deleted`, `deleted_at`, `remember_token`)
VALUES
	(1,'admin','luluq','$2y$10$bAvGIDVNi61Cw5Wkpz22iughoztXCJgFxw7RknKKCrHxxXNB9wx86','1','2019-11-22 06:22:06','2019-11-21 18:38:27',0,'2019-11-20 00:00:00','IUdTRKvbBUIgqah98A88a9dOX8KzbRVfpvT33NpoVplPL5YtR6x0jLBHV0um'),
	(2,'guru','admin','administrator',NULL,'2019-11-21 18:10:48','2019-11-21 18:10:48',1,'0000-00-00 00:00:00',NULL),
	(3,'siswa','adminlomba','asdfghjkl','1','2019-11-22 02:50:08','2019-11-22 02:50:08',1,'0000-00-00 00:00:00',NULL);

/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table vendor
# ------------------------------------------------------------

DROP TABLE IF EXISTS `vendor`;

CREATE TABLE `vendor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `address` text,
  `city` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `nota_number` varchar(255) DEFAULT NULL,
  `nota_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `is_deleted` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `vendor` WRITE;
/*!40000 ALTER TABLE `vendor` DISABLE KEYS */;

INSERT INTO `vendor` (`id`, `name`, `address`, `city`, `phone`, `pic`, `nota_number`, `nota_date`, `created_at`, `updated_at`, `deleted_at`, `is_deleted`)
VALUES
	(2,'Vendor Latnas','Malang','Malang','08563531001','Luluq','Malang','2019-04-01','2019-11-22 03:11:37','2019-11-22 03:11:37','0000-00-00 00:00:00',0);

/*!40000 ALTER TABLE `vendor` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table vendor_order
# ------------------------------------------------------------

DROP TABLE IF EXISTS `vendor_order`;

CREATE TABLE `vendor_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `vendor_id` int(11) DEFAULT NULL,
  `uniform_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `vendor_id` (`vendor_id`),
  KEY `uniform_id` (`uniform_id`),
  CONSTRAINT `vendor_order_ibfk_1` FOREIGN KEY (`vendor_id`) REFERENCES `vendor` (`id`),
  CONSTRAINT `vendor_order_ibfk_2` FOREIGN KEY (`uniform_id`) REFERENCES `uniform` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;




/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
