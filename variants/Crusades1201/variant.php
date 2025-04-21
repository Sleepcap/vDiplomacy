<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Crusades1201 variant for vDiplomacy

	The Crusades1201 variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Crusades1201 variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0: initial version
	1.1: No border and transparent background
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Crusades1201Variant extends WDVariant {
	public $id         =114;
	public $mapID      =114;
	public $name       ='Crusades1201';
	public $fullName    ='Crusades 1201';
	public $description ='Europe at the height of the Middle Ages.';
	public $author      ='Enriador (original design by Tommy Larsson)';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='1.0';
	public $codeVersion ='1.1.3';
	public $homepage    ='http://www.variantbank.org/results/rules/c/crusades.htm';

	public $countries=array('Castille','Almohad Caliphate','England','France','Holy Roman Empire','Denmark','Papacy','Hungary','Rus','Byzantine Empire','Ayyubid Sultanate');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Build anywhere
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['processOrderBuilds'] = $this->name;
		$this->variantClasses['userOrderBuilds']    = $this->name;

	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 14;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1201);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1201);
		};';
	}
}

?>
