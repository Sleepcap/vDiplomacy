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

class Sengoku6Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Shimazu'=> array ('Hyuga'  => 'Army', 'Osumi (3)'=> 'Fleet', 'Satsuma (2)'  => 'Fleet'),
		'Mori'   => array ('Aki'    => 'Army', 'Iwami'    => 'Fleet', 'Nagato'       => 'Fleet'),
		'Oda'    => array ('Mino'   => 'Army', 'Echizen'  => 'Fleet', 'Mikawa (8)'   => 'Fleet',
							'Omi'   => 'Army', 'Owari (7)'=> 'Army',  'Yamashiro (6)'=> 'Army' ),
		'Hojo'   => array ('Musashi'=> 'Army', 'Shimosa'  => 'Fleet', 'Sagami'       => 'Fleet'),
		'Uesugi' => array ('Aiizu'  => 'Army', 'Echigo'   => 'Fleet', 'North Kozuke' => 'Army' ),

		'Neutral units' => array (
			'Awa'         => 'Army', 'Bungo'        => 'Army', 'Dewa'         => 'Army',
			'Harima'      => 'Army', 'Hitachi'      => 'Army', 'Hizen'        => 'Army',
			'Inaba'       => 'Army', 'Iyo'          => 'Army', 'Kai'          => 'Army',
			'Kazusa'      => 'Army', 'Kii'          => 'Army', 'Mutsu'        => 'Army',
			'Settsu (4)'  => 'Army', 'Shimotsuke'   => 'Army', 'South Shinano'=> 'Army',
			'Suruga (11)' => 'Army', 'Tosa'         => 'Army', 'Noto (9)'     => 'Army',
			'Tsushima (1)'=> 'Army', 'North Shinano'=> 'Army')
	);

}
