ALTER TABLE `media` ADD `duration` INT NOT NULL DEFAULT 0 COMMENT 'the duration of the media in milliseconds' AFTER `length`;
