ALTER TABLE `wD_ModForumMessages` ADD `toUserID` mediumint(8) unsigned DEFAULT 0;
ALTER TABLE `wD_ModForumMessages` ADD  `forceReply` enum('Yes','No','Done') CHARACTER SET utf8 NOT NULL DEFAULT 'No';
ALTER TABLE `wD_Users` MODIFY `notifications` set('PrivateMessage','GameMessage','Unfinalized','GameUpdate','ModForum','CountrySwitch','ForceModMessage');
UPDATE `wD_vDipMisc` SET `value` = '36' WHERE `name` = 'Version';
