ALTER TABLE `wD_ModForumMessages` 
CHANGE `status` `status` SET('New','Open','Resolved','Bugs','Sticky','Deleted') 
CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'New'; 

UPDATE `wD_vDipMisc` SET `value` = '72' WHERE `name` = 'Version';
