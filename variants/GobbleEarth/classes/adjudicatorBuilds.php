<?php
defined('IN_CODE') or die('This script can not be run by itself.');

class GobbleEarthVariant_adjudicatorBuilds extends adjudicatorBuilds
{
	function adjudicate()
	{
	
		global $DB, $Game;

		// Assign TerrID's to the names in $Variant->homeSCs
		$terrIDByName = array();
		$tabl = $DB->sql_tabl("SELECT id, name FROM wD_Territories WHERE supply='Yes' AND mapID=".$Game->Variant->mapID);
		while(list($id, $name) = $DB->tabl_row($tabl))
			$terrIDByName[$name]=$id;
		
		foreach($Game->Variant->homeSCs as $countryName => $HomeSCs)
		{
			$countryID = $Game->Variant->countryID($countryName);

			$terrIDs = array();
			foreach($HomeSCs as $terrName)
				$terrIDs[] = $terrIDByName[$terrName];

			// How many colonial builds?	
			list($cB)=$DB->sql_row("SELECT COUNT(*) FROM wD_Moves WHERE gameID=".$Game->id." AND countryID=".$countryID." AND toTerrID not IN (".implode(",",$terrIDs).")");
			if ($cB > 0)
			{
				list($sB)=$DB->sql_row("SELECT COUNT(*) FROM wD_MovesArchive WHERE gameID=".$Game->id." AND countryID=".$countryID." AND turn=".($Game->turn - 2)." AND type='Wait'");
				if ($cB > $sB)
					$DB->sql_put(
						"UPDATE wD_Moves
						SET success = 'No'
						WHERE gameID=".$Game->id." AND countryID=".$countryID." AND toTerrID not IN (".implode(",",$terrIDs).") AND moveType != 'Destroy'"
					);
			}
		}
 		
		// Now adjuate the rest.
		parent::adjudicate();
	}

}