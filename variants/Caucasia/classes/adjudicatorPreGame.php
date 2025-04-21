<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Caucasia variant for vDiplomacy

	The Caucasia variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Caucasia variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class CaucasiaVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Azerbaijan' => array('Ali Bayramli' => 'Army','Gyandzja' => 'Army','Baku' => 'Fleet'),
		'Russia' => array('Stavropol' => 'Army','Krasnodar' => 'Fleet','Pjatigorsk' => 'Fleet'),
		'Georgia' => array('Kutaisi' => 'Army','Lagodekhi' => 'Army','Tbilisi' => 'Army','Tskhinvali' => 'Army'),
		'Chechnya' => array('Grozny' => 'Army','Tebulosmta' => 'Army','Vedeno' => 'Army'),
		'Armenia' => array('Jerevan' => 'Army','Kirovakan' => 'Army','Martuni' => 'Army'),
	);

}
?>
