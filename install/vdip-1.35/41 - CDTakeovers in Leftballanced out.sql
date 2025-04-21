ALTER TABLE `wD_Users`  ADD `CDtakeover` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
UPDATE wD_Users SET CDtakeover = gamesLeft;
ALTER TABLE `wD_Users` DROP COLUMN `leftBalanced`;
ALTER TABLE `wD_Games` DROP COLUMN `maxLeft`;
ALTER TABLE `wD_Backup_Games` DROP COLUMN `maxLeft`;

UPDATE `wD_vDipMisc` SET `value` = '41' WHERE `name` = 'Version';
