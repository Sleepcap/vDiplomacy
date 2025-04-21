<?php
/*
	Copyright (C) 2020 Oliver Auth

	This file is part of the TiglathPileser variant for vDiplomacy

	The TiglathPileser variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The TiglathPileser variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class TiglathPileserVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Saba' => array('Qana&#039;' => 'Fleet','Sirwah' => 'Army','Ma&#039;rib' => 'Army'),
		'Phrygia' => array('Midaeion' => 'Army','Gordion' => 'Army','Kelainai' => 'Fleet'),
		'Egypt' => array('Tanis' => 'Army','Memphis' => 'Fleet','Heliopolis' => 'Army'),
		'Elam' => array('Susa' => 'Army','Hidalu' => 'Army','Anshan' => 'Fleet'),
		'Kush' => array('Thebes' => 'Army','Napata (East Coast)' => 'Fleet','Meroe' => 'Army'),
		'Urartu' => array('Arzashkun' => 'Army','Diauehi' => 'Army','Tushpa' => 'Army'),
		'Babylonia ' => array('Babylon' => 'Army','Ur' => 'Fleet','Lagash' => 'Army'),
		'Assyria' => array('Assur' => 'Army','Kalhu' => 'Army','Nineveh' => 'Army'),
	);

}
?>