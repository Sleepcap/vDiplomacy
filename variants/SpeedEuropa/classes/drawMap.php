<?php
/*
    Copyright (C) 2004-2009 Kestas J. Kuliukas

	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('IN_CODE') or die('This script can not be run by itself.');

class SpeedEuropaVariant_drawMap extends drawMap {

	/**
	 * An array of colors for different countries, indexed by countryID
	 * @var array
	 */
	protected $countryColors = array(
		0 => array(226, 198, 158),
		1 => array(239, 196, 228),
		2 => array(121, 175, 198),
		3 => array(164, 196, 153),
		4 => array(160, 138, 117),
		5 => array(196, 143, 133),
		6 => array(234, 234, 175),
		7 => array(168, 126, 159)
	);

	/**
	 * Resources, all required except names, which will be drawn on by the computer if not supplied.
	 * @return array[$resourceName]=$resourceLocation
	 */
	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/SpeedEuropa/resources/smallmap.png',
				'army'=>'contrib/smallarmy.png',
				'fleet'=>'contrib/smallfleet.png',
				'names'=>'variants/SpeedEuropa/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/SpeedEuropa/resources/map.png',
				'army'=>'contrib/army.png',
				'fleet'=>'contrib/fleet.png',
				'names'=>'variants/SpeedEuropa/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>