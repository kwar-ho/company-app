-- Adminer 4.3.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `api_keys`;
CREATE TABLE `api_keys` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `api_keys` (`id`, `user_id`, `key`, `created_timestamp`, `updated_timestamp`) VALUES
(1,	1,	'113xJ5851O01kyCZ',	'2020-09-27 12:23:27',	'0000-00-00 00:00:00');

DROP TABLE IF EXISTS `api_user`;
CREATE TABLE `api_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(200) CHARACTER SET sjis COLLATE sjis_bin DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `created_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_timestamp` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `api_user` (`id`, `username`, `password`, `status`, `created_timestamp`, `updated_timestamp`) VALUES
(1,	'demo_api_user',	'58191844e7491e2a0077b5bbb9d6653648dd1e53',	1,	'2020-09-27 12:21:10',	'2020-09-27 12:37:31');

DROP TABLE IF EXISTS `departments`;
CREATE TABLE `departments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dept_name` varchar(40) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_timestamp` timestamp NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dept_name` (`dept_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `departments` (`id`, `dept_name`, `is_active`, `is_deleted`, `created_timestamp`, `updated_timestamp`) VALUES
(1,	'Software',	1,	0,	'2020-09-27 13:32:42',	'0000-00-00 00:00:00'),
(2,	'Operation',	1,	0,	'2020-09-27 13:33:01',	'0000-00-00 00:00:00'),
(4,	'Sales',	1,	0,	'2020-09-27 13:33:17',	'0000-00-00 00:00:00'),
(6,	'test',	1,	0,	'2020-09-27 14:08:36',	'0000-00-00 00:00:00'),
(7,	'demo',	1,	1,	'2020-09-27 14:14:59',	'2020-09-27 14:15:11');

DROP TABLE IF EXISTS `employees`;
CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(20) DEFAULT NULL,
  `last_name` varchar(20) DEFAULT NULL,
  `gender` enum('M','F') DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `date_of_joining` date DEFAULT NULL,
  `department_id` int(11) DEFAULT NULL,
  `office_email` varchar(30) DEFAULT NULL,
  `primary_mobile_country_code` varchar(5) DEFAULT NULL,
  `primary_mobile` varchar(15) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `is_deleted` tinyint(1) DEFAULT '0',
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `office_email` (`office_email`),
  UNIQUE KEY `primary_mobile` (`primary_mobile`),
  KEY `department_id` (`department_id`),
  CONSTRAINT `employees_ibfk_3` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `employees` (`id`, `first_name`, `last_name`, `gender`, `date_of_birth`, `date_of_joining`, `department_id`, `office_email`, `primary_mobile_country_code`, `primary_mobile`, `is_active`, `is_deleted`, `created_timestamp`, `updated_timestamp`) VALUES
(1,	'John',	'Smith',	'M',	'1995-10-29',	NULL,	1,	'john@gmail.com',	NULL,	'9876784123',	1,	0,	'2020-09-27 13:39:47',	'2020-09-27 14:03:22'),
(4,	'test',	'Jones',	'M',	'1990-03-29',	NULL,	6,	'test@gmail.com',	NULL,	'9876784444',	1,	1,	'2020-09-27 14:08:53',	'2020-09-27 14:09:49');

DROP TABLE IF EXISTS `emp_contact_details`;
CREATE TABLE `emp_contact_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `emp_id` int(11) NOT NULL,
  `personal_email` varchar(30) DEFAULT NULL,
  `alternate_mobile_country_code` varchar(5) DEFAULT NULL,
  `alternate_mobile` varchar(15) DEFAULT NULL,
  `office_mobile` varchar(15) DEFAULT NULL,
  `emergency_person` varchar(20) DEFAULT NULL,
  `emergency_person_contact_no` varchar(15) DEFAULT NULL,
  `current_address` varchar(200) DEFAULT NULL,
  `current_address_state` varchar(15) DEFAULT NULL,
  `current_address_city` varchar(15) DEFAULT NULL,
  `current_address_zipcode` varchar(15) DEFAULT NULL,
  `permanent_address` varchar(200) DEFAULT NULL,
  `permanent_address_state` varchar(15) DEFAULT NULL,
  `permanent_address_city` varchar(15) DEFAULT NULL,
  `permanent_address_zipcode` varchar(15) DEFAULT NULL,
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_timestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `emp_id` (`emp_id`),
  CONSTRAINT `emp_contact_details_ibfk_3` FOREIGN KEY (`emp_id`) REFERENCES `employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `emp_contact_details` (`id`, `emp_id`, `personal_email`, `alternate_mobile_country_code`, `alternate_mobile`, `office_mobile`, `emergency_person`, `emergency_person_contact_no`, `current_address`, `current_address_state`, `current_address_city`, `current_address_zipcode`, `permanent_address`, `permanent_address_state`, `permanent_address_city`, `permanent_address_zipcode`, `created_timestamp`, `updated_timestamp`) VALUES
(1,	1,	'johnpersonal@gmail.com',	NULL,	NULL,	NULL,	NULL,	NULL,	'Andheri Chakala',	'Maharshtra',	'Mumbai',	'400001',	NULL,	NULL,	NULL,	NULL,	'2020-09-27 13:39:47',	'0000-00-00 00:00:00'),
(4,	4,	'test@gmail.com',	NULL,	NULL,	NULL,	NULL,	NULL,	'Sector 4 Vashi',	'Maharshtra',	'Mumbai',	'400022',	NULL,	NULL,	NULL,	NULL,	'2020-09-27 14:08:54',	'0000-00-00 00:00:00');

-- 2020-09-27 14:18:51
