ALTER TABLE `wD_CivilDisorders` 
	ADD `takenByUserID` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL , 
	ADD `takenAtTime` INT(10) UNSIGNED NULL DEFAULT NULL;

UPDATE `wD_vDipMisc` SET `value` = '68' WHERE `name` = 'Version'
