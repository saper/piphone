ALTER TABLE `campaign` ADD `longname` TEXT NOT NULL DEFAULT '' AFTER `name` ;
ALTER TABLE `campaign` ADD `longname-fr` TEXT NOT NULL DEFAULT '' AFTER `longname` ;
