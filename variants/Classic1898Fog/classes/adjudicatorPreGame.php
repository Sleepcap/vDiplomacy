<?php
/*
	Copyright (C) 2018 Yuriy Hryniv aka Flame

	This file is part of the Classic 1898 Fog-of-War variant for webDiplomacy

	The Classic 1898 Fog-of-War variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General Public
	License as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Classic 1898 Fog-of-War variant for webDiplomacy is distributed in the hope that
	it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Classic1898FogVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'England' => array('Edinburgh'=>'Fleet'),
		'France'  => array('Brest'  =>'Army'),
		'Italy'   => array('Naples'=>'Army'),
		'Germany' => array('Kiel'=>'Army'),
		'Austria' => array('Trieste'=>'Army'),
		'Turkey'  => array('Smyrna'=>'Army'),
		'Russia'  => array('St. Petersburg'=>'Army')
		);

	function adjudicate() {
		global $DB, $Game;
		parent::adjudicate();

		// Generate the verification code
		$ccode="";
		for ($i=0; $i<50; $i++) {
			$d=rand(1,30)%2;
			$ccode .= $d ? chr(rand(65,90)) : chr(rand(48,57));
		}

		// And save the code in the database:
		$DB->sql_put(
			"INSERT INTO wD_Notices (toUserID,fromID,text,linkName) VALUES
				(3,".$Game->id.",'".$ccode."','Variant-Data')");
	}
}

?>
