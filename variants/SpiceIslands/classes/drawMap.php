<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the SpiceIslands variant for vDiplomacy

	The SpiceIslands variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The SpiceIslands variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class MoveFlags_drawMap extends drawMap
{
	public function countryFlag($terrID, $countryID)
	{

		$flagBlackback = $this->color(array(0, 0, 0));
		$flagColor = $this->color($this->countryColors[$countryID]);

		list($x, $y) = $this->territoryPositions[$terrID];

		$coordinates = array(
			'top-left' => array(
						 'x'=>$x-intval($this->fleet['width']/2+2),
						 'y'=>$y-intval($this->fleet['height']/2+1)
						 ),
			'bottom-right' => array(
						 'x'=>$x+intval($this->fleet['width']/2+2),
						 'y'=>$y+intval($this->fleet['height']/2+1)
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
}

class SpiceIslandsVariant_drawMap extends MoveFlags_drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(194, 197, 206), // Brunei
		2 => array( 89,  86, 255), // Majapahit
		3 => array(181, 144,  25), // Tondo
		4 => array( 48, 151,  46), // Malacca
		5 => array(254,  56,  47), // Ayutthaya
		6 => array(172, 101, 171), // Ternate
		7 => array(232, 234, 110)  // Dai Viet
	);

	protected function resources() {

		global $Variant;
		$prefix = ( ($this->smallmap) ? 'small' : '');

		return array(
			'map'     =>'variants/'.$Variant->name.'/resources/'.$prefix.'map.png',
			'army'    =>'variants/'.$Variant->name.'/resources/'.$prefix.'army.png',
			'fleet'   =>'variants/'.$Variant->name.'/resources/'.$prefix.'fleet.png',
			'names'   =>'variants/'.$Variant->name.'/resources/'.$prefix.'mapNames.png',
			'standoff'=>'images/icons/cross.png'
		);

	}

	// No need to set transparency. Icans have transparent background
	protected function setTransparancies() {}

}

?>
