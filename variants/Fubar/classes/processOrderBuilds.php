<?php

class FubarVariant_processOrderBuilds extends processOrderBuilds {

	// You can set default builds here, so the game is not screwed if someone misses his build
	protected $countryUnits = array(
		'Fatflap'             => array('Squirrel'=>'Fleet','Turtle'=>'Army','Lizard'=>'Army','Zebra'=>'Army','Rat'=>'Army','Chicken'=>'Army','Monkey'=>'Army','Cow'=>'Army','Lion'=>'Army','Fish'=>'Army'),
		'Howdoileavethisgame' => array('Sheep'=>'Army','Bat'=>'Army'),	
		'timmy1999'           => array('Kangaroo'=>'Army','Snake'=>'Army'),
		'Sh1tn00b'            => array('Pig'=>'Army','Eagle'=>'Army'),
		'oMgYoUrAsLuT'        => array('Ostrich'=>'Army','Platypus'=>'Army'),
		'multi_152'           => array('Meerkat'=>'Army','Elephant'=>'Army')
	);

	public function create()
	{
		global $DB, $Game;
		if ($Game->turn == 0) {
			// Custom start
			$terrIDByName = array();
			$tabl = $DB->sql_tabl("SELECT id, name FROM wD_Territories WHERE mapID=".$Game->Variant->mapID);
			while(list($id, $name) = $DB->tabl_row($tabl))
				$terrIDByName[$name]=$id;

			$UnitINSERTs = array();
			foreach($this->countryUnits as $countryName => $params)
			{
				$countryID = $Game->Variant->countryID($countryName);

				foreach($params as $terrName=>$unitType)
				{
					$terrID = $terrIDByName[$terrName];
					$unitType = "Build " . $unitType;
					$UnitINSERTs[] = "(".$Game->id.", ".$countryID.", '".$terrID."', '".$unitType."')"; // ( gameID, countryID, terrID, type )
				}
			}
			$DB->sql_put(
				"INSERT INTO wD_Orders ( gameID, countryID, toTerrID, type )
				VALUES ".implode(', ', $UnitINSERTs)
			);		
		} else {
			// Build anywhere
			$newOrders = array();
			foreach($Game->Members->ByID as $Member )
			{
				$difference = 0;
				if ( $Member->unitNo > $Member->supplyCenterNo )
				{
					$difference = $Member->unitNo - $Member->supplyCenterNo;
					$type = 'Destroy';
				}
				elseif ( $Member->unitNo < $Member->supplyCenterNo )
				{
					$difference = $Member->supplyCenterNo - $Member->unitNo;
					$type = 'Build Army';
				}
	
				for( $i=0; $i < $difference; ++$i )
				{
					$newOrders[] = "(".$Game->id.", ".$Member->countryID.", '".$type."')";
				}
			}
	
			if ( count($newOrders) )
			{
				$DB->sql_put("INSERT INTO wD_Orders
								(gameID, countryID, type)
								VALUES ".implode(', ', $newOrders));
			}
	
		}
		
	}

	/** 
	 * This extension replaces the algorithm to decide which units to destroy if 
	 * valid destroy orders were missing. 
	 * Originally the unit destroy index is used to determine which unit is 
	 * furthest away from home SCs. Since there are no real home SCs in Build 
	 * Anywhere variants and it is also possible to get units into spots that 
	 * are not reachable from the original home SCs the algorithm is replaced by
	 * a simpler one that just randomly chooses to destroy units not currently 
	 * capturing a SC.
	 */
	public function apply()
	{
		global $Game, $DB;

		$DB->sql_put(
				"DELETE FROM u
				USING wD_Units AS u
				INNER JOIN wD_Orders AS o ON ( ".$Game->Variant->deCoastCompare('o.toTerrID','u.terrID')." AND u.gameID = o.gameID )
				INNER JOIN wD_Moves m ON ( m.orderID = o.id AND m.gameID=".$GLOBALS['GAMEID']." )
				WHERE o.gameID = ".$Game->id." AND o.type = 'Destroy'
					AND m.success='Yes'");

		// Remove units randomly from non-SCs for any destroy orders that weren't successful
		$tabl = $DB->sql_tabl(
					"SELECT o.id, o.countryID FROM wD_Orders o
					INNER JOIN wD_Moves m ON ( m.orderID = o.id AND m.gameID=".$GLOBALS['GAMEID']." )
					WHERE o.type = 'Destroy' AND m.success = 'No' AND o.gameID = ".$Game->id
				);
		while(list($orderID, $countryID) = $DB->tabl_row($tabl))
		{
			list($unitID, $terrID) = $DB->sql_row(
				"SELECT u.id, u.terrID FROM wD_Units u
					INNER JOIN wD_Territories t
						ON ".$Game->Variant->deCoastCompare('t.id','u.terrID')."
				WHERE u.gameID = ".$Game->id." AND u.countryID = ".$countryID."
					AND t.mapID=".$Game->Variant->mapID." AND t.supply = 'No'
				ORDER BY RAND() LIMIT 1");

			$DB->sql_put("UPDATE wD_Orders SET toTerrID = '".$terrID."' WHERE id = ".$orderID);
			$DB->sql_put("UPDATE wD_Moves
				SET success = 'Yes', toTerrID = ".$Game->Variant->deCoast($terrID)." WHERE gameID=".$GLOBALS['GAMEID']." AND orderID = ".$orderID);

			$DB->sql_put("DELETE FROM wD_Units WHERE id = ".$unitID);
		}

		$DB->sql_put("INSERT INTO wD_Units ( gameID, countryID, type, terrID )
					SELECT o.gameID, o.countryID, IF(o.type = 'Build Army','Army','Fleet') as type, o.toTerrID
					FROM wD_Orders o INNER JOIN wD_Moves m ON ( m.orderID = o.id AND m.gameID=".$GLOBALS['GAMEID']." )
					WHERE o.gameID=".$Game->id." AND o.type LIKE 'Build%' AND m.success = 'Yes'");
		// All players have the correct amount of units
	}
	
}