ALTER TABLE `wD_Users` ADD `gamesPlayed` int(11) NOT NULL default '0';
UPDATE `wD_vDipMisc` SET `value` = '39' WHERE `name` = 'Version';
