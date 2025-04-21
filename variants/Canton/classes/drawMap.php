<?php
/*
	Copyright (C) 2018 Enriador / Oliver Auth

	This file is part of the Canton variant for webDiplomacy

	The Canton variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Canton variant for webDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/
defined('IN_CODE') or die('This script can not be run by itself.');

class CustomIcons_drawmap extends drawmap
{
	// Arrays for the custom icons:
	protected $unit_c =array(); // An array to store the owner of each territory
	protected $army_c =array(); // Custom army icons
	protected $fleet_c=array(); // Custom fleet icons

	// Load the custom icons...
	protected function loadImages()
	{
		$this->army_c[0]  = $this->loadImage('variants/Canton/resources/armyNeutral.png');
		$this->fleet_c[0] = $this->loadImage('variants/Canton/resources/fleetNeutral.png');
		for ($i=1; $i<=count($GLOBALS['Variants'][VARIANTID]->countries); $i++) {
			$this->army_c[$i]  = $this->loadImage('variants/Canton/resources/'.($this->smallmap ? 'small' : '' ).'army' .$GLOBALS['Variants'][VARIANTID]->countries[$i-1].'.png');
			$this->fleet_c[$i] = $this->loadImage('variants/Canton/resources/'.($this->smallmap ? 'small' : '' ).'fleet'.$GLOBALS['Variants'][VARIANTID]->countries[$i-1].'.png');
		}
		parent::loadImages();
	}

	// Save the countryID for every colored Territory (and their coasts)
	public function colorTerritory($terrID, $countryID)
	{
		$this->unit_c[$terrID]=$countryID;
		foreach (preg_grep( "/^".$this->territoryNames[$terrID].".* Coast.$/", $this->territoryNames) as  $id=>$name)
			$this->unit_c[$id]=$countryID;
		parent::colorTerritory($terrID, $countryID);
	}

	// Overwrite the country if a unit needs to draw a flag (and don't draw the flag) -> we use custom icons instead
	public function countryFlag($terrName, $countryID)
	{
		$this->unit_c[$terrName]=$countryID;
	}

	// Draw the custom icons:
	public function addUnit($terrName, $unitType)
	{
		$this->army  = $this->army_c[$this->unit_c[$terrName]];
		$this->fleet = $this->fleet_c[$this->unit_c[$terrName]];
		parent::addUnit($terrName, $unitType);
	}

}

class CantonVariant_drawMap extends CustomIcons_drawmap {

	/**
	 * An array of colors for different countries, indexed by countryID
	 * @var array
	 */
	protected $countryColors = array(
		0 => array(196, 171, 137), // Neutral
		1 => array( 35,  78, 186), // Britain
		2 => array(234, 199,  72), // China
		3 => array( 91, 181, 211), // France
		4 => array(224, 153,  53), // Holland
		5 => array(226, 145, 145), // Japan
		6 => array( 80, 119,  80), // Russia
		7 => array(255,  58,  58)  // Turkey
	);


	/**
	 * Resources, all required except names, which will be drawn on by the computer if not supplied.
	 * @return array[$resourceName]=$resourceLocation
	 */
	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/Canton/resources/smallmap.png',
				'army'=>'contrib/smallarmy.png',
				'fleet'=>'contrib/smallfleet.png',
				'names'=>'variants/Canton/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/Canton/resources/map.png',
				'army'=>'contrib/army.png',
				'fleet'=>'contrib/fleet.png',
				'names'=>'variants/Canton/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>