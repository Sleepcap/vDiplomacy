/* vDip 1 */
ALTER TABLE `wD_Games` ADD `maxTurns` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `maxTurns` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';

/* vDip 2 */
ALTER TABLE `wD_Users` ADD `missedMoves` int(11) NOT NULL default '0';
ALTER TABLE `wD_Users` ADD `phasesPlayed` int(11) NOT NULL default '0';

/* vDip 3 */
CREATE TABLE `wD_ModForumMessages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `toID` int(10) unsigned NOT NULL,
  `fromUserID` mediumint(8) unsigned NOT NULL,
  `timeSent` int(10) unsigned NOT NULL,
  `message` text NOT NULL,
  `subject` varchar(100) NOT NULL,
  `type` enum('ThreadStart','ThreadReply') NOT NULL,
  `replies` smallint(5) unsigned NOT NULL,
  `latestReplySent` int(10) unsigned NOT NULL,
  `silenceID` INT UNSIGNED NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `latest` (`timeSent`),
  KEY `threadReplies` (`type`,`toID`,`timeSent`),
  KEY `latestReplySent` (`latestReplySent`),
  KEY `profileLinks` (`type`,`fromUserID`,`timeSent`),
  KEY `type` (`type`,`latestReplySent`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

/* vDip 4 */
ALTER TABLE `wD_Users` ADD `gamesLeft` int(11) NOT NULL default '0';

/* vDip 5 */
ALTER TABLE `wD_Games` ADD `minRating` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Games` ADD `minPhases` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Games` ADD `maxLeft` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '99';
ALTER TABLE `wD_Backup_Games` ADD `minRating` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `minPhases` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `maxLeft` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '99';

/* vDip 6 */
ALTER TABLE `wD_Games` ADD `targetSCs` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `targetSCs` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';

/* vDip 7 */
ALTER TABLE `wD_Members` MODIFY `votes` set('Draw','Pause','Cancel','Extend');

/* vDip 8 */
ALTER TABLE `wD_Users` ADD `leftBalanced` int(11) NOT NULL default '0';

/* vDip 9 */
ALTER TABLE `wD_Members` MODIFY `votes` set('Draw','Pause','Cancel','Extend','Concede');

/* vDip 10 */
CREATE TABLE `wD_CountrySwitch` (
	`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
	`gameID` INT UNSIGNED NULL ,
	`fromID` INT UNSIGNED NULL ,
	`toID` INT UNSIGNED NULL ,
	`status` set('Send','Active','Rejected','Canceled','Returned','ClaimedBack') CHARACTER SET utf8 NOT NULL,
	PRIMARY KEY ( `id` )
) ENGINE = MyISAM DEFAULT CHARSET=latin1;

/* vDip 11 */
ALTER TABLE `wD_Users` ADD `lastModMessageIDViewed` int(10) unsigned NOT NULL DEFAULT '0';

/* vDip 12 */
ALTER TABLE `wD_Games` ADD `specialCDcount` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `wD_Backup_Games` ADD `specialCDcount` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '1';

