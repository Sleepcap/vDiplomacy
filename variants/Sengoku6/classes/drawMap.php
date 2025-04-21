<?php
/*
	Copyright (C) 2017 Oliver Auth

	This file is part of the Sengoku6 variant for webDiplomacy

	The Sengoku6 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Sengoku6 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
	
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Sengoku6Variant_drawMap extends Sengoku5Variant_drawMap {

	protected $countryColors = array(
		0 => array(160, 224, 128), // Neutral
		1 => array(114, 146, 103), // Shimazu
		2 => array( 64, 108, 128), // Mori
		3 => array(222,  91,  91), // Oda
		4 => array(196, 143, 133), // Hojo
		5 => array(168, 126, 159), // Uesugi
		6 => array(160, 224, 128), // Neutral units
	);

	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		ini_set('memory_limit',"70M");
	}
	
	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'     =>'variants/Sengoku6/resources/smallmap.png',
				'names'   =>'variants/Sengoku6/resources/smallmapNames.png',
				'army'    =>'variants/Sengoku5/resources/smallarmy.png',
				'fleet'   =>'variants/Sengoku5/resources/smallfleet.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'     =>'variants/Sengoku6/resources/map.png',
				'names'   =>'variants/Sengoku6/resources/mapNames.png',
				'army'    =>'variants/Sengoku5/resources/army.png',
				'fleet'   =>'variants/Sengoku5/resources/fleet.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
	
}
