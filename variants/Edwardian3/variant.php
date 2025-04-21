<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Edwardian variant for vDiplomacy

	The Edwardian variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Edwardian variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0: initial version
	1.1: new IA_smallmap
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Edwardian3Variant extends WDVariant {
	public $id         =130;
	public $mapID      =130;
	public $name       ='Edwardian3';
	public $fullName    ='Edwardian - 3rd Edition';
	public $description ='Vie for mastery of the Continent in the era of Edwardian Europe.';
	public $author      ='VaeVictis';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='1';
	public $codeVersion ='1.0.7';
	public $homepage    ='https://www.playdiplomacy.com/forum/viewtopic.php?f=413&t=57961&sid=1cd859259c37734e1f56b588735d3bec';

	public $countries=array('Austria-Hungary','Britain','France','Germany','Italy','Russia','Turkey');

	public function __construct() {
		parent::__construct();

		// Setup
		$this->variantClasses['adjudicatorPreGame'] = $this->name;
		$this->variantClasses['drawMap']            = $this->name;

		// Custom units
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['drawMap']            = $this->name;

		// Allow for some coasts to convoy
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['userOrderDiplomacy'] = $this->name;
	}

	// Coasts that allow convoying => Gibraltar(72)
	public $convoyCoasts = array ('72');

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 20;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1901);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1901);
		};';
	}
}

?>
