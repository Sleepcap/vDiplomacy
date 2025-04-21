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

class Balkans1860Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Italy'          => array('Firenze'  => 'Army',  'Torino'    => 'Army',  'Napoli'     => 'Fleet'),
		'Austria-Hungary'=> array('Budapest' => 'Army',  'Trieste'   => 'Fleet', 'Wien'       => 'Army'),
		'Serbia'         => array('Beograd'  => 'Army',  'Cetinje'   => 'Fleet', 'Kragujevac' => 'Army'),
		'Romania'        => array('Bucuresti'=> 'Army',  'Constanta' => 'Fleet', 'Iasi'       => 'Army'),
		'Bulgaria'       => array('Burgas'   => 'Fleet', 'Plovdiv'   => 'Army',  'Sofia'      => 'Army'),
		'Greece'         => array('Athens'   => 'Fleet', 'Larisa'    => 'Army',  'Kalamata'   => 'Fleet'),
		'Ottomans'       => array('Ankara'   => 'Army',  'Istanbul'  => 'Fleet', 'Izmir'      => 'Fleet')
	);

}