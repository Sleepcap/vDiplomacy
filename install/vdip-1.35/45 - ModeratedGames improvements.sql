ALTER TABLE `wD_Games` ADD `description` text NOT NULL;
ALTER TABLE `wD_Backup_Games` ADD `description` text NOT NULL;
ALTER TABLE `wD_Users` ADD `directorLicense` enum('Yes','No') DEFAULT NULL;

UPDATE `wD_vDipMisc` SET `value` = '45' WHERE `name` = 'Version';
