<?php
/*
	Copyright (C) 2018 Oliver Auth

	This file is part of the Renaissance1453 variant for webDiplomacy

	The Renaissance1453 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Renaissance1453 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---
	
	Rules for the Renaissance1453 Variant by Earle Ratcliffe and Michael Cuffaro:
	http://www.dipwiki.com/index.php?title=Renaissance
	
	This is Version: 1.0
	
	Changelog:
	1.0: initial release
	1.0.1: supplyCenterTarget set to 18
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Renaissance1453Variant extends WDVariant {
	public $id=107;
	public $mapID=107;
	public $name='Renaissance1453';
	public $fullName='Renaissance - 1453';
	public $description='Diplomacy and war during the Renaissance.';
	public $author='Enriador (original design by Earle Ratcliffe and Michael Cuffaro)';
	public $adapter ='Enriador / Oliver Auth';
	public $homepage = 'http://www.dipwiki.com/index.php?title=Renaissance';
	public $version ='1';
	public $codeVersion ='1.0.2';

	public $countries=array('Venice', 'Spain', 'France', 'England', 'Poland-Lithuania', 'Holy Roman Empire', 'Turkey');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = 'Renaissance1453';
		$this->variantClasses['adjudicatorPreGame'] = 'Renaissance1453';
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 18;
	}
	
	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1453);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1453);
		};';
	}
}

?>