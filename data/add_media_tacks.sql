CREATE TABLE IF NOT EXISTS `media_tracks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_id` int(10) unsigned NOT NULL,
  `datecreated` timestamp NOT NULL,
  `source` VARCHAR(128) NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`media_id`) REFERENCES media(id),
  INDEX `media_tracks_datecreated` (`datecreated`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `media_tracks_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_tracks_id` int(10) unsigned NOT NULL,
  `datecreated` timestamp NOT NULL,
  `kind` ENUM('caption','subtitle','description') NOT NULL,
  `format` ENUM('srt', 'vtt') NOT NULL,
  `language` varchar(128) NOT NULL,
  `file_contents` MEDIUMTEXT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`media_tracks_id`) REFERENCES media_tracks(id),
  INDEX `media_tracks_datecreated` (`datecreated`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

ALTER TABLE `media` ADD `meida_tracks_id` INT NULL;
ALTER TABLE `media`
ADD FOREIGN KEY (meida_tracks_id)
REFERENCES media_tracks(id);

CREATE TABLE IF NOT EXISTS `rev_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_tracks_id` int(10) unsigned NOT NULL,
  `uid` varchar(50) NOT NULL,
  `datecreated` timestamp NOT NULL,
  `costobjectnumber` VARCHAR(10) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`media_tracks_id`) REFERENCES media_tracks(id),
  FOREIGN KEY (`uid`) REFERENCES users(uid),
  INDEX `rev_orders_datecreated` (`datecreated`),
  INDEX `rev_orders_cost_object` (`costobjectnumber`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;


