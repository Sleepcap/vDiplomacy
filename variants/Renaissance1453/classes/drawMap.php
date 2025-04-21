<?php
/*
	Copyright (C) 2018 Oliver Auth

	This file is part of the Renaissance1453 variant for webDiplomacy

	The Renaissance1453 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Renaissance1453 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Renaissance1453Variant_drawMap extends drawMap {

	/**
	 * An array of colors for different countries, indexed by countryID
	 * @var array
	 */
	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(196, 143, 133), // Venice
		2 => array(164, 196, 153), // Spain
		3 => array(121, 175, 198), // France
		4 => array(239, 196, 228), // England
		5 => array(168, 126, 159), // Poland-Lithuania
		6 => array(160, 138, 117), // Holy Roman Empire
		7 => array(234, 234, 175)  // Turkey
	);

	public function countryFlag($terrID, $countryID)
	{
		
		$flagBlackback = $this->color(array(0, 0, 0));
		$flagColor = $this->color($this->countryColors[$countryID]);

		list($x, $y) = $this->territoryPositions[$terrID];

		$coordinates = array(
			'top-left' => array( 
						 'x'=>$x-intval($this->fleet['width']/2+1),
						 'y'=>$y-intval($this->fleet['height']/2+1)
						 ),
			'bottom-right' => array(
						 'x'=>$x+intval($this->fleet['width']/2+1),
						 'y'=>$y+intval($this->fleet['height']/2-1)
						 )
		);

		imagefilledrectangle($this->map['image'],
			$coordinates['top-left']['x'], $coordinates['top-left']['y'],
			$coordinates['bottom-right']['x'], $coordinates['bottom-right']['y'],
			$flagBlackback);
		imagefilledrectangle($this->map['image'],
			$coordinates['top-left']['x']+1, $coordinates['top-left']['y']+1,
			$coordinates['bottom-right']['x']-1, $coordinates['bottom-right']['y']-1,
			$flagColor);
	}
	// No need to set transparency. Icans have transparent background
	protected function setTransparancies() {}	
	
	/**
	 * Resources, all required except names, which will be drawn on by the computer if not supplied.
	 * @return array[$resourceName]=$resourceLocation
	 */
	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/Renaissance1453/resources/smallmap.png',
				'army'=>'variants/Renaissance1453/resources/smallarmy.png',
				'fleet'=>'variants/Renaissance1453/resources/smallfleet.png',
				'names'=>'variants/Renaissance1453/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/Renaissance1453/resources/map.png',
				'army'=>'variants/Renaissance1453/resources/army.png',
				'fleet'=>'variants/Renaissance1453/resources/fleet.png',
				'names'=>'variants/Renaissance1453/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>