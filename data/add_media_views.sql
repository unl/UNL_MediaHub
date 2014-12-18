CREATE TABLE IF NOT EXISTS `media_views` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_id` int(10) unsigned NOT NULL,
  `datecreated` timestamp NOT NULL,
  `ip_address` varchar(32) NULL,
  PRIMARY KEY (`id`),
  INDEX `media_views_media_id` (`media_id`),
  INDEX `media_views_datecreated` (`datecreated`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE `media` ADD `popular_play_count` INT NOT NULL DEFAULT 0 AFTER `play_count`;
