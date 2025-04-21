ALTER TABLE `wD_Games` ADD `potModifier` tinyint(3) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `potModifier` tinyint(3) unsigned NOT NULL DEFAULT '0';

UPDATE `wD_vDipMisc` SET `value` = '57' WHERE `name` = 'Version'
