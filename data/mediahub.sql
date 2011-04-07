-- phpMyAdmin SQL Dump
-- version 3.3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 10, 2011 at 03:25 PM
-- Server version: 5.1.48
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mediahub`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_id` int(10) unsigned NOT NULL,
  `uid` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `comment` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

CREATE TABLE IF NOT EXISTS `feeds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` mediumtext,
  `image_data` longblob,
  `image_type` varchar(120) DEFAULT NULL,
  `image_size` int(11) DEFAULT NULL,
  `image_title` varchar(150) DEFAULT NULL,
  `image_description` mediumtext,
  `itunes_uri` varchar(255) DEFAULT NULL COMMENT 'URI to this feed within iTunes or iTunes U',
  `boxee` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Flag indicating whether feed is included in Boxee',
  `uidcreated` varchar(50) NOT NULL,
  `datecreated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feed_has_media`
--

CREATE TABLE IF NOT EXISTS `feed_has_media` (
  `feed_id` int(10) unsigned NOT NULL,
  `media_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`feed_id`,`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feed_has_nselement`
--

CREATE TABLE IF NOT EXISTS `feed_has_nselement` (
  `feed_id` int(10) unsigned NOT NULL,
  `xmlns` varchar(10) NOT NULL,
  `element` varchar(100) NOT NULL,
  `attributes` longtext,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`feed_id`,`xmlns`,`element`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feed_has_subscription`
--

CREATE TABLE IF NOT EXISTS `feed_has_subscription` (
  `feed_id` int(10) unsigned NOT NULL,
  `subscription_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`feed_id`,`subscription_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `length` bigint(20) unsigned DEFAULT '0',
  `type` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `datecreated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dateupdated` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `media_has_nselement`
--

CREATE TABLE IF NOT EXISTS `media_has_nselement` (
  `media_id` int(10) unsigned NOT NULL,
  `xmlns` varchar(10) NOT NULL,
  `element` varchar(100) NOT NULL,
  `attributes` longtext NOT NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY (`media_id`,`xmlns`,`element`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE IF NOT EXISTS `subscriptions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `filter_class` int(255) NOT NULL,
  `filter_option` varchar(255) NOT NULL,
  `datecreated` datetime NOT NULL,
  `uidcreated` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` varchar(50) NOT NULL,
  `datecreated` datetime NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_permission`
--

CREATE TABLE IF NOT EXISTS `user_has_permission` (
  `user_uid` varchar(50) NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `feed_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_uid`,`permission_id`,`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
