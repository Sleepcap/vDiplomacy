ALTER TABLE `wD_Users` ADD `rssID` varchar(30) NOT NULL DEFAULT '';

UPDATE `wD_vDipMisc` SET `value` = '61' WHERE `name` = 'Version';
