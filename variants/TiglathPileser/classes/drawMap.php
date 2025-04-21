<?php
/*
	Copyright (C) 2020 Oliver Auth

	This file is part of the TiglathPileser variant for vDiplomacy

	The TiglathPileser variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The TiglathPileser variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class TiglathPileserVariant_drawMap extends drawMap {

	protected $countryColors = array(
		0 => array(186, 182, 162), // Neutral
		1 => array(255, 140,  94), // Assyria
		2 => array(147,  72, 206), // Babylonia 
		3 => array(  0, 139, 139), // Urartu
		4 => array(238, 146, 238), // Kush
		5 => array(255, 221,  42), // Elam
		6 => array( 33, 131, 255), // Egypt
		7 => array(128, 128,   0), // Phrygia
		8 => array( 75, 190,  92)  // Saba
	);

	// No need to set the transparency for our custom icons and mapnames.
	protected function setTransparancy(array $image, array $color=array(255,255,255)) {}

	// Move the country-flag behind the countries
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
						 'x'=>$x+intval($this->fleet['width']/2+1)+1,
						 'y'=>$y+intval($this->fleet['height']/2-1)+1
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

	protected function resources() {

		global $Variant;
		$prefix = ( ($this->smallmap) ? 'small' : '');

		return array(
			'map'     =>'variants/'.$Variant->name.'/resources/'.$prefix.'map.png',
			'names'   =>'variants/'.$Variant->name.'/resources/'.$prefix.'mapNames.png',
			'army'    =>'variants/'.$Variant->name.'/resources/'.$prefix.'army.png',
			'fleet'   =>'variants/'.$Variant->name.'/resources/'.$prefix.'fleet.png',
			'standoff'=>'images/icons/cross.png'
		);
	}

}
?>