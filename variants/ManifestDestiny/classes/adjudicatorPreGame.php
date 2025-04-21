<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Manifest Destiny variant for vDiplomacy

	The Manifest Destiny variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Manifest Destiny variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class ManifestDestinyVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Britain' => array('Toronto' => 'Army','Montreal' => 'Army','Ontario' => 'Army'),
		'Mexico' => array('Guadalajara' => 'Army','Veracruz' => 'Army','Durango' => 'Army'),
		'Texas' => array('Austin' => 'Army','Houston' => 'Fleet','Dallas' => 'Army'),
		'Spain' => array('Havana' => 'Fleet','Santo Domingo' => 'Fleet','Mid-Pacific Ocean' => 'Fleet'),
		'United States' => array('New York' => 'Army','Ohio' => 'Army','Virginia' => 'Army','Carolina' => 'Army'),
	);

}
?>
