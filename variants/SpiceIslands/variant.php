<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the SpiceIslands variant for vDiplomacy

	The SpiceIslands variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The SpiceIslands variant for vDiplomacy is distributed in the hope that it will
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

class SpiceIslandsVariant extends WDVariant {
	public $id         =116;
	public $mapID      =116;
	public $name       ='SpiceIslands';
	public $fullName    ='Spice Islands';
	public $description ='A fleet-heavy variant set in Southeast Asia.';
	public $author      ='David E. Cohen';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='2.0';
	public $codeVersion ='1.0.2';
	public $homepage    ='http://diplomiscellany.tripod.com/id24.html';

	public $countries=array('Brunei','Majapahit','Tondo','Malacca','Ayutthaya','Ternate','Dai Viet');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Build anywhere
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['processOrderBuilds'] = $this->name;
		$this->variantClasses['userOrderBuilds']    = $this->name;

		// Custom units
		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['OrderInterface']     = $this->name;

	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Monsoon, " : "Spring, " ).(floor($turn/2) + 1491);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Monsoon, " : "Spring, " )+(Math.floor(turn/2) + 1491);
		};';
	}
}

?>
