ALTER TABLE `wD_Users` ADD `terrGrey` enum('all','selected','off') CHARACTER SET utf8 NOT NULL DEFAULT 'all';
ALTER TABLE `wD_Users` ADD `greyOut` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '50';
ALTER TABLE `wD_Users` ADD `scrollbars` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';
ALTER TABLE `wD_Users` CHANGE `pointNClick` `pointNClick` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 NOT NULL DEFAULT 'Yes';

UPDATE `wD_Users` SET `pointNClick` = 'Yes' WHERE `pointNClick` = 'No';

UPDATE `wD_vDipMisc` SET `value` = '38' WHERE `name` = 'Version';
