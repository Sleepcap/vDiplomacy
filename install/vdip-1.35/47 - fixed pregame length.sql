ALTER TABLE `wD_Games` ADD `fixStart` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';
ALTER TABLE `wD_Backup_Games` ADD `fixStart` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';
UPDATE `wD_vDipMisc` SET `value` = '47' WHERE `name` = 'Version';
