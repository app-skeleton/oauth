-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.24-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4217
-- Date/time:                    2012-12-14 14:54:55
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table oauth_identities
DROP TABLE IF EXISTS `oauth_identities`;
CREATE TABLE IF NOT EXISTS `oauth_identities` (
  `identity_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` mediumint(8) unsigned DEFAULT NULL,
  `oauth_id` varchar(64) DEFAULT NULL,
  `oauth_provider` enum('facebook','google') DEFAULT NULL,
  PRIMARY KEY (`identity_id`),
  UNIQUE KEY `oauth_id_oauth_provider` (`oauth_id`,`oauth_provider`),
  KEY `FK_oauth__user_id` (`user_id`),
  CONSTRAINT `FK_oauth__user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Dumping data for table oauth_identities: ~0 rows (approximately)
DELETE FROM `oauth_identities`;
/*!40000 ALTER TABLE `oauth_identities` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_identities` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
