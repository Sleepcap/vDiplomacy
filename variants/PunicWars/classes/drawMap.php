<?php
/*
	Copyright (C) 2010 Carey Jensen / Oliver Auth

	This file is part of the Sail Ho II variant for webDiplomacy

	The Sail Ho II variant for webDiplomacy" is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either
	version 3 of the License, or (at your option) any later version.

	The Sail Ho II variant for webDiplomacy is distributed in the hope that it
	will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Fog_drawMap extends drawmap
{
	// variable to store the color-index for the fog color
	protected $fog_index;
	
	// Check if it's called from our special map-code. If not a player might cheat and we set all to fog.
	// or the game is over, than we reveal the map.
	
	protected $all_fog  = true;
	protected $show_all = false;
	
	// Check if it's called from our special map-code. If not a player might want to cheat and we set all to fog.
	// or the game is over, than we reveal the map.
	public function __construct($smallmap,$all_fog=true)
	{
		global $Game;
		
		// Add the fog and sea colors to the country-palette
		$this->fog_index = count($this->countryColors);
		$this->countryColors[$this->fog_index] = array(200, 200, 200); // Fog
		$this->countryColors[$this->fog_index+1] = array(161, 226, 255); // Sea
		
		if (isset($Game)) {
			if ($Game->phase == 'Finished')
				$this->show_all=true;
		}
		$this->all_fog=$all_fog;
		parent::__construct($smallmap);
	}
	
	public function colorTerritory($terrID, $countryID)	
	{
		// Just cover everything with fog if a cheater want to take a look...
		if (!$this->show_all && $this->all_fog) $countryID = $this->fog_index;
		parent::colorTerritory($terrID, $countryID);
	}

	// Hide order-info as long as the game is not finished.
	public function countryFlag($terrName, $countryID)	{
		if ($this->show_all || !$this->all_fog) parent::countryFlag($terrName, $countryID);
	}
	
	public function addUnit($terrName, $unitType)	{
		if ($this->show_all || !$this->all_fog) parent::addUnit($terrName, $unitType);
	}
	public function drawStandoff($terrName)	{
		if ($this->show_all || !$this->all_fog) parent::drawStandoff($terrName);
	}	
	public function drawSupportMove($terrID, $fromTerrID, $toTerrID, $success)	{
		if ($this->show_all || !$this->all_fog) parent::drawSupportMove($terrID, $fromTerrID, $toTerrID, $success);
	}
	public function drawConvoy($terrID, $fromTerrID, $toTerrID, $success){
		if ($this->show_all || !$this->all_fog) parent::drawConvoy($terrID, $fromTerrID, $toTerrID, $success);
	}
	public function drawMove($fromTerrID, $toTerrID, $success)	{
		if ($this->show_all || !$this->all_fog) parent::drawMove($fromTerrID, $toTerrID, $success);
	}
	public function drawSupportHold($fromTerrID, $toTerrID, $success)	{
		if ($this->show_all || !$this->all_fog) parent::drawSupportHold($fromTerrID, $toTerrID, $success);
	}
	public function drawRetreat($fromTerrID, $toTerrID, $success) {
		if ($this->show_all || !$this->all_fog) parent::drawRetreat($fromTerrID, $toTerrID, $success);
	}
	public function drawDestroyedUnit($terrID)	{
		if ($this->show_all || !$this->all_fog) parent::drawDestroyedUnit($terrID);
	}
	public function drawDislodgedUnit($terrID)	{
		if ($this->show_all || !$this->all_fog) parent::drawDislodgedUnit($terrID);
	}
	public function drawCreatedUnit($terrID, $unitType)	{
		if ($this->show_all || !$this->all_fog) parent::drawCreatedUnit($terrID, $unitType);
	}
}

class PunicWarsVariant_drawMap extends Fog_drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		//0 => array(183, 183, 183), // Neutral
		1 => array(196, 143, 133), // Carthago
		2 => array(234, 234, 175), // Campania
		3 => array(168, 126, 159), // Etruria
		4 => array(164, 196, 153), // Romae
	);

	protected function resources() {
		if ( $this->smallmap )
			$images=array(
				'army'    =>'variants/PunicWars/resources/army.png',
				'fleet'   =>'variants/PunicWars/resources/fleet.png',
				'names'   =>'variants/PunicWars/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png');
		else
			$images=array(
				'army'    =>'variants/PunicWars/resources/army.png',
				'fleet'   =>'variants/PunicWars/resources/fleet.png',
				'names'   =>'variants/PunicWars/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png');
		
		if ($this->show_all || $this->all_fog) {
			$images['map'] ='variants/PunicWars/resources/map_noFog.png';
		} else {
			$images['map'] ='variants/PunicWars/resources/map.png';
		}
		return $images;
	}

}
?>