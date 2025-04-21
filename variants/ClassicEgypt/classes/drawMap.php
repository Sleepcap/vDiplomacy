<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of vDiplomacy.

    vDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    vDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with vDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('IN_CODE') or die('This script can not be run by itself.');

class ClassicEgyptVariant_drawMap extends drawMap {

	/**
	 * An array of colors for different countries, indexed by countryID
	 * @var array
	 */
	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(239, 196, 228), // England
		2 => array(121, 175, 198), // France
		3 => array(164, 196, 153), // Italy
		4 => array(160, 138, 117), // Germany
		5 => array(196, 143, 133), // Austria
		6 => array(234, 234, 175), // Turkey
		7 => array(168, 126, 159)  // Russia
	);

	protected function resources() {

		global $Variant;
		$prefix = ( ($this->smallmap) ? 'small' : '');

		return array(
			'map'     =>'variants/'.$Variant->name.'/resources/'.$prefix.'map.png',
			'army'    =>'contrib/'.$prefix.'army.png',
			'fleet'   =>'contrib/'.$prefix.'fleet.png',
			'names'   =>'variants/'.$Variant->name.'/resources/'.$prefix.'mapNames.png',
			'standoff'=>'images/icons/cross.png'
		);

	}

}

?>