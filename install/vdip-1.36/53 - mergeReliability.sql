/* First create a dummy table, so we can fill the NMR-table with dummy data.
 * Than create fake NMR data for all previous NMRs in missedMoves
 */ 
CREATE TABLE `DUMMY` (
  `id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MEMORY DEFAULT CHARSET=utf8;

DELIMITER $$
DROP PROCEDURE IF EXISTS FillEmpty$$
CREATE PROCEDURE FillEmpty()
	BEGIN
		DECLARE i INT;
		SET i = 1;
		WHILE i < 1000 DO
			INSERT INTO `DUMMY` (`id`) VALUES(i);
			SET i = i + 1;
		END WHILE;
	END$$
DELIMITER ;
	
call FillEmpty();

Insert Into wD_NMRs select 0, u.id, 0, 0, 0, 0 from wD_Users u, DUMMY d where d.id <= (cast(u.missedMoves as signed) - cast(u.nmrCount as signed) );

Drop Table DUMMY;

/* Add an integrityBalance for each user that can be edited by mods. */
ALTER TABLE wD_Users ADD integrityBalance mediumint(8) unsigned NOT NULL DEFAULT '0';

/* Delete last turn auto CDs from the logs */
DELETE FROM wD_CivilDisorders WHERE SCCount = 0;

/* Set live games on ignore */
UPDATE wD_CivilDisorders cd LEFT JOIN wD_Games g ON (cd.gameID = g.id) SET cd.forcedByMod = 1 WHERE g.phaseMinutes < 60 

/* Generate the new RR data from the available data.
 * cdCount, nmrCount, gameCount, cdTakenCount, reliabilityRating is generated from the NMR and CD tables
 * phaseCount is copied from the old phasesPlayed
 * integrityBalance is set to the old CDtakeover - new cdTakenCount, so the integrety does not change for the payers.
 */
UPDATE wD_Users u SET 
	u.cdCount = (SELECT COUNT(1) FROM wD_CivilDisorders c WHERE c.userID = u.id AND c.forcedByMod=0),
	u.nmrCount = (SELECT COUNT(1) FROM wD_NMRs n WHERE n.userID = u.id),
	u.phaseCount = u.phasesPlayed,
	u.gameCount = ( 
		SELECT (COUNT(1) + 
		(SELECT COUNT(*) FROM wD_Members m WHERE m.userID = u.id and ((select count(1) from wD_Members M1 where M1.gameID = m.gameID) > 2))) 
		FROM wD_CivilDisorders c 
		LEFT JOIN wD_Members m ON c.gameID = m.gameID AND c.userID = m.userID AND c.countryID = m.countryID 
		WHERE m.id IS NULL AND c.userID = u.id
	),
	u.cdTakenCount = (
		SELECT COUNT(1)
		FROM wD_Members ct
		INNER JOIN wD_CivilDisorders c ON c.gameID = ct.gameID AND c.countryID = ct.countryID AND NOT c.userID = ct.userID
		WHERE ct.userID = u.id AND c.turn = (
			SELECT MAX(sc.turn) 
			FROM wD_CivilDisorders sc 
			WHERE sc.gameID = c.gameID AND sc.countryID = c.countryID
		)
	),
	u.reliabilityRating = (POW( (
		(100 * ( 1.0 - ((cast(u.cdCount as signed) + u.deletedCDs) / (u.gameCount+1)) ))
		+  (100 * (1.0 -   ((u.nmrCount)/(u.phaseCount+1))))
	)/2 , 3)/10000),
	u.integrityBalance = GREATEST(cast(u.CDtakeover as signed) - cast(u.cdTakenCount as signed), 0);

UPDATE `wD_vDipMisc` SET `value` = '53' WHERE `name` = 'Version'
