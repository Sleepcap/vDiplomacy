<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Crusades1201 variant for vDiplomacy

	The Crusades1201 variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Crusades1201 variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class NoBorder_drawMap extends drawMap
{
	/**
	 * Add the territory names, either with GD FreeType or with the small-map overlay
	 */
 	public function addTerritoryNames()
	{
		$this->mapNames = $this->loadImage($this->mapNames);
		$this->setTransparancy($this->mapNames);
		$this->putImage($this->mapNames, 0, 0);
		imagedestroy($this->mapNames['image']);
	}
}		

class MoveFlags_drawMap extends NoBorder_drawMap
{
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
}

class Crusades1201Variant_drawMap extends MoveFlags_drawMap {

	protected $countryColors = array(
		 0 => array(226, 198, 158), // Neutral
		 1 => array(236, 217,   0), // Castille
		 2 => array(255, 123,  57), // Almohad Caliphate
		 3 => array(249,  47,  47), // England
		 4 => array( 43,  75, 255), // France
		 5 => array(189, 189, 189), // Holy Roman Empire
		 6 => array(255,  81, 255), // Denmark
		 7 => array(135,  41, 135), // Papacy
		 8 => array( 38, 255, 139), // Hungary
		 9 => array(189, 189,   0), // Rus
		10 => array(  0, 196, 196), // Byzantine Empire
		11 => array(255, 235, 112)  // Ayyubid Sultanate
	);

	protected function resources() {

		global $Variant;

		if( $this->smallmap )
		{
			return array(
				'map'     =>'variants/'.$Variant->name.'/resources/smallmap.png',
				'army'    =>'variants/'.$Variant->name.'/resources/smallarmy.png',
				'fleet'   =>'variants/'.$Variant->name.'/resources/smallfleet.png',
				'names'   =>'variants/'.$Variant->name.'/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'     =>'variants/'.$Variant->name.'/resources/map.png',
				'army'    =>'variants/'.$Variant->name.'/resources/army.png',
				'fleet'   =>'variants/'.$Variant->name.'/resources/fleet.png',
				'names'   =>'variants/'.$Variant->name.'/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}

	// No need to set transparency. Icans have transparent background
	protected function setTransparancies() {}

}

?>
