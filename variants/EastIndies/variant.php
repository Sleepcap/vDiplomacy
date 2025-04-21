<?php
/*
	Copyright (C) 2018 Oliver Auth

	This file is part of the EastIndies variant for vDiplomacy

	The EastIndies variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The EastIndies variant for vDiplomacy is distributed in the hope that it will
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

class EastIndiesVariant extends WDVariant {
	public $id         = 131;
	public $mapID      = 131;
	public $name       = 'EastIndies';
	public $fullName    ='East Indies';
	public $description ='A combination of the Maharajah and Spice Islands variants.';
	public $author      ='David E. Cohen';
	public $adapter     ='Tobias Florin, Enriador & Oliver Auth';
	public $version     ='1.0';
	public $codeVersion ='1.1';
	public $homepage    ='http://diplomiscellany.tripod.com/id23.html';

	public $countries=array('Bahmana','Brunei','Delhi','Gondwana','Majapahit','Malacca','Mughalistan','Persia','Rajputana','Ayutthaya','Ternate','Tondo','Dai Viet','Vijayanagar');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Build anywhere
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['userOrderBuilds']    = $this->name;
		$this->variantClasses['processOrderBuilds'] = $this->name;

		// Zoom-Map
		$this->variantClasses['panelGameBoard']     = $this->name;
		$this->variantClasses['drawMap']            = $this->name;

	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Monsoon, " : "Spring, " ).(floor($turn/2) + 1501);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Monsoon, " : "Spring, " )+(Math.floor(turn/2) + 1501);
		};';
	}
}
?>