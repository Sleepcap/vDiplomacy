<?php
/*
	Copyright (C) 2018 Enriador / Oliver Auth

	This file is part of the Classic1913 variant for webDiplomacy

	The Classic1913 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Classic1913 variant for webDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/
defined('IN_CODE') or die('This script can not be run by itself.');

class Classic1913Variant_drawMap extends drawMap {

	/**
	 * An array of colors for different countries, indexed by countryID
	 * @var array
	 */
	protected $countryColors = array(
		0 => array(226, 198, 158), /* Neutral */
		1 => array(239, 196, 228), /* England */
		2 => array(121, 175, 198), /* France  */
		3 => array(164, 196, 153), /* Italy   */
		4 => array(160, 138, 117), /* Germany */
		5 => array(196, 143, 133), /* Austria */
		6 => array(234, 234, 175), /* Turkey  */
		7 => array(168, 126, 159)  /* Russia  */
	);

	protected $initMap = false;
	
	/**
	 * Egypt and Algier are _no_ home-SCs for England and France, so they are set as "Neutral" initaially.
	 * The correct owner is set in adjucator-pregame right at the start of the game.
	 * Just set the correct owner for the initial-map.
	 * The initMap variable is needed, because it will draw a countryflag for the 2 units starting here
	 * and we need to catch this.
	 */
	public function ColorTerritory($terrID, $countryID)	
	{
		if ($terrID == 38 && $countryID == 0) { $countryID=2; } // Algeria
		if ($terrID == 43 && $countryID == 0) { $countryID=1; $this->initMap = true; } // Egypt
		parent::ColorTerritory($terrID, $countryID);
	}
	
	public function countryFlag($terrID, $countryID)
	{
		if ($this->initMap == false ) parent::countryFlag($terrID, $countryID);
	}
	
	/**
	 * Resources, all required except names, which will be drawn on by the computer if not supplied.
	 * @return array[$resourceName]=$resourceLocation
	 */
	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/Classic1913/resources/smallmap.png',
				'army'=>'contrib/smallarmy.png',
				'fleet'=>'contrib/smallfleet.png',
				'names'=>'variants/Classic1913/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/Classic1913/resources/map.png',
				'army'=>'contrib/army.png',
				'fleet'=>'contrib/fleet.png',
				'names'=>'variants/Classic1913/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>