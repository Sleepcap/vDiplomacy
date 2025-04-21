ALTER TABLE `wD_Games` ADD `blockVotes` set('Draw','Pause','Cancel','Extend','Concede') CHARACTER SET utf8 NOT NULL;
ALTER TABLE `wD_Backup_Games` ADD `blockVotes` set('Draw','Pause','Cancel','Extend','Concede') CHARACTER SET utf8 NOT NULL;
UPDATE `wD_vDipMisc` SET `value` = '48' WHERE `name` = 'Version';
