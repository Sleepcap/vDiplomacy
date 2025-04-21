ALTER TABLE `wD_Games` ADD `chooseYourCountry` enum('Yes','No') NOT NULL DEFAULT 'No';
ALTER TABLE `wD_Backup_Games` ADD `chooseYourCountry` enum('Yes','No') NOT NULL DEFAULT 'No';
UPDATE `wD_vDipMisc` SET `value` = '43' WHERE `name` = 'Version';
