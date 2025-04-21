<?php
/*
	Copyright (C) 2018 Yuriy Hryniv aka Flame

	This file is part of the WesternWorld_901 variant for webDiplomacy

	The WesternWorld_901 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The WesternWorld_901 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class ZoomMap_drawMap extends drawMap
{
	// Always only load the largemap (as there is no smallmap)
	public function __construct($smallmap) {
		parent::__construct(false);
	}

	// Always use the small orderarrows...
	protected function loadOrderArrows() {
		$this->smallmap=true; parent::loadOrderArrows(); $this->smallmap=false;
	}

	// Always use the small standoff-Icons
	public function drawStandoff($terrName) {
		$this->smallmap=true; parent::drawStandoff($terrName); $this->smallmap=false;
	}

	// Always use the small failure-cross...
	protected function drawFailure(array $from, array $to) {
		$this->smallmap=true; parent::drawFailure($from, $to); $this->smallmap=false;
	}
}

class CustomCountryIcons_drawMap extends ZoomMap_drawMap {

	// Arrays for the custom icons:
	protected $unit_c =array(); // An array to store the owner of each territory
	protected $army_c =array(); // Custom army icons
	protected $fleet_c=array(); // Custom fleet icons

	// Load custom icons (fleet and army) for each country
	protected function loadImages()
	{
	//	$cvr = count($GLOBALS['Variants'][VARIANTID]->countries);
	//	echo $cvr;

		$this->army_c[0]  = $this->loadImage('variants/WesternWorld_901/resources/army_1.png');
		$this->fleet_c[0] = $this->loadImage('variants/WesternWorld_901/resources/fleet_1.png');

		for ($i=1; $i<=10; $i++) {
			$this->army_c[$i]  = $this->loadImage('variants/WesternWorld_901/resources/army_' .$i.'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/WesternWorld_901/resources/fleet_'.$i.'.png');
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


class WesternWorld_901Variant_drawMap extends CustomCountryIcons_drawMap {

	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		ini_set('memory_limit',"35M");
	}

	protected $countryColors = array(
		0 => array(170, 159, 85), // Neutral
		1 => array(114, 146, 103), // Arabia
		2 => array(168, 84, 82), // Byzantium
		3 => array(222, 91, 91), // Denmark
		4 => array(235, 217, 132), // Egypt
		5 => array(6, 86, 168), // France
		6 => array(47, 93, 111), // Germany
		7 => array(0, 255, 255), // Khazaria
		8 => array(0, 150, 150), // Rus
		9 => array(140, 186, 28), // Spain
       10 => array(170, 159, 85) // Neutral Units  170, 159, 85
	);



	// No need to set the transparency for our custom icons.
	protected function setTransparancy(array $image, array $color=array(255,255,255)) {}

	protected function resources() {
		return array(
			'map'     =>'variants/WesternWorld_901/resources/map.png',
			'army'	  =>'contrib/army.png',
			'fleet'   =>'contrib/fleet.png',
			'names'   =>'variants/WesternWorld_901/resources/mapNames.png',
			'standoff'=>'images/icons/cross.png'
		);
	}

}

?>