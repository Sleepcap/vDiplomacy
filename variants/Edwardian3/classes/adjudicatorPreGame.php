<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Edwardian variant for vDiplomacy

	The Edwardian variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Edwardian variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Edwardian3Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'France' => array('Marseilles' => 'Army','Brest' => 'Fleet','Lyon' => 'Army','Algiers' => 'Army','Paris' => 'Army'),
		'Germany' => array('Kiel' => 'Fleet','Essen' => 'Army','Berlin' => 'Army','Frankfurt' => 'Army','Dresden' => 'Army','Stuttgart' => 'Army'),
		'Britain' => array('London' => 'Fleet','Liverpool' => 'Army','Cairo' => 'Fleet','Gibraltar' => 'Fleet','Edinburgh' => 'Fleet'),
		'Austria-Hungary' => array('Vienna' => 'Army','Budapest' => 'Army','Trieste' => 'Fleet','Bosnia' => 'Army'),
		'Russia' => array('St. Petersburg' => 'Army','Sevastopol' => 'Fleet','Kiev' => 'Army','Warsaw' => 'Army','Moscow' => 'Army','Riga' => 'Fleet'),
		'Turkey' => array('Macedonia' => 'Army','Constantinople' => 'Fleet','Erzurum' => 'Army','Damascus' => 'Army'),
		'Italy' => array('Rome' => 'Army','Apulia' => 'Fleet','Milan' => 'Army','Naples' => 'Fleet'),
	);

}
?>
