SET NAMES utf8mb4;

DROP TABLE IF EXISTS `counts`;
CREATE TABLE `counts` (
  `item_id` int(11) NOT NULL,
  `views` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  KEY `data_id` (`item_id`),
  CONSTRAINT `counts_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `data`;
CREATE TABLE `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `subcategory` varchar(50) NOT NULL,
  `intro` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(100) NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `gallery` varchar(500) NOT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lng` decimal(11,8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `devices`;
CREATE TABLE `devices` (
  `guid` varchar(38) NOT NULL,
  `cordova` varchar(10) NOT NULL,
  `model` varchar(20) NOT NULL,
  `platform` varchar(20) NOT NULL,
  `manufacturer` varchar(25) NOT NULL,
  `isvirtual` varchar(20) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`guid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `history`;
CREATE TABLE `history` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `guid` varchar(38) NOT NULL,
  `coords` point NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


DROP TABLE IF EXISTS `items`;
CREATE TABLE `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL,
  `subcategory` varchar(50) NOT NULL,
  `intro` varchar(500) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(100) NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `gallery` varchar(500) NOT NULL,
  `coords` point DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;