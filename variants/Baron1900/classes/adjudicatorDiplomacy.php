<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of adjudicatorDiplomacy
 *
 * @author tobi
 */
defined('IN_CODE') or die('This script can not be run by itself.');

class Baron1900Variant_adjudicatorDiplomacy extends adjudicatorDiplomacy
{
	function adjudicate()
	{
		global $DB, $Game;
		
		/*
		 * (2), (4)
		 * Catch all the units in Egypt/Hejaz, Mid-Atlantic Ocean that need 
		 * special treatment before the adjucation (Moves around Africa)
		 */
		
		//Mid-Atlantic Ocean -> Egypt/Hejaz
		$row = $DB->sql_row( "SELECT m.id FROM wD_Moves m
								WHERE m.moveType='Move' 
								AND m.terrID=".Baron1900Variant::$MidAtlanticOceanID." 
								AND m.toTerrID IN (".Baron1900Variant::$egyptID.",".Baron1900Variant::$hejazID.") 
								AND m.gameID=".$GLOBALS['GAMEID']);

		if($row){
			$Game->Variant->moveAroundAfrica[0] = $row[0];
			$Game->Variant->maoUnitAttacking = $row[0]; //we need to know, if we got a mao-attacking unit for the resolution of a new paradox
		}
		
		//Egypt/Hejaz -> Mid-Atlantic Ocean
		$tabl = $DB->sql_tabl( "SELECT m.id FROM wD_Moves m
								WHERE m.moveType='Move' 
								AND m.terrID IN (".Baron1900Variant::$egyptID.",".Baron1900Variant::$hejazID.") 
								AND m.toTerrID=".Baron1900Variant::$MidAtlanticOceanID." 
								AND m.gameID=".$GLOBALS['GAMEID']);
		
		while( list($id) = $DB->tabl_row($tabl) )
			$Game->Variant->moveAroundAfrica[]=$id;
		
		/*
		 * (5)
		 * Catch F MAO if MAO is convoying to Egypt/Hejaz. If that is the case, catch all 
		 * armies moving via Convoy to Egypt/Hejaz.
		 * Armies moving to Hejaz will attack with half strength, armies to 
		 * Egypt only with half strength, if there is no chain through 
		 * Mediternean.
		 */
		$rowMAOegypt = $DB->sql_row( "SELECT m.id FROM wD_Moves m
								WHERE m.moveType='Convoy' 
								AND m.terrID=".Baron1900Variant::$MidAtlanticOceanID." 
								AND m.toTerrID=".Baron1900Variant::$egyptID." 
								AND m.gameID=".$GLOBALS['GAMEID']);
		
		if($rowMAOegypt){
			
			$rowToEgypt = $DB->sql_row( "SELECT m.id FROM wD_Moves m
								WHERE m.moveType='Move' 
								AND m.toTerrID=".Baron1900Variant::$egyptID." 
								AND m.terrID=
									(SELECT c.fromTerrID FROM wD_Moves c
									WHERE c.id=".$rowMAOegypt[0].")
								AND m.viaConvoy='Yes'
								AND m.gameID=".$GLOBALS['GAMEID']);
			
			if($rowToEgypt){
				$Game->Variant->MidAtlanticConvoying = $rowMAOegypt[0];
				$Game->Variant->ConvoyMoveToEgypt = $rowToEgypt[0];
			}
			
		}else{
			/*
			 * There is no need to store the ID of MAO unit, if we have a convoy 
			 * to Hejaz, since the only path to Hejaz is around the tip of South
			 * Africa.
			 */
			
			$rowToHejaz = $DB->sql_row( "SELECT m.id FROM wD_Moves m
								WHERE m.moveType='Move' 
								AND m.toTerrID=".Baron1900Variant::$hejazID." 
								AND m.terrID=
									(SELECT c.fromTerrID FROM wD_Moves c
									WHERE c.moveType='Convoy' 
									AND c.terrID=".Baron1900Variant::$MidAtlanticOceanID." 
									AND c.toTerrID=".Baron1900Variant::$hejazID." 
									AND c.gameID=".$GLOBALS['GAMEID'].")
								AND m.viaConvoy='Yes'
								AND m.gameID=".$GLOBALS['GAMEID']);
				
			if($rowToHejaz){
				$Game->Variant->ConvoyMoveToHejaz = $rowToHejaz[0];
			}
			
		}

		/*
		 * (3) 
		 * Remove invalid supports around South Africa
		 */
		$DB->sql_put(
					"UPDATE wD_Moves s
					SET s.moveType = 'Hold'
					WHERE (s.moveType = 'Support hold' OR s.moveType = 'Support move')
					AND (s.terrID=".Baron1900Variant::$MidAtlanticOceanID." 
								AND s.toTerrID IN (".Baron1900Variant::$egyptID.",".Baron1900Variant::$hejazID.")
						OR s.toTerrID=".Baron1900Variant::$MidAtlanticOceanID." 
								AND s.terrID IN (".Baron1900Variant::$egyptID.",".Baron1900Variant::$hejazID."))
					AND s.gameID = ".$GLOBALS['GAMEID']);
		
		return parent::adjudicate();
	}
}

