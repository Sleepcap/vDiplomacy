<?php
/*
    Copyright (C) 2018 Oliver Auth

	This file is part of vDiplomacy.

    vDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    vDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with vDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');
	
$adjucatorPreGameTxt =
'<?php

defined(\'IN_CODE\') or die(\'This script can not be run by itself.\');

class '.$newName.'Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
	);

}
?>';

$drawMapTxt =
'<?php

defined(\'IN_CODE\') or die(\'This script can not be run by itself.\');

class '.$newName.'Variant_drawMap extends drawMap {

	protected $countryColors = array(
		0  => array(226, 198, 158), /* Neutral */
		1  => array(239, 196, 228), /* Country1 */
		2  => array(121, 175, 198)  /* Country2 */
	);

	protected function resources() {
		
		global $Variant;
		$prefix = ( ($this->smallmap) ? \'small\' : \'\');
	
		return array(
			\'map\'     =>\'variants/\'.$Variant->name.\'/resources/\'.$prefix.\'map.png\',
			\'names\'   =>\'variants/\'.$Variant->name.\'/resources/\'.$prefix.\'mapNames.png\',
			\'army\'    =>\'contrib/\'.$prefix.\'army.png\',
			\'fleet\'   =>\'contrib/\'.$prefix.\'fleet.png\',
			\'standoff\'=>\'images/icons/cross.png\'
		);
	}
}
?>';

$styleTxt =
'@CHARSET "ISO-8859-1";
';

$installTxt=
'<?php
require_once("variants/install.php");
InstallTerritory::$Territories=array();
new InstallTerritory(\'Terr1\', \'Coast\', \'Yes\', 1, 0, 0, 0, 0);
new InstallTerritory(\'Terr2\', \'Coast\', \'Yes\', 1, 0, 0, 0, 0);
InstallTerritory::$Territories[\'Terr1\']->addBorder(InstallTerritory::$Territories[\'Terr2\'],\'Yes\',\'Yes\');
InstallTerritory::runSQL($this->mapID);
InstallCache::terrJSON($this->territoriesJSONFile(),$this->mapID);
?>';

$variantTxt=
'<?php
/*
	Copyright (C) 2018 Oliver Auth

	This file is part of the '.$newName.' variant for vDiplomacy

	The '.$newName.' variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The '.$newName.' variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---
	
	Changelog:
	1.0: initial version
*/

defined(\'IN_CODE\') or die(\'This script can not be run by itself.\');

class '.$newName.'Variant extends WDVariant {
	public $id         = '.$newID.';
	public $mapID      = '.$newID.';
	public $name       = \''.$newName.'\';
	public $codeVersion= \'0.1\';
	
	public $countries=array(\'Country1\', \'Country2\');

	public function __construct() {
		parent::__construct();

		$this->variantClasses[\'drawMap\']            = $this->name;
		$this->variantClasses[\'adjudicatorPreGame\'] = $this->name;

	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + '.$newYear.');
	}

	public function turnAsDateJS() {
		return \'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + '.$newYear.');
		};\';
	}
}
?>';
