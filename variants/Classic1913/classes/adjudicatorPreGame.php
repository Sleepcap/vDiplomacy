<?php
/*
	Copyright (C) 2018 Enriador / Oliver Auth

	This file is part of the Classic1913 variant for webDiplomacy

	The Classic1913 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Classic1913 variant for webDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/
defined('IN_CODE') or die('This script can not be run by itself.');

class Classic1913Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'England' => array('Egypt' =>'Fleet', 'London' =>'Fleet', 'Edinburgh'     =>'Fleet', 'Liverpool'=>'Army' ),
		'France'  => array('Brest' =>'Fleet', 'Paris'  =>'Army',  'Marseilles'    =>'Army',  'Algeria'  =>'Army' ),
		'Italy'   => array('Naples'=>'Fleet', 'Milan'  =>'Army',  'Rome'          =>'Army'                       ),
		'Germany' => array('Kiel'  =>'Fleet', 'Berlin' =>'Army',  'Munich'        =>'Army',  'Cologne'   =>'Army'),
		'Austria' => array('Vienna'=>'Army',  'Trieste'=>'Fleet', 'Budapest'      =>'Army'                       ),
		'Turkey'  => array('Smyrna'=>'Army',  'Ankara' =>'Fleet', 'Constantinople'=>'Army'                       ),
		'Russia'  => array('Moscow'=>'Army',  'Warsaw' =>'Army',  'Sevastopol'    =>'Fleet', 'St. Petersburg (South Coast)'=>'Fleet')
	);

	/*
	 * Egypt (43) -> Set to England (1) / Algeria (38) -> Set to France (2)
	 * They are initally set to neutral, so they are no home-SCs for the bild phase.
	 * Now assign them to England and France
	 */
	protected function assignTerritories() {
		global $DB, $Game;
		
		
		$DB->sql_put(
			"INSERT INTO wD_TerrStatus
				( gameID, countryID, terrID )
			VALUES 
				(".$Game->id.", 1, 43),
				(".$Game->id.", 2, 38)"
		);
						
		parent::assignTerritories();
	}

}
