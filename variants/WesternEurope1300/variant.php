<?php
/*
	Copyright (C) 2018 Oliver Auth

	This file is part of the WesternEurope1300 variant for vDiplomacy

	The WesternEurope1300 variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The WesternEurope1300 variant for vDiplomacy is distributed in the hope that it will
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

class WesternEurope1300Variant extends WDVariant {
	public $id         = 145;
	public $mapID      = 145;
	public $name       = 'WesternEurope1300';
	public $fullName    ='Western Europe 1300';
	public $description ='5-player variant option to Hundred';
	public $author      ='Matthew Medeiros';
	public $adapter     ='Fake Al & Oliver Auth';
	public $version     ='1';
	public $codeVersion ='1.2.1';
	public $homepage    ='http://www.dipwiki.com/index.php?title=DipWiki';

	public $countries=array('France','England','Castile','Aragon','Burgundy');

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
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1300);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1300);
		};';
	}
}
?>