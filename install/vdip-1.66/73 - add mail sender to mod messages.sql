ALTER TABLE `wD_ModForumMessages` ADD `fromMail` VARCHAR(90) CHARACTER SET utf8 COLLATE utf8_general_ci NULL AFTER `fromUserID`; 

ALTER TABLE `wD_ModForumMessages` CHANGE `fromUserID` `fromUserID` MEDIUMINT(8) UNSIGNED NULL; 

UPDATE `wD_vDipMisc` SET `value` = '73' WHERE `name` = 'Version';