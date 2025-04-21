CREATE TABLE `wD_ForceReply` (
  `id` int(10) unsigned NOT NULL,
  `toUserID` mediumint(8) unsigned DEFAULT 0,
  `forceReply` enum('Yes','No','Done') CHARACTER SET utf8 NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`,`toUserID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO wD_ForceReply (id, toUserID, forceReply )
	SELECT m.id, m.toUserID, m.forceReply
	FROM wD_ModForumMessages m
WHERE m.toUserID != 0;

ALTER TABLE `wD_ModForumMessages` DROP `toUserID`;	
ALTER TABLE `wD_ModForumMessages` DROP `forceReply`;	

UPDATE `wD_vDipMisc` SET `value` = '37' WHERE `name` = 'Version';
