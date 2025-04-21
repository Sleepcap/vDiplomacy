<?php
/*
	Copyright (C) 2018 Yuriy Hryniv aka Flame

	This file is part of the Classic 1898 Fog-of-War variant for webDiplomacy

	The Classic 1898 Fog-of-War variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General Public
	License as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Classic 1898 Fog-of-War variant for webDiplomacy is distributed in the hope that 
	it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Classic1898FogVariant_OrderArchiv extends OrderArchiv {

	/*
	 * An Array storing the visible territories for each turn. 
	 */
	public $noFog;
	
	/*
	 * The countryID of the current user. If user is not a member $countryID has
	 * the value "Guest". 
	 */
	public $countryID;
	
	// Hide the OrderLog
	public function outputOrderLogs(array $orders)
	{
		global $Game, $User, $Variant;
		if ($Game->phase == 'Finished') return parent::outputOrderLogs($orders);
		
		if ($User->type['Moderator'] && $this->countryID == "Guest") return parent::outputOrderLogs($orders);
		
		// filter out all orders that are not visible for user
		$filteredOrders = array();
		if ($this->countryID != "Guest") {
			foreach($orders as $type=>$orderByType){
				foreach($orderByType as $order){
					
					$turn = $order["turn"];

					if (isset($this->noFog[$turn]) && 
							( in_array($Variant->decoast($order["terrID"]),$this->noFog[$turn]) 
								|| in_array($Variant->decoast($order["toTerrID"]),$this->noFog[$turn]) )){
						// -> order should be visible to user -> include in final array
						
						if( !isset($filteredOrders[$type]) )
							$filteredOrders[$type] = array();
						
						$filteredOrders[$type][] = $order;
	}

}
				
			}
		}
		return parent::outputOrderLogs($filteredOrders);
	}
	
	// store visible territories of each turn before building the log (analogous to fogmap.php
	function buildLogs() {
		global $Game, $Variant, $DB, $User;
		
		if ($Game->Members->isJoined()) {
			$this->countryID = $Game->Members->ByUserID[$User->id]->countryID;
		} else {
			$this->countryID = "Guest";
		}
		
		$this->noFog = array();

		if ($this->countryID != "Guest") {
		
			// get turn-specific no fog areas
			
			/*
			 * Determine which turn we are viewing. This is made a little trickier because
			 * in the Diplomacy phase the *previous* turn is drawn. In Retreats and Builds
			 * the current turn is drawn.
			 */

		   // Determine the turn number:
		   if ( $Game->phase == 'Diplomacy' ) $latestTurn = $Game->turn-1;
		   else $latestTurn = $Game->turn;
			
			// 1. past turns
			
			// neigboring territories of units or SCs
			$sql = "SELECT b.toTerrID, ts.turn 
					FROM wD_Borders b 
						INNER JOIN wD_Territories t 
							ON (t.coastParentID = b.fromTerrID AND t.mapID=b.mapID) 
						INNER JOIN wD_TerrStatusArchive ts 
							ON (t.id = ts.terrID AND ts.gameID=".$Game->id.")
						LEFT JOIN wD_MovesArchive m 
							ON  (m.terrID=t.id AND m.gameID=ts.gameID AND m.turn = ts.turn+1)
					
					WHERE t.mapID=".$Variant->mapID." 
						AND ((t.supply='Yes' AND ts.countryID=".$this->countryID.") 
							OR m.countryID=".$this->countryID.")
						AND ts.turn < ".$latestTurn;

			// plus own territories
			$sql .= "
				UNION 
					SELECT terrID, turn 
					FROM wD_TerrStatusArchive 
					WHERE countryID=".$this->countryID." AND gameID=".$Game->id;

			$tabl = $DB->sql_tabl($sql);
			
			while(list($terrID,$turn) = $DB->tabl_row($tabl))
			{
				if( !isset($this->noFog[$turn]) ) 
					$this->noFog[$turn] = array();
				
				$this->noFog[$turn][] = $Variant->deCoast($terrID);
			}
			
			// 2. current turn (in case of retreats / builds)
			
			// neigboring territories of units or SCs
			$sql = "SELECT b.toTerrID
					FROM wD_Borders b 
						INNER JOIN wD_Territories t 
							ON (t.coastParentID = b.fromTerrID AND t.mapID=b.mapID) 
						INNER JOIN wD_TerrStatus ts 
							ON (t.id = ts.terrID AND ts.gameID=".$Game->id.")
						LEFT JOIN wD_Units u 
							ON  (u.terrID=t.id AND u.gameID=ts.gameID)
					
					WHERE t.mapID=".$Variant->mapID." 
						AND ((t.supply='Yes' AND ts.countryID=".$this->countryID.") 
							OR u.countryID=".$this->countryID.")";

			// plus own territories
			$sql .= "UNION 
					SELECT terrID
					FROM wD_TerrStatus 
					WHERE countryID=".$this->countryID." AND gameID=".$Game->id;

			$tabl = $DB->sql_tabl($sql);
			
			while(list($terrID) = $DB->tabl_row($tabl))
			{
				if( !isset($this->noFog[$Game->turn-1]) ) 
					$this->noFog[$latestTurn] = array();
				
				$this->noFog[$latestTurn][] = $Variant->deCoast($terrID);
			}
		}
		
		parent::buildLogs();
	}
}
?>
