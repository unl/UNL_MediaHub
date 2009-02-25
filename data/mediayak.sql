-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 25, 2009 at 08:46 AM
-- Server version: 5.0.67
-- PHP Version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `mediayak`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `media_id` int(10) unsigned NOT NULL,
  `uid` varchar(50) NOT NULL,
  `comment` mediumtext NOT NULL,
  `datecreated` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feeds`
--

CREATE TABLE IF NOT EXISTS `feeds` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `description` mediumtext,
  `uidcreated` varchar(50) NOT NULL,
  `datecreated` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feed_has_media`
--

CREATE TABLE IF NOT EXISTS `feed_has_media` (
  `feed_id` int(10) unsigned NOT NULL,
  `media_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`feed_id`,`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `feed_has_nselement`
--

CREATE TABLE IF NOT EXISTS `feed_has_nselement` (
  `feed_id` int(10) unsigned NOT NULL,
  `xmlns` varchar(10) NOT NULL,
  `element` varchar(100) NOT NULL,
  `attributes` varchar(255) default NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY  (`feed_id`,`xmlns`,`element`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE IF NOT EXISTS `media` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `url` varchar(255) NOT NULL,
  `length` bigint(20) unsigned default '0',
  `type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` mediumtext NOT NULL,
  `author` varchar(255) default NULL,
  `datecreated` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media_has_nselement`
--

CREATE TABLE IF NOT EXISTS `media_has_nselement` (
  `media_id` int(10) unsigned NOT NULL,
  `xmlns` varchar(10) NOT NULL,
  `element` varchar(100) NOT NULL,
  `attributes` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  PRIMARY KEY  (`media_id`,`xmlns`,`element`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `uid` varchar(50) NOT NULL,
  `datecreated` datetime NOT NULL,
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_has_permission`
--

CREATE TABLE IF NOT EXISTS `user_has_permission` (
  `user_uid` varchar(50) NOT NULL,
  `permission_id` int(10) unsigned NOT NULL,
  `feed_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`user_uid`,`permission_id`,`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
