ALTER TABLE `wD_Games` ADD `delayDeadlineMaxTurn` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `wD_Backup_Games` ADD `delayDeadlineMaxTurn` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '1';

UPDATE wD_Games SET delayDeadlineMaxTurn = specialCDturn;
UPDATE wD_Backup_Games SET delayDeadlineMaxTurn = specialCDturn;

ALTER TABLE `wD_Games` DROP COLUMN `specialCDturn`;
ALTER TABLE `wD_Backup_Games` DROP COLUMN `specialCDturn`;

UPDATE `wD_vDipMisc` SET `value` = '65' WHERE `name` = 'Version'
