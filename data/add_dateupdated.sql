 ALTER TABLE `media` CHANGE `datecreated` `datecreated` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;
 ALTER TABLE `media` ADD `dateupdated` TIMESTAMP NULL AFTER `datecreated` ;