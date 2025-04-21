<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Caucasia variant for vDiplomacy

	The Caucasia variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Caucasia variant for vDiplomacy is distributed in the hope that it will
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

class CaucasiaVariant extends WDVariant {
	public $id         =118;
	public $mapID      =118;
	public $name       ='Caucasia';
	public $fullName    ='Caucasia';
	public $description ='Post-Soviet warfare in the Caucasus.';
	public $author      ='Christian Dreyer';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='1';
	public $codeVersion ='1.0';
	public $homepage    ='http://www.variantbank.org/results/rules/c/caucasia.htm';

	public $countries=array('Armenia','Chechnya','Georgia','Russia','Azerbaijan');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1999);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1999);
		};';
	}
}

?>
