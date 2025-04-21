<?php
/*
	Copyright (C) 2018 Oliver Auth

	This file is part of the Renaissance1453 variant for webDiplomacy

	The Renaissance1453 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Renaissance1453 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Renaissance1453Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Venice'           => array('Venice'  =>'Army', 'Trieste'   =>'Army' , 'Athens'              =>'Fleet'),
		'Spain'            => array('Lisbon'  =>'Army', 'Naples'    =>'Fleet', 'Madrid (South Coast)'=>'Fleet'),
		'France'           => array('Paris'   =>'Army', 'Marseilles'=>'Army' , 'Brussels'            =>'Fleet'),
		'England'          => array('Chester' =>'Army', 'London'    =>'Fleet', 'Brest'               =>'Fleet'),
		'Poland-Lithuania' => array('Novgorod'=>'Army', 'Warsaw'    =>'Army' , 'Belgorod'            =>'Fleet'),
		'Holy Roman Empire'=> array('Vienna'  =>'Army', 'Munich'    =>'Army' , 'Berlin'              =>'Fleet'),
		'Turkey'           => array('Ankara'  =>'Army', 'Smyrna'    =>'Fleet', 'Sofia (North Coast)' =>'Fleet')
	);

}
