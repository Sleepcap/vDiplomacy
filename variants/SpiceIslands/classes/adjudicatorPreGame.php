<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the SpiceIslands variant for vDiplomacy

	The SpiceIslands variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The SpiceIslands variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class SpiceIslandsVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Dai Viet' => array('Hanoi' => 'Army','Haiphong' => 'Army','Faifo' => 'Fleet'),
		'Ternate' => array('Halmahera' => 'Fleet','Buru' => 'Fleet','Seram' => 'Fleet'),
		'Ayutthaya' => array('Ayutthaya' => 'Army','Roi Et' => 'Army','Dawei (West Coast)' => 'Fleet'),
		'Malacca' => array('Malacca' => 'Fleet','Pahang' => 'Fleet','Riau' => 'Fleet'),
		'Tondo' => array('Tondo' => 'Fleet','Kasiguran' => 'Fleet','Namayan (South Coast)' => 'Fleet'),
		'Majapahit' => array('Pajajaran' => 'Fleet','Trowulan' => 'Fleet','Javadvipa (South Coast)' => 'Fleet'),
		'Brunei' => array('Brunei' => 'Fleet','Tunku' => 'Fleet','Palawan' => 'Fleet'),
	);

}
?>
