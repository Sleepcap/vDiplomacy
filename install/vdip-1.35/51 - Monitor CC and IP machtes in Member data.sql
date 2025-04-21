ALTER TABLE `wD_Members` ADD `ccMatch` tinyint(3) unsigned NOT NULL;
ALTER TABLE `wD_Members` ADD `ipMatch` tinyint(3) unsigned NOT NULL;

ALTER TABLE `wD_Backup_Members` ADD `ccMatch` tinyint(3) unsigned NOT NULL;
ALTER TABLE `wD_Backup_Members` ADD `ipMatch` tinyint(3) unsigned NOT NULL;

UPDATE `wD_vDipMisc` SET `value` = '51' WHERE `name` = 'Version';
