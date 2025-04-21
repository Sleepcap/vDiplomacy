<?php
/*
	Copyright (C) 2020 Jared Kish

	This file is part of the A_Modern_Europe variant for vDiplomacy

	The A_Modern_Europe variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The A_Modern_Europe variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0: initial version
	1.0.1: Minor bugfixes.
	1.0.2: Interactive map bugfixes
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class A_Modern_EuropeVariant extends WDVariant {
	public $id         = 136;
	public $mapID      = 136;
	public $name       = 'A_Modern_Europe';
	public $fullName    ='A Modern Europe';
	public $description ='Europe in the 21st century.';
	public $author      ='Jared Kish (Kurt)';
	public $adapter     ='Jared Kish (Kurt) and Oliver Auth';
	public $version     ='1.0';
	public $codeVersion ='1.0.2';
	public $homepage    ='';

	public $countries=array('Czechia','Finland','France','Georgia','Germany','Greece','Ireland','Italy','Lithuania','Netherlands','Poland','Portugal','Romania','Russia','Serbia','Spain','Sweden','Turkey','Ukraine','United Kingdom');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Build anywhere
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['processOrderBuilds'] = $this->name;
		$this->variantClasses['userOrderBuilds']    = $this->name;

		// Transform command
		$this->variantClasses['drawMap']               = $this->name;
		$this->variantClasses['OrderArchiv']           = $this->name;
		$this->variantClasses['OrderInterface']        = $this->name;
		$this->variantClasses['processOrderDiplomacy'] = $this->name;
		$this->variantClasses['userOrderDiplomacy']    = $this->name;

		// Custom start
		$this->variantClasses['adjudicatorPreGame'] = $this->name;
		$this->variantClasses['processOrderBuilds'] = $this->name;
		$this->variantClasses['processGame']        = $this->name;

		// Zoom-Map
		$this->variantClasses['panelGameBoard']     = $this->name;
		$this->variantClasses['drawMap']            = $this->name;

		// Write the countryname in global chat
		$this->variantClasses['Chatbox']            = $this->name;

		// Split Home-view after 9 countries for better readability:
		$this->variantClasses['panelMembersHome']   = $this->name;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 2020);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 2020);
		};';
	}
}
?>