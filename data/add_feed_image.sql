ALTER TABLE `feeds` ADD `image_data` BLOB NULL AFTER `description` ,
ADD `image_type` VARCHAR( 120 ) NULL AFTER `image_data` ,
ADD `image_size` INT NULL AFTER `image_type` ,
ADD `image_title` VARCHAR( 150 ) NULL AFTER `image_size` ,
ADD `image_description` MEDIUMTEXT NULL AFTER `image_title` ;