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

class MachiavelliTTRVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Neutral units' => array('Turin' => 'Army','Trent' => 'Army','Carniola' => 'Army','Croatia' => 'Army','Ragusa' => 'Army','Durazzo' => 'Army'),
		'Venice' => array('Venice' => 'Fleet','Ferrara' => 'Army','Padua' => 'Army'),
		'Papacy' => array('Rome' => 'Army','Perugia' => 'Army','Ancona' => 'Fleet'),
		'Genoa' => array('Genoa' => 'Fleet','Savoy' => 'Fleet','Modena' => 'Army'),
		'Milan' => array('Milan' => 'Army','Pavia' => 'Army','Cremona' => 'Army'),
		'Avignon' => array('Avignon' => 'Army','Marseilles' => 'Fleet','Naples' => 'Army','Bari' => 'Fleet'),
		'Florence' => array('Florence' => 'Army','Pisa' => 'Army','Piombino' => 'Army'),
		'Aragon' => array('Palermo' => 'Army','Messina' => 'Fleet','Sardinia' => 'Fleet'),
	);

}
?>
