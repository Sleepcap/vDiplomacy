<?php

/*
	Copyright (C) 2018 Technostar / Oliver Auth

	This file is part of the Divided States variant for webDiplomacy

	The Divided States variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Divided States variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomStart_processGame extends processGame {

	protected function changePhase() {
		if( $this->phase == 'Pre-game' )
		{
			// Builds first after the game starts
			$this->setPhase('Builds');

			// This gives the map some color to start with
			$this->archiveTerrStatus();

			return false;
		}
		elseif( $this->phase == 'Builds' && $this->turn==0 )
		{
			// The first Spring builds just finished, make sure we don't go to the next turn

			$this->phase='Pre-game'; // This prevents a turn being added on in setPhase, keeping it in Spring, 1901
			// (It won't activate twice because the next time it won't go into a Builds phase in Spring)

			$this->setPhase('Diplomacy'); // Diplomacy, Spring 1901, and from then on like nothing was different

			$this->archiveTerrStatus();
			return false;
		}
		else
			return parent::changePhase(); // Except those two phases above behave normally
	}
	
	function process()
	{
		global $DB;
		parent::process();

		// If we have a diplomacy-phase add the support-hold commands for the neutral units.
		if ($this->phase == 'Diplomacy')
		{
			$tabl=$DB->sql_tabl('SELECT id, terrID FROM wD_Units
									WHERE countryID='.(count($this->Variant->countries) + 1).' AND gameID='.$this->id);
			$units=array();
			while ( list($unitID, $terrID) = $DB->tabl_row($tabl))
				$units[$unitID] = $terrID;
 		
			foreach ($units as $unitID=>$terrID)
				if (array_key_exists($terrID, $this->Variant->neutralHold))
					if ( in_array($this->Variant->neutralHold[$terrID],$units) )
						$DB->sql_put("UPDATE wD_Orders SET type='Support Hold', toTerrID='".$this->Variant->neutralHold[$terrID]."' WHERE unitID=".$unitID);
						
						
		}
		
		// If only the "neutral player has to do retreats process again.
		if ($this->phase == 'Retreats')
		{	
			list($count) = $DB->sql_row("SELECT COUNT(*)
				FROM wD_Members 
				WHERE orderStatus != 'None' AND gameID = ".$this->id);
			if ($count == 0)
				self::process();
		}	
	}
}

class Divided_StatesVariant_processGame extends CustomStart_processGame {}
