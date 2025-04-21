ALTER TABLE `wD_vDipMisc` CHANGE COLUMN `name` `name` ENUM('Version', 'LastMail') NOT NULL ;

INSERT INTO `wD_vDipMisc` VALUES ('LastMail',0);
UPDATE `wD_vDipMisc` SET `value` = '70' WHERE `name` = 'Version';
