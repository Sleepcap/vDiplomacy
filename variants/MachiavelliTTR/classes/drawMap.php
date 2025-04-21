<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Machiavelli variant for vDiplomacy

	The Machiavelli variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Machiavelli variant for vDiplomacy is distributed in the hope that it will
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

class MachiavelliTTRVariant_drawMap extends MoveFlags_drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(255,  68, 245), // Aragon
		2 => array( 52, 219, 210), // Florence
		3 => array(255, 251,  45), // Avignon
		4 => array(255,  66,  66), // Milan
		5 => array( 28,  92, 255), // Genoa
		6 => array( 70, 255,  63), // Papacy
		7 => array(160,   0,   0)  // Venice
	);
	
	public function __construct($smallmap)
	{
		$this->countryColors[] = array(226, 198, 158); // Neutral
		parent::__construct($smallmap);
	}

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'     =>'variants/MachiavelliTTR/resources/smallmap.png',
				'army'    =>'variants/MachiavelliTTR/resources/smallarmy.png',
				'fleet'   =>'variants/MachiavelliTTR/resources/smallfleet.png',
				'names'   =>'variants/MachiavelliTTR/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'     =>'variants/MachiavelliTTR/resources/map.png',
				'army'    =>'variants/MachiavelliTTR/resources/army.png',
				'fleet'   =>'variants/MachiavelliTTR/resources/fleet.png',
				'names'   =>'variants/MachiavelliTTR/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}

	// No need to set transparency. Icans have transparent background
	protected function setTransparancies() {}
}

?>
