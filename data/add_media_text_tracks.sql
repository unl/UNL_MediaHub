/** Bring old tables up to snuff **/
ALTER TABLE users CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE feed_has_media CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE feed_has_nselement CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE feeds CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE media CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE media_has_nselement CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE subscriptions CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE user_has_permission CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;
ALTER TABLE permissions CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci;

ALTER TABLE users ENGINE = INNODB;
ALTER TABLE feed_has_media ENGINE = INNODB;
ALTER TABLE feed_has_nselement ENGINE = INNODB;
ALTER TABLE feeds ENGINE = INNODB;
ALTER TABLE media ENGINE = INNODB;
ALTER TABLE media_has_nselement ENGINE = INNODB;
ALTER TABLE subscriptions ENGINE = INNODB;
ALTER TABLE user_has_permission ENGINE = INNODB;
ALTER TABLE permissions ENGINE = INNODB;

/** Add new tables/structure **/
CREATE TABLE IF NOT EXISTS `media_text_tracks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_id` int(10) unsigned NOT NULL,
  `datecreated` timestamp NOT NULL,
  `source` ENUM('amara', 'rev') NULL,
  `revision_comment` MEDIUMTEXT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`media_id`) REFERENCES media(id),
  INDEX `media_text_tracks_datecreated` (`datecreated`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;

CREATE TABLE IF NOT EXISTS `media_text_tracks_files` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_text_tracks_id` int(10) unsigned NOT NULL,
  `datecreated` timestamp NOT NULL,
  `kind` ENUM('caption','subtitle','description') NOT NULL,
  `format` ENUM('srt', 'vtt') NOT NULL,
  `language` varchar(128) NOT NULL,
  `file_contents` MEDIUMTEXT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`media_text_tracks_id`) REFERENCES media_text_tracks(id),
  INDEX `media_text_tracks_datecreated` (`datecreated`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;

ALTER TABLE `media` ADD `media_text_tracks_id` int(10) unsigned NULL;
ALTER TABLE `media`
ADD FOREIGN KEY (media_text_tracks_id)
REFERENCES media_text_tracks(id);

CREATE TABLE IF NOT EXISTS `rev_orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_text_tracks_id` int(10) unsigned NULL,
  `media_id` int(10) unsigned NOT NULL,
  `uid` varchar(50) NOT NULL,
  `datecreated` timestamp NOT NULL,
  `dateupdated` timestamp NULL,
  `costobjectnumber` VARCHAR(20) NOT NULL,
  `rev_order_number` varchar(256) NULL,
  `media_duration` varchar(256) null,
  `estimate` varchar(256) null,
  `status` VARCHAR(128) NOT NULL,
  `error_text` TEXT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`media_text_tracks_id`) REFERENCES media_text_tracks(id),
  FOREIGN KEY (`uid`) REFERENCES users(uid),
  FOREIGN KEY (`media_id`) REFERENCES media(id),
  INDEX `rev_orders_datecreated` (`datecreated`),
  INDEX `rev_orders_cost_object` (`costobjectnumber`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;


