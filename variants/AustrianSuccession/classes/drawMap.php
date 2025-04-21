<?php
/*
	Copyright (C) 2015 Oliver Auth / Safari

	This file is part of the War of Austrian Succession variant for webDiplomacy

	The War of Austrian Succession variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The War of Austrian Succession variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomCountryIcons_drawMap extends drawMap {
	
	// Arrays for the custom icons:
	protected $unit_c =array(); // An array to store the owner of each territory
	protected $army_c =array(); // Custom army icons
	protected $fleet_c=array(); // Custom fleet icons
	
	// Load custom icons (fleet and army) for each country
	protected function loadImages()
	{
		$this->army_c[0]  = $this->loadImage('variants/AustrianSuccession/resources/army_1.png');
		$this->fleet_c[0] = $this->loadImage('variants/AustrianSuccession/resources/fleet_1.png');
		
		for ($i=1; $i<=count($GLOBALS['Variants'][VARIANTID]->countries); $i++) {
			$this->army_c[$i]  = $this->loadImage('variants/AustrianSuccession/resources/army_' .$i.'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/AustrianSuccession/resources/fleet_'.$i.'.png');
		}
		parent::loadImages();
	}
	
	// Save the countryID for every colored Territory (and their coasts)
	public function colorTerritory($terrID, $countryID)
	{
		$terrName=$this->territoryNames[$terrID];
		$this->unit_c[$terrID]=$countryID;
		$this->unit_c[array_search($terrName. " (North Coast)" ,$this->territoryNames)]=$countryID;
		$this->unit_c[array_search($terrName. " (East Coast)"  ,$this->territoryNames)]=$countryID;
		$this->unit_c[array_search($terrName. " (South Coast)" ,$this->territoryNames)]=$countryID;
		$this->unit_c[array_search($terrName. " (West Coast)"  ,$this->territoryNames)]=$countryID;
		parent::colorTerritory($terrID, $countryID);
	}
	
	// Store the country if a unit needs to draw a flag for a custom icon.
	public function countryFlag($terrName, $countryID)
	{
		$this->unit_c[$terrName]=$countryID;
	}
	
	// Draw the custom icons:
	public function addUnit($terrID, $unitType)
	{
		$this->army  = $this->army_c[$this->unit_c[$terrID]];
		$this->fleet = $this->fleet_c[$this->unit_c[$terrID]];
		parent::addUnit($terrID, $unitType);
	}
	
}


class AustrianSuccessionVariant_drawMap extends CustomCountryIcons_drawMap {

	protected $countryColors = array(
		0  => array(241, 214, 144), // Neutral
		1  => array(230, 207,  83), // Austria
		2  => array( 77, 113, 163), // Bavaria
		3  => array(217, 100,  77), // England
		4  => array(121, 198, 212), // France
		5  => array(152,  88,  68), // Ottoman Empire
		6  => array(165, 197, 139), // Piedmont-Sardinia
		7  => array(250, 165,   0), // Prussia
		8  => array(225, 177, 185), // Russia
		9  => array( 86, 128,  46)  // Spain
	);
	
	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		if ( !$this->smallmap )	ini_set('memory_limit',"30M");
		parent::__construct($smallmap);
	}
	
	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'     =>l_s('variants/AustrianSuccession/resources/smallmap.png'),
				'army'    =>l_s('contrib/smallarmy.png'),
				'fleet'   =>l_s('contrib/smallfleet.png'),
				'names'   =>l_s('variants/AustrianSuccession/resources/smallmapNames.png'),
				'standoff'=>l_s('images/icons/cross.png')
			);
		}
		else
		{
			return array(
				'map'     =>l_s('variants/AustrianSuccession/resources/map.png'),
				'army'    =>l_s('contrib/army.png'),
				'fleet'   =>l_s('contrib/fleet.png'),
				'names'   =>l_s('variants/AustrianSuccession/resources/mapNames.png'),
				'standoff'=>l_s('images/icons/cross.png')
			);
		}
	}

}

?>