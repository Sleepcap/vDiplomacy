ALTER TABLE `wD_Users` DROP COLUMN `forceDesktop`;

UPDATE `wD_vDipMisc` SET `value` = '60' WHERE `name` = 'Version';

