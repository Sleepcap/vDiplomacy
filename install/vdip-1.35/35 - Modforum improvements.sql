ALTER TABLE `wD_ModForumMessages` MODIFY `status` enum('New','Open','Resolved','Bugs','Sticky') CHARACTER SET utf8 NOT NULL DEFAULT 'New';;
UPDATE `wD_vDipMisc` SET `value` = '35' WHERE `name` = 'Version';
