<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Machiavelli variant for vDiplomacy

	The Machiavelli variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Machiavelli variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class MachiavelliVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Venice' => array('Venice' => 'Fleet','Dalmatia' => 'Fleet','Padua' => 'Army','Treviso' => 'Army'),
		'Turkey' => array('Tunis' => 'Fleet','Durazzo' => 'Fleet','Albania' => 'Army'),
		'Papacy' => array('Rome' => 'Army','Bologna' => 'Army','Perugia' => 'Army','Ancona' => 'Fleet'),
		'Naples' => array('Messina' => 'Army','Palermo' => 'Fleet','Naples' => 'Fleet','Bari' => 'Army'),
		'Florence' => array('Florence' => 'Army','Pisa' => 'Fleet','Arezzo' => 'Army'),
		'France' => array('Marseilles' => 'Fleet','Swiss' => 'Army','Avignon' => 'Army'),
		'Milan' => array('Milan' => 'Army','Cremona' => 'Army','Pavia' => 'Army'),
		'Austria' => array('Tyrolia' => 'Army','Austria' => 'Army','Hungary' => 'Army'),
	);

}
?>
