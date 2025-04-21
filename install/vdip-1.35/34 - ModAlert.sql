ALTER TABLE `wD_Users` CHANGE `type` `type` SET( 'Banned', 'Guest', 'System', 'User', 'Moderator', 'Admin', 'Donator', 'DonatorBronze', 'DonatorSilver', 'DonatorGold', 'DonatorPlatinum', 'DevBronze', 'DevSilver', 'DevGold', 'ForumModerator', 'ModAlert' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'User';
UPDATE `wD_vDipMisc` SET `value` = '34' WHERE `name` = 'Version';
