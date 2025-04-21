<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Crusades1201 variant for vDiplomacy

	The Crusades1201 variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Crusades1201 variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Crusades1201Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Denmark' => array('Denmark' => 'Fleet','Scania' => 'Fleet'),
		'Rus' => array('Novgorod' => 'Fleet','Kiev' => 'Army'),
		'Ayyubid Sultanate' => array('Egypt' => 'Fleet','Jerusalem' => 'Army'),
		'Byzantine Empire' => array('Constantinople' => 'Army','Athens' => 'Fleet'),
		'Hungary' => array('Buda' => 'Army','Pest' => 'Army'),
		'Papacy' => array('Rome' => 'Army','Ravenna' => 'Army'),
		'Holy Roman Empire' => array('Hesse' => 'Army','Bavaria' => 'Army'),
		'France' => array('Paris' => 'Army','Auvergne' => 'Army'),
		'England' => array('England' => 'Fleet','Guyenne' => 'Army'),
		'Almohad Caliphate' => array('Cordoba' => 'Army','Tunis' => 'Fleet'),
		'Castille' => array('Toledo' => 'Army','Valladolid' => 'Fleet')
	);
}
?>
