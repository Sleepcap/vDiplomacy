<?php
/*
	Copyright (C) 2016 Oliver Auth

	This file is part of the the 1600 variant for webDiplomacy

	The 1600 variant for webDiplomacy is free software: 
	you can redistribute it and/or modify it under the terms of the GNU Affero
	General Public License as published by the Free Software Foundation, either 
	version 3 of the License, or (at your option) any later version.

    The 1600 variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Europe1600Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Denmark'=> array('Zealand' => 'Army', 'Scania' => 'Army', 'Vestlandet' => 'Fleet', 'Jutland' => 'Army'),
		'England'=> array('London' => 'Fleet', 'Ireland' => 'Fleet', 'Wales' => 'Army'),
		'France'=> array('Paris' => 'Army', 'Burgundy' => 'Army', 'Provence' => 'Army', 'Gascony' => 'Fleet'),
		'HabsburgEmpire'=> array('Austria' => 'Army', 'Bohemia' => 'Army', 'Croatia' => 'Army'),
		'OttomanEmpire'=> array('Constantinople' => 'Fleet', 'Anatolia' => 'Army', 'Egypt' => 'Army', 'Greece' => 'Army'),
		'Poland'=> array('White Russia' => 'Army', 'Warsaw' => 'Army', 'Cracow' => 'Army'),
		'Russia'=> array('Moscow' => 'Army', 'Novgorod' => 'Army', 'Voronezj' => 'Army'),
		'Spain'=> array('Castille' => 'Army', 'Granada' => 'Fleet', 'Naples' => 'Army', 'Flanders' => 'Army'),
		'Sweden'=> array('Finland' => 'Army', 'Bergslagen' => 'Army', 'Stockholm' => 'Fleet', 'Estonia' => 'Army')
	);

}