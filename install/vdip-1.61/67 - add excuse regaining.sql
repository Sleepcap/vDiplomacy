ALTER TABLE `wD_Games` ADD `regainExcusesDuration` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '99';
ALTER TABLE `wD_Backup_Games` ADD `regainExcusesDuration` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '99';

UPDATE `wD_vDipMisc` SET `value` = '67' WHERE `name` = 'Version'
