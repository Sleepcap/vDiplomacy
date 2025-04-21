<?php
/*
	Copyright (C) 2012 Gavin Atkinson / Oliver Auth

	This file is part of the Pirates variant for webDiplomacy

	The Pirates variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.

	The Pirates variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class BuildAnywhere_processOrderBuilds extends processOrderBuilds
{
	public function create()
	{
		global $DB, $Game;

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

class Hurricane_processOrderBuilds extends BuildAnywhere_processOrderBuilds
{
	public function create()
	{
		global $DB, $Game;
		parent::create();
		
		// Get the terrid from the old hurricane
		list($old_hurricane)=$DB->sql_row("SELECT terrID FROM wD_Units
				WHERE (gameID=".$Game->id." 
				AND countryID=".(count($Game->Variant->countries) + 1).")");
				
		// And destoy if there is one.
		if ($old_hurricane > 0)
			$DB->sql_put("INSERT INTO wD_Orders
							(gameID, countryID, type, toTerrID)
							VALUES (".$Game->id.","
									.(count($Game->Variant->countries) + 1).","
									."'Destroy',"
									.$old_hurricane.")");
		
		// Get a free Territory (without an SC)
		list($new_hurricane) = $DB->sql_row("SELECT t.id FROM wD_Territories t
					LEFT JOIN wD_TerrStatus ts ON (t.id = ts.terrID && ts.gameID=".$Game->id.")
					WHERE t.mapID=".$Game->Variant->mapID." && ts.occupyingUnitID IS NULL && t.supply='No'
						&& t.id IN (SELECT fromTerrID AS id FROM wD_Borders WHERE mapID=".$Game->Variant->mapID.")
					ORDER BY RAND() LIMIT 1");
		
		// And put a hurricane on it.
		$DB->sql_put("INSERT INTO wD_Orders
							(gameID, countryID, type, toTerrID)
							VALUES (".$Game->id.","
									.(count($Game->Variant->countries) + 1).","
									."'Build Army',"
									.$new_hurricane.")");
		
	}
}

class CustomStart_processOrderBuilds extends Hurricane_processOrderBuilds
{
	protected $countryUnits = array(
		'Spain'   => array('Havana'      =>'Fleet', 'Voodoo Witch Hut'=>'Army','Panama'      =>'Fleet'),
		'England' => array('Spanish Town'=>'Fleet', 'St Kitts'        =>'Army','Barbados'    =>'Fleet'),
		'France'  => array('St Domingue' =>'Fleet', 'Guadeloupe'      =>'Army','Martinique'  =>'Fleet'),
		'Holland' => array('Curacao'     =>'Fleet', 'St Martin'       =>'Army','St Eustatius'=>'Fleet'),
		'Dunkirkers'          => array('Caracus'     =>'Fleet', 'Campeche'    =>'Army'),
		'Henry Morgan'        => array('Port Royal'  =>'Fleet', 'Providence'  =>'Army'),
		'Francois l Olonnais' => array('Tortuga'     =>'Fleet', 'Florida Keys'=>'Army'),
		'Isaac Rochussen'     => array('Mona Passage'=>'Fleet', 'Mid Atlantic'=>'Army','Southeast Caribbean Sea'=>'Fleet'),
		'The Infamous El Guapo' => 
			array('Northern Gulf of Mexico'=>'Fleet', 'Western Gulf of Mexico'=>'Army', 'Yuchatan Channel'=>'Army','Isla de los Pinos'=>'Fleet'),
		'Daniel "The Terror" Johnson' => 
			array('North Riff'=>'Fleet', 'Mayaguana Passage'=>'Army', 'Florida Channel'=>'Army', 'Tongue of the Ocean'=>'Fleet'),
		'Daniel "The Exterminator" Montbars' =>
			array('Bermuda Triangle'=>'Fleet', 'Virgin Islands'=>'Army', 'Crooked Island Passage'=>'Army', 'Northwest Channel'=>'Fleet'),
		'Bartolomeu "The Portuguese" de la Cueva' =>
			array('Gulf of Venezuela'=>'Fleet', 'Skeleton Bluff'=>'Army', 'Waters of the Spanish Main'=>'Army', 'Central Caribbean Sea'=>'Fleet'),
		'Roche "The Rock" Braziliano' =>
			array('South Caribbean Sea'=>'Fleet', 'Southwest Caribbean Sea'=>'Army', 'Northwest Caribbean Sea'=>'Army', 'Northeast Caribbean Sea'=>'Fleet'),					
	);

	public function create()
	{
		global $DB, $Game;
		if ($Game->turn == 0) {

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
			parent::create();
		}		
	}
}

class PiratesVariant_processOrderBuilds extends CustomStart_processOrderBuilds {}
