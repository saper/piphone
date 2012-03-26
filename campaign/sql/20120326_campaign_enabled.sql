
ALTER TABLE `campaign` ADD `enabled` TINYINT NOT NULL DEFAULT '1' AFTER `datestop` ,
ADD INDEX ( `enabled` )  ;
