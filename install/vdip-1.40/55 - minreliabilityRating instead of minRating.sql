UPDATE wD_Games SET minimumReliabilityRating = minRating;
UPDATE wD_Backup_Games SET minimumReliabilityRating = minRating;

ALTER TABLE `wD_Games` DROP COLUMN `minRating`;
ALTER TABLE `wD_Backup_Games` DROP COLUMN `minRating`;

UPDATE `wD_vDipMisc` SET `value` = '55' WHERE `name` = 'Version'
