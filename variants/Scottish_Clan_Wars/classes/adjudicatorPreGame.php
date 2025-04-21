<?php
/*
	Copyright (C) 2020 alifeee

	This file is part of the Scottish_Clan_Wars variant for vDiplomacy

	The Scottish_Clan_Wars variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Scottish_Clan_Wars variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Scottish_Clan_WarsVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Kintyre' => array('Kilmartin' => 'Army','Islay' => 'Fleet','Campbeltown' => 'Fleet'),
		'Outer Hebrides' => array('Isle of Harris' => 'Fleet','Uist Isles' => 'Fleet'),
		'Orkney Islands' => array('John o Groats' => 'Fleet','Orkney' => 'Fleet'),
		'Edinburgh' => array('Edinburgh' => 'Army','Kirkcaldy' => 'Army','Dunbar' => 'Fleet'),
		'Glasgow' => array('Glasgow' => 'Army','Largs' => 'Fleet','Dumbarton' => 'Army'),
		'Aberdeen' => array('Aberdeen' => 'Army','Dufftown' => 'Army','Peterhead' => 'Fleet'),
		'Dundee' => array('Dundee' => 'Army','St. Andrews' => 'Army','Arbroath' => 'Fleet','Forfar' => 'Army'),
	);

}
?>