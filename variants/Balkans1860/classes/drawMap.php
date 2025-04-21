<?php
/*
	Copyright (C) 2017 Oliver Auth

	This file is part of the Balkans 1860 variant for webDiplomacy

	The Balkans 1860 variant for webDiplomacy is free software:
	you can redistribute it and/or modify it under the terms of the GNU Affero
	General Public License as published by the Free Software Foundation, either
	version 3 of the License, or (at your option) any later version.

	The Balkans 1860 variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
	
	If you have questions or suggestions send me a mail: Oliver.Auth@rhoen.de
*/


defined('IN_CODE') or die('This script can not be run by itself.');

class Balkans1860Variant_drawMap extends drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158),
		1 => array(196, 143, 133), // Austria-Hungary
		2 => array(160, 138, 117), // Bulgaria
		3 => array(121, 175, 198), // Greece
		4 => array(164, 196, 153), // Italy
		5 => array(234, 234, 175), // Ottomans
		6 => array(239, 196, 228), // Romania
		7 => array(168, 126, 159)  // Serbia
	);

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>l_s('variants/Balkans1860/resources/smallmap.png'),
				'army'=>l_s('contrib/smallarmy.png'),
				'fleet'=>l_s('contrib/smallfleet.png'),
				'names'=>l_s('variants/Balkans1860/resources/smallmapNames.png'),
				'standoff'=>l_s('images/icons/cross.png')
			);
		}
		else
		{
			return array(
				'map'=>l_s('variants/Balkans1860/resources/map.png'),
				'army'=>l_s('contrib/army.png'),
				'fleet'=>l_s('contrib/fleet.png'),
				'names'=>l_s('variants/Balkans1860/resources/mapNames.png'),
				'standoff'=>l_s('images/icons/cross.png')
			);
		}
	}
}

?>