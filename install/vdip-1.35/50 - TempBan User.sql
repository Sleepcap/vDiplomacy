ALTER TABLE `wD_Users` ADD `tempBan` int(10) unsigned;

UPDATE `wD_vDipMisc` SET `value` = '50' WHERE `name` = 'Version';
