/* Add a uuid field to media to help manage a unique folder structure that is relatively obscured */
ALTER TABLE `media` ADD `UUID` VARCHAR(128) NULL;


CREATE TABLE IF NOT EXISTS `transcoding_jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `media_id` int(10) unsigned NOT NULL,
  `uid` varchar(50) NULL,
  `datecreated` timestamp NOT NULL,
  `dateupdated` timestamp NULL,
  `job_type` ENUM('mp4', 'hls') NOT NULL,
  `job_id` varchar(256) NULL,
  `status` ENUM('SUBMITTED', 'WORKING', 'ERROR', 'FINISHED') NOT NULL DEFAULT 'SUBMITTED',
  `error_text` TEXT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`uid`) REFERENCES users(uid) ON DELETE SET NULL,
  FOREIGN KEY (`media_id`) REFERENCES media(id) ON DELETE CASCADE,
  INDEX `transcoding_jobs_datecreated` (`datecreated`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE utf8_unicode_ci;
