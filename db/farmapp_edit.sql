/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 8.0.18 : Database - farmapp
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`farmapp` /*!40100 DEFAULT CHARACTER SET utf8mb4 */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `farmapp`;

/*Table structure for table `account_balance` */

DROP TABLE IF EXISTS `account_balance`;

CREATE TABLE `account_balance` (
  `accId` int(11) NOT NULL AUTO_INCREMENT,
  `accAmount` double NOT NULL,
  `accBalance` double NOT NULL,
  `accActive` tinyint(4) NOT NULL,
  PRIMARY KEY (`accId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `expenditures` */

DROP TABLE IF EXISTS `expenditures`;

CREATE TABLE `expenditures` (
  `expId` int(11) NOT NULL AUTO_INCREMENT,
  `expUId` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `nId` int(20) NOT NULL,
  `expPayeeName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `expActive` int(12) NOT NULL DEFAULT '1',
  `expenditureName` varchar(200) NOT NULL,
  `expenditureDescription` varchar(255) DEFAULT NULL,
  `expenditureCategory` varchar(200) DEFAULT NULL,
  `expenditureAmount` decimal(20,2) NOT NULL,
  `expenditureDate` date NOT NULL,
  `expenditureReceipt` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`expId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `expense_category` */

DROP TABLE IF EXISTS `expense_category`;

CREATE TABLE `expense_category` (
  `ecatId` int(12) NOT NULL AUTO_INCREMENT,
  `ecatName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `ecatActive` tinyint(4) NOT NULL DEFAULT '1',
  `ecatCode` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`ecatId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `nominal_accounts` */

DROP TABLE IF EXISTS `nominal_accounts`;

CREATE TABLE `nominal_accounts` (
  `nId` int(11) NOT NULL AUTO_INCREMENT,
  `nUId` varchar(20) NOT NULL,
  `nAccountName` text NOT NULL,
  `nAccountDescription` varchar(300) NOT NULL,
  `nActive` tinyint(4) NOT NULL,
  PRIMARY KEY (`nId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `produce_category` */

DROP TABLE IF EXISTS `produce_category`;

CREATE TABLE `produce_category` (
  `pcatId` int(12) NOT NULL AUTO_INCREMENT,
  `pcatName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `pcatActive` tinyint(4) NOT NULL DEFAULT '1',
  `pcatCode` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`pcatId`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `produce_stock_level` */

DROP TABLE IF EXISTS `produce_stock_level`;

CREATE TABLE `produce_stock_level` (
  `stId` int(11) NOT NULL AUTO_INCREMENT,
  `prodId` int(20) NOT NULL,
  `sQty` int(20) NOT NULL,
  `sLevel` int(20) NOT NULL,
  `sActive` tinyint(4) NOT NULL,
  PRIMARY KEY (`stId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `producelist` */

DROP TABLE IF EXISTS `producelist`;

CREATE TABLE `producelist` (
  `prodId` int(12) NOT NULL AUTO_INCREMENT,
  `prodName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `prodDescription` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `prodActive` tinyint(4) NOT NULL DEFAULT '1',
  `prodCategory` varchar(200) DEFAULT NULL,
  `prodPrice` decimal(20,2) DEFAULT NULL,
  `expirationDate` varchar(200) DEFAULT NULL,
  `prodQuantity` varchar(150) DEFAULT NULL,
  PRIMARY KEY (`prodId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

/*Table structure for table `sales` */

DROP TABLE IF EXISTS `sales`;

CREATE TABLE `sales` (
  `sId` int(11) NOT NULL AUTO_INCREMENT,
  `sUId` varchar(300) NOT NULL,
  `prodId` int(20) NOT NULL,
  `custId` int(20) NOT NULL,
  `sAmount` double NOT NULL,
  `sActive` tinyint(4) NOT NULL,
  `sLog` varchar(300) NOT NULL,
  PRIMARY KEY (`sId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `moduleType` int(11) NOT NULL,
  `moduleDescription` varchar(300) NOT NULL,
  `modActive` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `trial_balance` */

DROP TABLE IF EXISTS `trial_balance`;

CREATE TABLE `trial_balance` (
  `tId` int(11) NOT NULL AUTO_INCREMENT,
  `nId` int(20) NOT NULL,
  `tAmount` double NOT NULL,
  `balbrtforward` double NOT NULL,
  PRIMARY KEY (`tId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `uId` int(12) NOT NULL AUTO_INCREMENT,
  `userName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `password` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `permission` varchar(200) NOT NULL,
  `passphrase` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `firstName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `lastName` varchar(200) CHARACTER SET utf8mb4 NOT NULL,
  `dob` date NOT NULL,
  `uActive` tinyint(4) NOT NULL DEFAULT '1',
  `role` varchar(200) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phoneNumber` varchar(200) DEFAULT NULL,
  `fullName` varchar(255) DEFAULT NULL,
  `emailAddress` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`uId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
