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

class ClassicFlorenceVariant_drawMap extends drawMap {

	/**
	 * An array of colors for different countries, indexed by countryID
	 * @var array
	 */
	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array( 22, 119, 188), // England
		2 => array(155, 194, 230), // France
		3 => array(148, 211,  84), // Italy
		4 => array( 86,  86,  86), // Germany
		5 => array(231,  52,  63), // Austria
		6 => array(255, 205,  58), // Turkey
		7 => array(255, 245, 231)  // Russia
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