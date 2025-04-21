<?php
/*
	Copyright (C) 2020 alifeee

	This file is part of the Scottish_Clan_Wars variant for vDiplomacy

	The Scottish_Clan_Wars variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Scottish_Clan_Wars variant for vDiplomacy is distributed in the hope that it will
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

class Scottish_Clan_WarsVariant_drawMap extends MoveFlags_drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(196, 143, 133), // Edinburgh
		2 => array(121, 175, 198), // Glasgow
		3 => array(234, 234, 175), // Dundee
		4 => array(168, 126, 159), // Aberdeen
		5 => array(239, 196, 228), // Orkney Islands
		6 => array(164, 196, 153), // Outer Hebrides
		7 => array(160, 138, 117)  // Kintyre
	);

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
	
	// No need to set transparency. Icans have transparent background
	protected function setTransparancies() {}	
	
}
?>