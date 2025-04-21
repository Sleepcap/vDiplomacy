<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Caucasia variant for vDiplomacy

	The Caucasia variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Caucasia variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class CaucasiaVariant_drawMap extends drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(244, 151,  63), // Armenia
		2 => array(  4, 119,  41), // Chechnya
		3 => array(234,   7,  86), // Georgia
		4 => array( 35,  94, 211), // Russia
		5 => array( 84, 183, 162)  // Azerbaijan
	);

	protected function resources() {

		global $Variant;

		if( $this->smallmap )
		{
			return array(
				'map'     =>'variants/'.$Variant->name.'/resources/smallmap.png',
				'army'    =>'contrib/smallarmy.png',
				'fleet'   =>'contrib/smallfleet.png',
				'names'   =>'variants/'.$Variant->name.'/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'     =>'variants/'.$Variant->name.'/resources/map.png',
				'army'    =>'contrib/army.png',
				'fleet'   =>'contrib/fleet.png',
				'names'   =>'variants/'.$Variant->name.'/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}

}

?>
