ALTER TABLE `wD_Games` DROP COLUMN `minNoCD`;
ALTER TABLE `wD_Games` DROP COLUMN `minNoNMR`;
ALTER TABLE `wD_Backup_Games` DROP COLUMN `minNoCD`;
ALTER TABLE `wD_Backup_Games` DROP COLUMN `minNoNMR`;

UPDATE `wD_vDipMisc` SET `value` = '54' WHERE `name` = 'Version'
