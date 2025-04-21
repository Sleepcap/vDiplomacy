ALTER TABLE `wD_Games` ADD `noProcess` set('1', '2', '3', '4', '5', '6', '0' )  NOT NULL DEFAULT '';
ALTER TABLE `wD_Backup_Games` ADD `noProcess` set('1', '2', '3', '4', '5', '6', '0') NOT NULL DEFAULT '';
UPDATE `wD_vDipMisc` SET `value` = '46' WHERE `name` = 'Version';

