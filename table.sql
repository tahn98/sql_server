DROP DATABASE IF EXISTS `sql_server`;
CREATE DATABASE `sql_server`; 
USE `sql_server`;

SET NAMES utf8 ;
SET character_set_client = utf8mb4 ;

DROP table if exists `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `book_id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  `author_name` varchar(50),
  `cover` varchar(255),
  PRIMARY KEY (`book_id`)
)ENGINE=INNODB;

DROP table if exists `chapter`;
CREATE TABLE IF NOT EXISTS `chapter` (
  `chapter_id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `book_id` tinyint(6) NOT NULL,
  `name` varchar(50) NOT NULL,
  `data` TEXT,
  `number_of_read` int(8),
  `upload_date` datetime NOT NULL,
  PRIMARY KEY (`chapter_id`)
)ENGINE=INNODB;

DROP table if exists `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`user_id`)
)ENGINE=INNODB;

DROP table if exists `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `comment_text` varchar(255) NOT NULL,
  `book_id` tinyint(6) NOT NULL,
  `user_id` tinyint(6) NOT NULL,
  `comment_date` DATETIME NOT NULL,
  PRIMARY KEY (`comment_id`)
)ENGINE=INNODB;

DROP table if exists `bookmark`;
CREATE TABLE IF NOT EXISTS `bookmark` (
  `bookmark_id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `user_id` tinyint(6) NOT NULL,
  `book_id` tinyint(6) NOT NULL,
  PRIMARY KEY (`bookmark_id`)
)ENGINE=INNODB;

DROP table if exists `rating`;
CREATE TABLE IF NOT EXISTS `rating` (
  `rating_id` tinyint(6) NOT NULL AUTO_INCREMENT,
  `rate` tinyint(3) NOT NULL,
  `user_id` tinyint(6) NOT NULL,
  `book_id` tinyint(6) NOT NULL,
  PRIMARY KEY (`rating_id`)
)ENGINE=INNODB;