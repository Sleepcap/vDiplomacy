<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the EmpiresCoalitions variant for vDiplomacy

	The EmpiresCoalitions variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The EmpiresCoalitions variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class EmpiresCoalitionsVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Spain' => array('Madrid' => 'Fleet','Valencia' => 'Fleet'),
		'Sicily' => array('Palermo' => 'Fleet','Naples' => 'Army'),
		'Ottoman Empire' => array('Constantinople' => 'Army','Angora' => 'Fleet'),
		'France' => array('Paris' => 'Army','Brest' => 'Army','Lyon' => 'Army','Marseilles' => 'Fleet'),
		'Denmark' => array('Copenhagen' => 'Fleet','Christiania' => 'Fleet'),
		'Austria' => array('Vienna' => 'Army','Venice' => 'Army','Budapest' => 'Army'),
		'Britain' => array('London' => 'Fleet','Edinburgh' => 'Fleet','Gibraltar' => 'Fleet','Hanover' => 'Army'),
		'Prussia' => array('Berlin' => 'Army','Breslau' => 'Army','Konigsberg' => 'Army'),
		'Russia' => array('St. Petersburg (South Coast)' => 'Fleet','Sevastopol' => 'Fleet','Moscow' => 'Army','Kiev' => 'Army'),
	);
	
	protected function assignTerritories()
	{
		global $DB, $Game;

		parent::assignTerritories();
		
		// Iceland, Sweden, Egypt, Portigal and Papal States are neutral.
 		$DB->sql_put("
			UPDATE wD_TerrStatus 
				SET countryID = 0 
			WHERE gameID=".$Game->id." AND terrID IN ( 1, 8, 52, 63, 70 );" 
		);
	
	}

	
}
?>
