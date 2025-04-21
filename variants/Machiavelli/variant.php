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

	---
	
	Changelog:
	1.0: initial version
	1.1: bugfix with the custom units code and the buildanywhere
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class MachiavelliVariant extends WDVariant {
	public $id          =109;
	public $mapID       =109;
	public $name        ='Machiavelli';
	public $fullName    ='Machiavelli - The Balance of Power';
	public $description ='Eight nations fight over control of Italy.';
	public $author      ='Andrew Jameson (original design by S. Craig Taylor and James B. Wood)';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='1';
	public $codeVersion ='1.1.1';
	public $homepage    ='http://www.dipwiki.com/?title=Machiavelli';

	public $countries=array('Austria','Florence','France','Milan','Naples','Papacy','Turkey','Venice');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Custom icon units
		$this->variantClasses['OrderInterface']     = $this->name;
		
		// Build anywhere
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['processOrderBuilds'] = $this->name;
		$this->variantClasses['userOrderBuilds']    = $this->name;

	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1454);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1454);
		};';
	}
}

?>