/* vDip 13 */
CREATE TABLE `wD_BlockUser` (
	`userID` mediumint(8) unsigned NOT NULL,
	`blockUserID` mediumint(8) unsigned NOT NULL,
	`timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`userID`,`blockUserID`)
) ENGINE=MyISAM;

/* vDip 14 */
ALTER TABLE `wD_Users` ADD `RLGroup` mediumint(8) unsigned default '0';
ALTER TABLE `wD_ModeratorNotes` MODIFY `linkIDType` ENUM( 'Game', 'User', 'RLGroup' );

/* vDip 15 */
ALTER TABLE `wD_Users` CHANGE `RLGroup` `rlGroup` MEDIUMINT( 8 );
ALTER TABLE `wD_ModeratorNotes` CHANGE `linkIDType` `linkIDType` ENUM( 'Game', 'User', 'rlGroup' ) ;

/* vDip 16 */
ALTER TABLE `wD_Games` ADD `rlPolicy` enum('None','Strict','Friends') CHARACTER SET utf8 NOT NULL DEFAULT 'None';
ALTER TABLE `wD_Backup_Games` ADD `rlPolicy` enum('None','Strict','Friends') CHARACTER SET utf8 NOT NULL DEFAULT 'None';

/* vDip 17 */
ALTER TABLE `wD_Users` CHANGE `rlGroup` `rlGroup` MEDIUMINT( 8 ) NOT NULL default '0';

/* vDip 18 */
ALTER TABLE `wD_ForumMessages` ADD `anon` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';

/* vDip 19 */
ALTER TABLE `wD_Users` MODIFY `notifications` set('PrivateMessage','GameMessage','Unfinalized','GameUpdate','ModForum');

/* vDip 20 */
ALTER TABLE `wD_Members`        ADD `chessTime` smallint(5) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Members` ADD `chessTime` smallint(5) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `wD_Games`          ADD `chessTime` smallint(5) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `wD_Games`          ADD `lastProcessed` int(10) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games`   ADD `chessTime` smallint(5) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games`   ADD `lastProcessed` int(10) unsigned NOT NULL DEFAULT '0';

/* vDip 21 */
ALTER TABLE `wD_Users` CHANGE `type` `type` SET( 'Banned', 'Guest', 'System', 'User', 'Moderator', 'Admin', 'Donator', 'DonatorBronze', 'DonatorSilver', 'DonatorGold', 'DonatorPlatinum', 'DevBronze', 'DevSilver', 'DevGold', 'ForumModerator' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'User';

/* vDip 22 */
ALTER TABLE `wD_ModForumMessages` ADD `adminReply` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';
ALTER TABLE `wD_ModForumMessages` ADD `status` enum('New','Open','Resolved') CHARACTER SET utf8 NOT NULL DEFAULT 'New';

/* vDip 23 */
ALTER TABLE `wD_Users` ADD `showCountryNames` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';

/* vDip 24 */
ALTER TABLE `wD_Users` ADD `colorCorrect` enum('Off','Protanope','Deuteranope','Tritanope') CHARACTER SET utf8 NOT NULL DEFAULT 'Off';

/* vDip 25 */
ALTER TABLE `wD_Users` ADD `showCountryNamesMap` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';

/* vDip 26 */
CREATE TABLE `wD_AccessLogAdvanced` (
  `userID` mediumint(8) unsigned NOT NULL,
  `request` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ip` int(10) unsigned NOT NULL,
  `action` enum('LogOn','LogOff','Board') CHARACTER SET utf8 NOT NULL DEFAULT 'LogOn',
  `memberID` mediumint(8) unsigned NOT NULL,
  KEY `userID` (`userID`),
  KEY `ip` (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/* vDip 27 */
ALTER TABLE `wD_Games` ADD `adminLock` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';
ALTER TABLE `wD_Backup_Games` ADD `adminLock` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';

/* vDip 28 */
CREATE TABLE `wD_Ratings` (
  `userID` mediumint(8) unsigned NOT NULL,
  `ratingType` enum('VDip') CHARACTER SET utf8 NOT NULL DEFAULT 'VDip',
  `gameID` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rating` smallint(5) unsigned NOT NULL DEFAULT '1500',
  `fixed` enum('variantID', 'potType', 'pressType') CHARACTER SET utf8 DEFAULT NULL,
  KEY `userID` (`userID`),
  KEY `ratingType` (`ratingType`),
  KEY `gameID` (`gameID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/* vDip 29 */
ALTER TABLE `wD_Members` CHANGE `votes` `votes` set('Draw','Pause','Cancel','Extend','Concede') NOT NULL DEFAULT '';

/* vDip 30 */
ALTER TABLE `wD_Users` MODIFY `notifications` set('PrivateMessage','GameMessage','Unfinalized','GameUpdate','ModForum','CountrySwitch');

/* vDip 31 */
ALTER TABLE `wD_Users` ADD `sortOrder` enum('BuildOrder','TerrName','NorthSouth','EastWest') CHARACTER SET utf8 NOT NULL DEFAULT 'BuildOrder';
ALTER TABLE `wD_Users` ADD `unitOrder` enum('Mixed','AF','FA') CHARACTER SET utf8 NOT NULL DEFAULT 'Mixed';

/* vDip 32 */
CREATE TABLE `wD_vDipMisc` (
  `name` enum('Version') NOT NULL,
  `value` int(10) unsigned NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO `wD_vDipMisc` VALUES ('Version',32);

/* vDip 33 */
ALTER TABLE `wD_Users` ADD `pointNClick` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';

/* vDip 34 */
ALTER TABLE `wD_Users` CHANGE `type` `type` SET( 'Banned', 'Guest', 'System', 'User', 'Moderator', 'Admin', 'Donator', 'DonatorBronze', 'DonatorSilver', 'DonatorGold', 'DonatorPlatinum', 'DevBronze', 'DevSilver', 'DevGold', 'ForumModerator', 'ModAlert' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'User';

/* vDip 35 */
ALTER TABLE `wD_ModForumMessages` MODIFY `status` enum('New','Open','Resolved','Bugs','Sticky') CHARACTER SET utf8 NOT NULL DEFAULT 'New';;

/* vDip 36 */
ALTER TABLE `wD_ModForumMessages` ADD `toUserID` mediumint(8) unsigned DEFAULT 0;
ALTER TABLE `wD_ModForumMessages` ADD  `forceReply` enum('Yes','No','Done') CHARACTER SET utf8 NOT NULL DEFAULT 'No';
ALTER TABLE `wD_Users` MODIFY `notifications` set('PrivateMessage','GameMessage','Unfinalized','GameUpdate','ModForum','CountrySwitch','ForceModMessage');

/* vDip 37 */
CREATE TABLE `wD_ForceReply` (
  `id` int(10) unsigned NOT NULL,
  `toUserID` mediumint(8) unsigned DEFAULT 0,
  `forceReply` enum('Yes','No','Done') CHARACTER SET utf8 NOT NULL DEFAULT 'No',
  PRIMARY KEY (`id`,`toUserID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `wD_ModForumMessages` DROP `toUserID`;	
ALTER TABLE `wD_ModForumMessages` DROP `forceReply`;	

/* vDip 38 */
ALTER TABLE `wD_Users` ADD `terrGrey` enum('all','selected','off') CHARACTER SET utf8 NOT NULL DEFAULT 'all';
ALTER TABLE `wD_Users` ADD `greyOut` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '50';
ALTER TABLE `wD_Users` ADD `scrollbars` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';
ALTER TABLE `wD_Users` CHANGE `pointNClick` `pointNClick` ENUM( 'Yes', 'No' ) CHARACTER SET utf8 NOT NULL DEFAULT 'Yes';

/* vDip 39 */
ALTER TABLE `wD_Users` ADD `gamesPlayed` int(11) NOT NULL default '0';

/* vDip 40 */
ALTER TABLE `wD_Games` ADD `minNoCD` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Games` ADD `minNoNMR` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `minNoCD` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `minNoNMR` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '0';

/* vDip 41 */
ALTER TABLE `wD_Users`  ADD `CDtakeover` MEDIUMINT UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `wD_Users` DROP COLUMN `leftBalanced`;
ALTER TABLE `wD_Games` DROP COLUMN `maxLeft`;
ALTER TABLE `wD_Backup_Games` DROP COLUMN `maxLeft`;

/* vDip 42 */
ALTER TABLE `wD_ModForumMessages` ADD `assigned` mediumint(8) unsigned DEFAULT 0;

/* vDip 43 */
ALTER TABLE `wD_Games` ADD `chooseYourCountry` enum('Yes','No') NOT NULL DEFAULT 'No';
ALTER TABLE `wD_Backup_Games` ADD `chooseYourCountry` enum('Yes','No') NOT NULL DEFAULT 'No';

/* 1900 Variant 00 */
ALTER TABLE `wD_DATC` CHANGE COLUMN `variantID` `variantID` SMALLINT(6) UNSIGNED NOT NULL ;
ALTER TABLE `wD_Backup_Games` CHANGE COLUMN `variantID` `variantID` SMALLINT(6) UNSIGNED NOT NULL ;
ALTER TABLE `wD_Borders` CHANGE COLUMN `mapID` `mapID` SMALLINT(6) UNSIGNED NOT NULL ;
ALTER TABLE `wD_CoastalBorders` CHANGE COLUMN `mapID` `mapID` SMALLINT(6) UNSIGNED NOT NULL ;
ALTER TABLE `wD_Games` CHANGE COLUMN `variantID` `variantID` SMALLINT(6) UNSIGNED NOT NULL ;
ALTER TABLE `wD_Territories` CHANGE COLUMN `mapID` `mapID` SMALLINT(6) UNSIGNED NOT NULL ;
ALTER TABLE `wD_UnitDestroyIndex` CHANGE COLUMN `mapID` `mapID` SMALLINT(6) UNSIGNED NOT NULL ;
ALTER TABLE `wD_VariantData` CHANGE COLUMN `variantID` `variantID` SMALLINT(6) UNSIGNED NOT NULL ;

/* 1900 Variant 01 */
ALTER TABLE `wD_Territories` ADD COLUMN `buildEligibilityFlags` INT(7) UNSIGNED ZEROFILL NOT NULL DEFAULT 0 AFTER `coastParentID`;

/* 1900 Variant 02 */
ALTER TABLE `wD_Territories` CHANGE COLUMN `type` `type` ENUM('Sea', 'Land', 'Coast', 'Strait') NOT NULL ;

/* 1900 Variant 03 */
Insert into `wD_DATC` (`testID`, `variantID`,`testName`,`testDesc`,`status`)
Values
(1900,1900,'9.A.1900','TEST CASE, CONVOY TO SELF ILLEGAL','NotPassed'),
(1901,1900,'9.B.1900','TEST CASE, CONVOYING TO OWN AREA WITH A LOOP','Invalid'),
(1902,1900,'9.C.1900','TEST CASE, CONVOY DISRUPTED BY ARMY','NotPassed'),
(1903,1900,'9.D.1900','TEST CASE, CONVOY DISRUPTED BY CONVOYING ARMY','NotPassed'),
(1904,1900,'9.E.1900','TEST CASE, TWO DISRUPTED CONVOYS PARADOX','Invalid'),
(1905,1900,'9.F.1900','TEST CASE, DISRUPTED CONVOY SUPPORT PARADOX WITH NO RESOLUTION','NotPassed'),
(1906,1900,'9.G.1900','TEST CASE, DISRUPTED CONVOY SUPPORT PARADOX WITH TWO RESOLUTIONS','NotPassed'),
(1907,1900,'X.A0.1900','TEST CASE, SUPPORT CAN NOT BE CUT BY SIMPLE HALF-STRENGTH BOUNCE','NotPassed'),
(1908,1900,'X.A1.1900','TEST CASE, SUPPORT CAN NOT BE CUT BY SUPPORTED HALF-STRENGTH BOUNCE','NotPassed'),
(1909,1900,'X.B.1900','TEST CASE, SUPPORT CAN BE CUT BY DISLODGING MOVE OVER HALF-STRENGTH BORDER','NotPassed'),
(1910,1900,'X.C.1900','TEST CASE, MOVE OVER HALF-STRENGTH BORDER CAN NOT BOUNCE WITH NORMAL MOVE','NotPassed'),
(1911,1900,'X.D0.1900','TEST CASE, TWO MOVES OVER HALF-STRENGTH BORDER CAN BOUNCE','NotPassed'),
(1912,1900,'X.D1.1900','TEST CASE, TWO MOVES OVER HALF-STRENGTH BORDER AND SUPPORTED MOVE SUCCEEDS','NotPassed'),
(1913,1900,'X.D2.1900','TEST CASE, TWO SUPPORTED MOVES OVER HALF-STRENGTH BORDER CAN BOUNCE','NotPassed'),
(1914,1900,'X.E0.1900','SUPPORT TO MOVE CAN NOT BE GIVEN OVER HALF-STRENGTH BORDER','NotPassed'),
(1915,1900,'X.E1.1900','SUPPORT TO HOLD CAN NOT BE GIVEN OVER HALF-STRENGTH BORDER','NotPassed'),
(1916,1900,'X.F.1900','TEST CASE, SUPPORT PARADOX','Invalid'),
(1917,1900,'X.G.1900','TEST CASE, ALMOST PARADOX','Invalid'),
(1918,1900,'X.H.1900','TEST CASE, CIRCULAR MOVEMENT WITH HALF-STRENGTH BORDER','NotPassed'),
(1919,1900,'X.I.1900','TEST CASE, CIRCULAR MOVEMENT WITH BOUNCE','NotPassed'),
(1920,1900,'X.J0.1900','TEST CASE, CIRCULAR MOVEMENT CAN NOT BE DISRUPTED BY HALF-STRENGTH BORDER','NotPassed'),
(1921,1900,'X.J1.1900','TEST CASE, CIRCULAR MOVEMENT CAN BE DISRUPTED BY HALF-STRENGTH WITH SUPPORT','NotPassed'),
(1922,1900,'X.J2.1900','TEST CASE, CIRCULAR MOVEMENT WITH SUPPORT TRUMPS HALF-STRENGTH WITH SUPPORT','NotPassed'),
(1923,1900,'X.K.1900','TEST CASE, FULL-STRENGTH RETREAT IS HIGHER PRIORITY THAN HALF-STRENGTH RETREAT','Invalid'),
(1924,1900,'X.L.1900','TEST CASE, HALF-STRENGTH BORDER IS PROPERTY OF BORDER NOT OF SECTOR','NotPassed'),
(1925,1900,'X.M.1900','TEST CASE, USING CONVOY INSTEAD OF HALF-STRENGTH BORDER','Invalid'),
(1926,1900,'X.N.1900','TEST CASE, USING CONVOY INSTEAD OF HALF-STRENGTH BORDER CUTS SUPPORT','Invalid'),
(1927,1900,'X.O.1900','TEST CASE, SUPPORT ON ATTACK ON OWN FLEET OVER HALF-STRENGTH BORDER DOES NOT CUT SUPPORT','NotPassed'),
(1928,1900,'X.P.1900','TEST CASE, NO CONVOYS ACROSS TWO HALF-STRENGTH SEGMENTS','NotPassed'),
(1929,1900,'X.Q.1900','TEST CASE, CONVOY IS HALF-STRENGTH WHEN LAST SEGMENT IS HALF-STRENGTH','NotPassed'),
(1930,1900,'X.R.1900','TEST CASE, CONVOY IS FULL-STRENGTH WHEN ONE NON-LAST SEGMENT IS HALF-STRENGTH','NotPassed'),
(1931,1900,'X.S0.1900','TEST CASE, PREFER FULL-STRENGTH CONVOY TO HALF-STRENGTH CONVOY','NotPassed'),
(1932,1900,'X.S1.1900','TEST CASE, USE HALF-STRENGTH CONVOY WHEN FULL-STRENGTH CONVOY DISRUPTED','NotPassed'),
(1933,1900,'X.S2.1900','TEST CASE, UNOPPOSED HALF-STRENGTH CONVOY WHEN FULL-STRENGTH CONVOY DISRUPTED','NotPassed');

Insert into `wD_DATCOrders` (`testID`,`countryID`,`unitType`,`terrID`,`moveType`,`toTerrID`,`fromTerrID`,`viaConvoy`,`criteria`,`legal`)
Values
(1900,2,'Fleet',25,'Convoy',25,41,'No','Hold','No'),
(1900,6,'Fleet',40,'Hold',NULL,NULL,'No','Hold','Yes'),
(1900,6,'Army',41,'Move',25,NULL,'Yes','Hold','Yes'),
(1902,4,'Army',3,'Move',50,NULL,'Yes','Hold','Yes'),
(1902,4,'Fleet',71,'Convoy',50,3,'No','Hold','Yes'),
(1902,4,'Fleet',25,'Convoy',50,3,'No','Dislodged','Yes'),
(1902,4,'Fleet',40,'Convoy',50,3,'No','Hold','Yes'),
(1902,6,'Army',58,'Move',25,NULL,'No','Success','Yes'),
(1902,6,'Army',41,'Support move',25,58,'No','Hold','Yes'),
(1903,4,'Army',58,'Move',41,NULL,'Yes','Hold','Yes'),
(1903,4,'Fleet',25,'Convoy',41,58,'No','Dislodged','Yes'),
(1903,6,'Army',3,'Move',25,NULL,'Yes','Success','Yes'),
(1903,6,'Fleet',71,'Convoy',25,3,'No','Hold','Yes'),
(1903,6,'Fleet',40,'Support move',25,3,'No','Hold','Yes'),
(1905,4,'Army',41,'Move',58,NULL,'Yes','Hold','Yes'),
(1905,4,'Fleet',25,'Convoy',58,41,'No','Hold','Yes'),
(1905,4,'Army',50,'Support move',58,41,'No','Hold','Yes'),
(1905,6,'Army',3,'Move',25,NULL,'Yes','Hold','Yes'),
(1905,6,'Fleet',71,'Convoy',25,3,'No','Dislodged','Yes'),
(1905,6,'Fleet',40,'Support move',25,3,'No','Hold','Yes'),
(1905,2,'Fleet',59,'Support move',71,28,'No','Hold','Yes'),
(1905,2,'Fleet',28,'Move',71,NULL,'No','Success','Yes'),
(1906,4,'Army',41,'Move',58,NULL,'Yes','Hold','Yes'),
(1906,4,'Fleet',25,'Convoy',58,41,'No','Hold','Yes'),
(1906,4,'Army',50,'Support move',58,41,'No','Hold','Yes'),
(1906,4,'Fleet',28,'Move',71,NULL,'No','Hold','Yes'),
(1906,4,'Fleet',67,'Support move',71,28,'No','Hold','Yes'),
(1906,6,'Army',3,'Move',25,NULL,'Yes','Hold','Yes'),
(1906,6,'Fleet',71,'Convoy',25,3,'No','Hold','Yes'),
(1906,6,'Fleet',59,'Support hold',71,NULL,'No','Hold','Yes'),
(1906,6,'Fleet',40,'Support move',25,3,'No','Hold','Yes'),
(1907,5,'Fleet',40,'Move',20,NULL,'No','Hold','Yes'),
(1907,5,'Fleet',19,'Hold',NULL,NULL,'No','Dislodged','Yes'),
(1907,7,'Fleet',20,'Support move',19,32,'No','Hold','Yes'),
(1907,7,'Fleet',32,'Move',19,NULL,'No','Success','Yes'),
(1908,5,'Fleet',40,'Move',20,NULL,'No','Hold','Yes'),
(1908,5,'Army',29,'Support move',20,40,'No','Hold','Yes'),
(1908,2,'Army',20,'Support move',47,6,'No','Hold','Yes'),
(1908,2,'Fleet',19,'Support hold',20,NULL,'No','Hold','Yes'),
(1908,2,'Army',6,'Move',47,NULL,'No','Success','Yes'),
(1908,7,'Army',47,'Hold',NULL,NULL,'No','Dislodged','Yes'),
(1909,5,'Fleet',40,'Move',20,NULL,'No','Success','Yes'),
(1909,5,'Army',29,'Support move',20,40,'No','Hold','Yes'),
(1909,2,'Army',20,'Support move',47,6,'No','Dislodged','Yes'),
(1909,2,'Army',6,'Move',47,NULL,'No','Hold','Yes'),
(1909,7,'Army',47,'Hold',NULL,NULL,'No','Hold','Yes'),
(1910,5,'Fleet',40,'Move',20,NULL,'No','Hold','Yes'),
(1910,7,'Army',47,'Move',20,NULL,'No','Success','Yes'),
(1911,2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
(1911,2,'Fleet',29,'Move',40,NULL,'No','Hold','Yes'),
(1912,2,'Fleet',20,'Move',40,NULL,'No','Success','Yes'),
(1912,2,'Fleet',29,'Move',40,NULL,'No','Hold','Yes'),
(1912,2,'Fleet',41,'Support move',40,20,'No','Hold','Yes'),
(1913,2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
(1913,2,'Fleet',50,'Support move',40,29,'No','Hold','Yes'),
(1913,2,'Fleet',29,'Move',40,NULL,'No','Hold','Yes'),
(1913,2,'Fleet',41,'Support move',40,20,'No','Hold','Yes'),
(1914,2,'Fleet',40,'Support move',20,29,'No','Hold','No'),
(1914,2,'Army',29,'Move',20,NULL,'No','Hold','Yes'),
(1914,7,'Army',20,'Hold',NULL,NULL,'No','Hold','Yes'),
(1915,2,'Army',29,'Move',20,NULL,'No','Success','Yes'),
(1915,2,'Army',47,'Support move',20,29,'No','Hold','Yes'),
(1915,7,'Army',20,'Hold',NULL,NULL,'No','Dislodged','Yes'),
(1915,7,'Fleet',40,'Support hold',20,NULL,'No','Hold','No'),
(1918,2,'Army',29,'Move',20,NULL,'No','Success','Yes'),
(1918,2,'Fleet',20,'Move',40,NULL,'No','Success','Yes'),
(1918,3,'Fleet',40,'Move',29,NULL,'No','Success','Yes'),
(1919,2,'Army',29,'Move',20,NULL,'No','Hold','Yes'),
(1919,2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
(1919,3,'Fleet',40,'Move',29,NULL,'No','Hold','Yes'),
(1919,7,'Army',47,'Move',20,NULL,'No','Hold','Yes'),
(1920,2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
(1920,3,'Fleet',50,'Move',40,NULL,'No','Success','Yes'),
(1920,3,'Fleet',40,'Move',61,NULL,'No','Success','Yes'),
(1920,3,'Fleet',61,'Move',50,NULL,'No','Success','Yes'),
(1921,2,'Fleet',20,'Move',40,NULL,'No','Success','Yes'),
(1921,2,'Fleet',25,'Support move',40,20,'No','Hold','Yes'),
(1921,3,'Fleet',50,'Move',40,NULL,'No','Hold','Yes'),
(1921,3,'Fleet',40,'Move',61,NULL,'No','Dislodged','Yes'),
(1921,3,'Fleet',61,'Move',50,NULL,'No','Hold','Yes'),
(1922,2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
(1922,2,'Fleet',25,'Support move',40,20,'No','Hold','Yes'),
(1922,3,'Fleet',50,'Move',40,NULL,'No','Success','Yes'),
(1922,3,'Fleet',40,'Move',61,NULL,'No','Success','Yes'),
(1922,3,'Fleet',61,'Move',50,NULL,'No','Success','Yes'),
(1922,3,'Fleet',75,'Support move',40,50,'No','Hold','Yes'),
(1924,2,'Army',17,'Move',20,NULL,'No','Hold','Yes'),
(1924,7,'Army',29,'Move',20,NULL,'No','Hold','Yes'),
(1927,2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
(1927,2,'Fleet',25,'Hold',NULL,NULL,'No','Hold','Yes'),
(1927,3,'Fleet',41,'Support move',40,20,'No','Hold','Yes'),
(1927,3,'Fleet',40,'Support hold',25,NULL,'No','Hold','Yes'),
(1927,5,'Fleet',71,'Move',25,NULL,'No','Hold','Yes'),
(1927,5,'Fleet',59,'Support move',25,71,'No','Hold','Yes'),
(1928,2,'Fleet',40,'Convoy',29,20,'No','Hold','Yes'),
(1928,2,'Army',20,'Move',29,NULL,'Yes','Hold','Yes'),
(1928,7,'Army',6,'Move',29,NULL,'No','Success','Yes'),
(1929,2,'Fleet',40,'Convoy',20,33,'No','Hold','Yes'),
(1929,2,'Army',33,'Move',20,NULL,'Yes','Hold','Yes'),
(1929,7,'Army',47,'Move',20,NULL,'No','Success','Yes'),
(1930,2,'Fleet',40,'Convoy',50,20,'No','Hold','Yes'),
(1930,2,'Army',20,'Move',50,NULL,'Yes','Hold','Yes'),
(1930,3,'Army',58,'Move',50,NULL,'No','Hold','Yes'),
(1931,2,'Army',64,'Move',20,NULL,'Yes','Hold','Yes'),
(1931,7,'Army',47,'Move',20,NULL,'No','Hold','Yes'),
(1931,5,'Fleet',19,'Convoy',20,64,'No','Hold','Yes'),
(1931,5,'Fleet',32,'Convoy',20,64,'No','Hold','Yes'),
(1931,5,'Fleet',67,'Convoy',20,64,'No','Hold','Yes'),
(1931,5,'Fleet',71,'Convoy',20,64,'No','Hold','Yes'),
(1931,5,'Fleet',25,'Convoy',20,64,'No','Hold','Yes'),
(1931,5,'Fleet',40,'Convoy',20,64,'No','Hold','Yes'),
(1932,2,'Army',58,'Move',20,NULL,'Yes','Hold','Yes'),
(1932,7,'Army',47,'Move',20,NULL,'No','Success','Yes'),
(1932,5,'Fleet',19,'Convoy',20,58,'No','Hold','Yes'),
(1932,5,'Fleet',32,'Convoy',20,58,'No','Dislodged','Yes'),
(1932,5,'Fleet',67,'Convoy',20,58,'No','Hold','Yes'),
(1932,5,'Fleet',71,'Convoy',20,58,'No','Hold','Yes'),
(1932,5,'Fleet',40,'Convoy',20,58,'No','Hold','Yes'),
(1932,5,'Fleet',25,'Convoy',20,58,'No','Hold','Yes'),
(1932,1,'Fleet',2,'Move',32,NULL,'No','Success','Yes'),
(1932,1,'Fleet',1,'Support move',32,2,'No','Hold','Yes'),
(1933,2,'Army',58,'Move',20,NULL,'Yes','Success','Yes'),
(1933,5,'Fleet',19,'Convoy',20,58,'No','Hold','Yes'),
(1933,5,'Fleet',32,'Convoy',20,58,'No','Dislodged','Yes'),
(1933,5,'Fleet',67,'Convoy',20,58,'No','Hold','Yes'),
(1933,5,'Fleet',71,'Convoy',20,58,'No','Hold','Yes'),
(1933,5,'Fleet',40,'Convoy',20,58,'No','Hold','Yes'),
(1933,5,'Fleet',25,'Convoy',20,58,'No','Hold','Yes'),
(1933,1,'Fleet',2,'Move',32,NULL,'No','Success','Yes'),
(1933,1,'Fleet',1,'Support move',32,2,'No','Hold','Yes');

/* vDip 44 */
ALTER TABLE `wD_ForceReply` ADD `status` enum('Sent','Read','Replied') CHARACTER SET utf8 NOT NULL DEFAULT 'Sent';
ALTER TABLE `wD_ForceReply` ADD `readIP`  int(10) unsigned NOT NULL;
ALTER TABLE `wD_ForceReply` ADD `readTime` int(10) unsigned NOT NULL;
ALTER TABLE `wD_ForceReply` ADD `replyIP` int(10) unsigned NOT NULL;

/* vDip 45 */
ALTER TABLE `wD_Games` ADD `description` text NOT NULL;
ALTER TABLE `wD_Backup_Games` ADD `description` text NOT NULL;
ALTER TABLE `wD_Users` ADD `directorLicense` enum('Yes','No') DEFAULT NULL;

/* vDip 46 */
ALTER TABLE `wD_Games` ADD `noProcess` set('1', '2', '3', '4', '5', '6', '0' )  NOT NULL DEFAULT '';
ALTER TABLE `wD_Backup_Games` ADD `noProcess` set('1', '2', '3', '4', '5', '6', '0') NOT NULL DEFAULT '';

/* vDip 47 */
ALTER TABLE `wD_Games` ADD `fixStart` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';
ALTER TABLE `wD_Backup_Games` ADD `fixStart` enum('Yes','No') CHARACTER SET utf8 NOT NULL DEFAULT 'No';

/* vDip 48 */
ALTER TABLE `wD_Games` ADD `blockVotes` set('Draw','Pause','Cancel','Extend','Concede') CHARACTER SET utf8 NOT NULL;
ALTER TABLE `wD_Backup_Games` ADD `blockVotes` set('Draw','Pause','Cancel','Extend','Concede') CHARACTER SET utf8 NOT NULL;

/* vDip 49 */
ALTER TABLE `wD_MovesArchive` ADD `orderID` int(10) unsigned NOT NULL FIRST;;
ALTER TABLE `wD_Backup_MovesArchive` ADD `orderID` int(10) unsigned NOT NULL FIRST;;

/* vDip 50 */

/* vDip 51 */
ALTER TABLE `wD_Members` ADD `ccMatch` tinyint(3) unsigned NOT NULL;
ALTER TABLE `wD_Members` ADD `ipMatch` tinyint(3) unsigned NOT NULL;
ALTER TABLE `wD_Backup_Members` ADD `ccMatch` tinyint(3) unsigned NOT NULL;
ALTER TABLE `wD_Backup_Members` ADD `ipMatch` tinyint(3) unsigned NOT NULL;

/* vDip 52 */
ALTER TABLE wD_Users ADD vpoints mediumint(8) unsigned NOT NULL DEFAULT '1000';

/* vDip 53 */
ALTER TABLE wD_Users ADD integrityBalance mediumint(8) unsigned NOT NULL DEFAULT '0';

/* vDip 54 */
ALTER TABLE `wD_Games` DROP COLUMN `minNoCD`;
ALTER TABLE `wD_Games` DROP COLUMN `minNoNMR`;
ALTER TABLE `wD_Backup_Games` DROP COLUMN `minNoCD`;
ALTER TABLE `wD_Backup_Games` DROP COLUMN `minNoNMR`;

/* vDip 55 */
ALTER TABLE `wD_Games` DROP COLUMN `minRating`;
ALTER TABLE `wD_Backup_Games` DROP COLUMN `minRating`;

/* vDip 56 */
ALTER TABLE wD_NMRs ADD COLUMN ignoreNMR BOOLEAN DEFAULT 0;

/* vDip 57 */
ALTER TABLE `wD_Games` ADD `potModifier` tinyint(3) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `wD_Backup_Games` ADD `potModifier` tinyint(3) unsigned NOT NULL DEFAULT '0';

/* vDip 58 */
ALTER TABLE wD_Users ADD `cssStyle` enum('vDip','webDip') DEFAULT 'vDip';

/* vDip 59 */
/* vDip 60 */

/* vDip 61 */
ALTER TABLE `wD_Users` ADD `rssID` varchar(30) NOT NULL DEFAULT '';

/* vDip 62 */
ALTER TABLE wD_Users ADD `buttonWidth` enum('auto','small','large') NOT NULL DEFAULT 'auto';

/* vDip 63 */
ALTER TABLE wD_CountrySwitch ADD `hasWatched` enum('Yes','No') NOT NULL DEFAULT 'No';

/* vDip 64 */
ALTER TABLE `wD_DATC` CHANGE `testID` `testID` INT NOT NULL;
ALTER TABLE `wD_DATCOrders` CHANGE `testID` `testID` INT NOT NULL;
DELETE FROM `wD_DATC` WHERE `testID` >= 1900 AND `testID` <= 1933;
DELETE FROM `wD_DATCOrders` WHERE `testID` >= 1900 AND `testID` <= 1933;

/* vDip 65 */
ALTER TABLE `wD_Games` ADD `delayDeadlineMaxTurn` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '1';
ALTER TABLE `wD_Backup_Games` ADD `delayDeadlineMaxTurn` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '1';

/* vDip 66 */

/* vDip 67 */
ALTER TABLE `wD_Games` ADD `regainExcusesDuration` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '99';
ALTER TABLE `wD_Backup_Games` ADD `regainExcusesDuration` SMALLINT( 5 ) UNSIGNED NOT NULL DEFAULT '99';

/* vDip 68 */
ALTER TABLE `wD_CivilDisorders` 
	ADD `takenByUserID` MEDIUMINT(8) UNSIGNED NULL DEFAULT NULL , 
	ADD `takenAtTime` INT(10) UNSIGNED NULL DEFAULT NULL;

/* vDip 69 */

/* vDip 70 */
ALTER TABLE `wD_vDipMisc` CHANGE COLUMN `name` `name` ENUM('Version', 'LastMail') NOT NULL ;
INSERT INTO `wD_vDipMisc` VALUES ('LastMail',0);

/* vDip 71 */
ALTER TABLE `wD_Users`
CHANGE `type` `type` SET(
	'Banned', 'Guest', 'System', 'User', 'Moderator',
	'Admin', 'Donator', 'DonatorBronze', 'DonatorSilver',
	'DonatorGold', 'DonatorPlatinum', 'DevBronze', 'DevSilver', 'DevGold', 'ForumModerator', 'ModAlert', 'FtfTD',
	'DonatorAdamantium', 'DonatorService', 'DonatorOwner', 'Bot', 'SeniorMod'
) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'User';

UPDATE `wD_vDipMisc` SET `value` = '71' WHERE `name` = 'Version';
