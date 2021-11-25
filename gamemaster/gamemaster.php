<?php
/*
    Copyright (C) 2004-2010 Kestas J. Kuliukas

	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * A class which performs utility functions for the gamemaster script, such as
 * adding/removing/fetching items from the process-queue, and doing various maintenance
 * tasks.
 *
 * @package GameMaster
 */
class libGameMaster
{
	/**
	 * Removes temporary (keep='No') notices that are more than a week old.
	 */
	public static function clearStaleNotices()
	{
		global $DB;

		$DB->sql_put("DELETE FROM wD_Notices
			WHERE keep='No' AND timeSent < (".time()."-7*24*60*60)");
	}

	/**
	 * Update the session table; for users which have expired from it enter their data into the
	 * access log and add their hits to the global hits counter.
	 */
	static public function updateSessionTable()
	{
		global $DB, $Misc;

		$DB->sql_put("BEGIN");

		$tabl = $DB->sql_tabl("SELECT userID FROM wD_Sessions
						WHERE UNIX_TIMESTAMP(lastRequest) < UNIX_TIMESTAMP(CURRENT_TIMESTAMP) - 10 * 60");

		$userIDs = array();

		while ( list($userID) = $DB->tabl_row($tabl) )
			$userIDs[] = $userID;

		if ( count($userIDs) > 0 )
		{
			$userIDs = implode(', ', $userIDs);

			// Update the hit counter
			list($newhits) = $DB->sql_row("SELECT SUM(hits) FROM wD_Sessions WHERE userID IN (".$userIDs.")");

			$Misc->Hits += $newhits;
			$Misc->write();

			// Save access logs, to detect multi-accounters
			$DB->sql_put("INSERT INTO wD_AccessLog
				( userID, lastRequest, hits, ip, userAgent, cookieCode )
				SELECT userID, lastRequest, hits, ip, userAgent, cookieCode
				FROM wD_Sessions
				WHERE userID IN (".$userIDs.")");

			$DB->sql_put("DELETE FROM wD_Sessions WHERE userID IN (".$userIDs.")");

			$DB->sql_put("UPDATE wD_Users
					SET timeLastSessionEnded = ".time().", lastMessageIDViewed = (SELECT MAX(f.id) FROM wD_ForumMessages f)
					, lastModMessageIDViewed = (SELECT MAX(fm.id) FROM wD_ModForumMessages fm)
					WHERE id IN (".$userIDs.")");

		}

		$DB->sql_put("COMMIT");
	}

	/**
	 * Recalculates for all users that have logged in the last 30 days.
	 * 
	 * This is a relatively DB intensive query since it needs to check over 2 tables for all the users it includes, but it does 
	 * ensure the way the numbers are calculated can be tracked back to the specific games involved.
	 * 
	 * This could be optimized by making it recalculate only for users who are members / who were members in games that have just been
	 * processed.
	 * 
	 * @param $recalculateAll If true don't filter on active users, but recalculate for all users, which takes longer
	 */
	static public function updateReliabilityRating($recalculateAll = false)
	{
		global $DB, $Misc;

		$year = time() - 31536000;
		$lastMonth = time() - 2419200;
		$lastWeek = time() - 604800;

		$RELIABILITY_QUERY = "
		UPDATE wD_Users u 
		set u.reliabilityRating = greatest(0, 
		(100 *(1 - ((SELECT COUNT(1) FROM wD_MissedTurns t  WHERE t.userID = u.id AND t.modExcused = 0 and t.turnDateTime > ".$year.") / greatest(1,u.yearlyPhaseCount))))
		-(6*(SELECT COUNT(1) FROM wD_MissedTurns t  WHERE t.userID = u.id AND t.liveGame = 0 AND t.modExcused = 0 and t.samePeriodExcused = 0 and t.systemExcused = 0 and t.turnDateTime > ".$lastMonth."))
		-(6*(SELECT COUNT(1) FROM wD_MissedTurns t  WHERE t.userID = u.id AND t.liveGame = 1 AND t.modExcused = 0 and t.samePeriodExcused = 0 and t.systemExcused = 0 and t.turnDateTime > ".$lastWeek."))
		-(5*(SELECT COUNT(1) FROM wD_MissedTurns t  WHERE t.userID = u.id AND t.liveGame = 1 AND t.modExcused = 0 and t.samePeriodExcused = 0 and t.systemExcused = 0 and t.turnDateTime > ".$lastMonth."))
		-(5*(SELECT COUNT(1) FROM wD_MissedTurns t  WHERE t.userID = u.id AND t.liveGame = 0 AND t.modExcused = 0 and t.samePeriodExcused = 0 and t.systemExcused = 0 and t.turnDateTime > ".$year.")))";

		// VDips way to handle the Reliability rating. Maybe needs to adjusted or replaced by the original webDip querry in the future...
		$RELIABILITY_QUERY = "
		UPDATE wD_Users u 
		SET u.cdCount = (SELECT COUNT(1) FROM wD_CivilDisorders c WHERE c.userID = u.id AND c.forcedByMod=0),
			u.nmrCount = (SELECT COUNT(1) FROM wD_NMRs n WHERE n.userID = u.id AND n.ignoreNMR=0),
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
			)/2 , 3)/10000)";
			
		// Calculates the RR for members. 
		$DB->sql_put($RELIABILITY_QUERY. ($recalculateAll ? "" : " WHERE u.timeLastSessionEnded+(30*86400) > ".$Misc->LastProcessTime));
		
		$DB->sql_put("COMMIT");
	}
}

?>
