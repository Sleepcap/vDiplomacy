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

	---

	Changelog:
	1.0: initial version
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class TiglathPileserVariant extends WDVariant {
	public $id         = 137;
	public $mapID      = 137;
	public $name       = 'TiglathPileser';
	public $fullName    ='Tiglath-Pileser';
	public $description ='The Iron Age civilizations of the Fertile Crescent fight for dominance in the eighth century BC.';
	public $author      ='Fake Al';
	public $adapter     ='Fake Al & Oliver Auth';
	public $version     ='2.1';
	public $codeVersion ='1.0';
	public $homepage    ='https://vdiplomacy.com/dev.php?tab=Preview&variantID=137';

	public $countries=array('Assyria','Babylonia ','Urartu','Kush','Elam','Egypt','Phrygia','Saba');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Build anywhere
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['processOrderBuilds'] = $this->name;
		$this->variantClasses['userOrderBuilds']    = $this->name;

	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		return ( $turn % 2 ? "Autumn, " : "Spring, " ).(-1*(floor($turn/2) - 745))." BC.";
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			return ( turn%2 ? "Autumn, " : "Spring, " )+(-1*(Math.floor(turn/2) - 745)) +" BC.";
		};';
	}
}
?>