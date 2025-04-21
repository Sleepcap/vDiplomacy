ALTER TABLE `wD_ModForumMessages` ADD `assigned` mediumint(8) unsigned DEFAULT 0;
UPDATE `wD_vDipMisc` SET `value` = '42' WHERE `name` = 'Version';
