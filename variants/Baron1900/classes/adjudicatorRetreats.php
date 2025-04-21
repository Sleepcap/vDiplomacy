<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author tobi
 */
class Baron1900Variant_adjudicatorRetreats extends adjudicatorRetreats {
	
	function adjudicate() {
		global $DB;
		
		/* 
		 * Rename all retreat orders around South Africa so they do not block
		 * regular retreats in the original adjudicator 
		 * (renamed as 'Move' since moveType is enum).
		 * As retreats have no terrIDs stored in wD_Moves, we have to use wD_units
		 * to get the correct terrIDs.
		 */
		$DB->sql_put(
				"UPDATE wD_Moves m INNER JOIN wD_Units u 
				ON (m.unitID = u.id AND m.gameID = u.gameID)
				SET m.moveType = 'Move'
				WHERE m.moveType = 'Retreat' 
				AND ((u.terrID = ".Baron1900Variant::$MidAtlanticOceanID."
						AND m.toTerrID IN (".Baron1900Variant::$egyptID.",".Baron1900Variant::$hejazID."))
					OR (u.terrID IN (".Baron1900Variant::$egyptID.",".Baron1900Variant::$hejazID.")
						AND m.toTerrID = ".Baron1900Variant::$MidAtlanticOceanID."))
				AND m.gameID = ".$GLOBALS['GAMEID']);
		
		parent::adjudicate();
		//all retreats around South Africa, that don't point towards territories,
		//that are blocked by multiple regular retreats anyway,
		//are set to success at this point
		
		//Now handle the retreats around South Africa:
		//Select all retreats around South Africa, that should retreat into a territory,
		//that is blocked by another retreat
		$tabl = $DB->sql_tabl(
				"SELECT specialRetreat.id
				FROM wD_Moves specialRetreat
				WHERE specialRetreat.moveType = 'Move'
				AND (SELECT COUNT(m.id) 
					FROM wD_Moves m
					WHERE (m.moveType = 'Retreat' OR m.moveType = 'Move')
					AND m.toTerrID = specialRetreat.toTerrID
					AND m.gameID = ".$GLOBALS['GAMEID'].") > 1
				AND specialRetreat.gameID = ".$GLOBALS['GAMEID']);
		
		//Reset all blocked retreats around South Africa to success = "No"
		$blockedRetreatIDs = array();
		while(list($id) = $DB->tabl_row($tabl)){
			$blockedRetreatIDs[] = $id;
		}
		
		$DB->sql_put(
				"UPDATE wD_Moves
				SET success = 'No'
				WHERE id IN ( '".implode("','", $blockedRetreatIDs)."' ) AND gameID = ".$GLOBALS['GAMEID']);
		
		//Now rename all retreats around South Africa again
		$DB->sql_put(
				"UPDATE wD_Moves SET moveType = 'Retreat'
				WHERE moveType = 'Move'
				AND gameID = ".$GLOBALS['GAMEID']);
	}
}
