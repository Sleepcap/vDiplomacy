ALTER TABLE `wD_ForceReply` ADD `status` enum('Sent','Read','Replied') CHARACTER SET utf8 NOT NULL DEFAULT 'Sent';
ALTER TABLE `wD_ForceReply` ADD `readIP`  int(10) unsigned NOT NULL;
ALTER TABLE `wD_ForceReply` ADD `readTime` int(10) unsigned NOT NULL;
ALTER TABLE `wD_ForceReply` ADD `replyIP` int(10) unsigned NOT NULL;

UPDATE wD_ForceReply SET status='Read' WHERE forceReply='Done';
UPDATE wD_ForceReply fr LEFT JOIN `wD_ModForumMessages` m ON (m.toID = fr.id && fr.toUserID=fromUserID) SET fr.status='Replied' WHERE fr.forceReply='Done';

UPDATE `wD_vDipMisc` SET `value` = '44' WHERE `name` = 'Version';
