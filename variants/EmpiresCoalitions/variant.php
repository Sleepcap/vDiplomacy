<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the EmpiresCoalitions variant for vDiplomacy

	The EmpiresCoalitions variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The EmpiresCoalitions variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0: initial version
	1.1: recolored some territories on the maps
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class EmpiresCoalitionsVariant extends WDVariant {
	public $id          = 113;
	public $mapID       = 113;
	public $name        ='EmpiresCoalitions';
	public $fullName    ='1800 - Empires and Coalitions';
	public $description ='Europe in 1800, after the rise of Napoleon Bonaparte.';
	public $author      ='VaeVictis (unit icons by Firehawk)';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='1';
	public $codeVersion ='1.1.3';
	public $homepage    ='https://www.playdiplomacy.com/forum/viewtopic.php?f=253&t=41242';

	public $countries=array('Austria','Britain','Denmark','France','Ottoman Empire','Prussia','Russia','Sicily','Spain');

	public function __construct() {
		parent::__construct();

		// Setup
		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Custom units
		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['OrderInterface']     = $this->name;

		// Allow for some coasts to convoy
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['userOrderDiplomacy'] = $this->name;

	}

	/* Coasts that allow convoying:  Gibraltar(58)
	*/
	public $convoyCoasts = array ('57');


	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1800);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1800);
		};';
	}
}

?>
