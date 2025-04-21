<?php
/*
    Copyright (C) 2004-2009 Kestas J. Kuliukas

	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('IN_CODE') or die('This script can not be run by itself.');

class Classic1898Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'England' => array(
					'Edinburgh'=>'Fleet'
				),
		'France' => array(
					'Brest'=>'Army'
				),
		'Italy' => array(
					'Naples'=>'Army'
				),
		'Germany' => array(
					'Kiel'=>'Army'
				),
		'Austria' => array(
					'Trieste'=>'Army'
				),
		'Turkey' => array(
					'Smyrna'=>'Army'
				),
		'Russia' => array(
					'St. Petersburg'=>'Army'
				)
		);

}