
ALTER TABLE `lists` ADD `enabled` TINYINT NOT NULL DEFAULT '1' AFTER `callduration` ,
ADD INDEX ( `enabled` )  ;
