ALTER TABLE `wD_MovesArchive` ADD `orderID` int(10) unsigned NOT NULL FIRST;;
ALTER TABLE `wD_Backup_MovesArchive` ADD `orderID` int(10) unsigned NOT NULL FIRST;;
UPDATE `wD_vDipMisc` SET `value` = '49' WHERE `name` = 'Version';
