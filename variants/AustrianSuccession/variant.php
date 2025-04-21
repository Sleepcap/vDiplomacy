<?php
/*
	Copyright (C) 2015 Oliver Auth / Safari

	This file is part of the War of Austrian Succession variant for webDiplomacy

	The War of Austrian Succession variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The War of Austrian Succession variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0:   initial version
	1.0.2: Coast-Childs have no SC
	1.0.4: Interactive map bugfixes
	
*/
defined('IN_CODE') or die('This script can not be run by itself.');

class AustrianSuccessionVariant extends WDVariant {
	public $id         =117;
	public $mapID      =117;
	public $name       ='AustrianSuccession';
	public $fullName   ='War of Austrian Succession';
	public $description='';
	public $author     ='Nick Wactor';
	public $adapter    ='Safari';
	public $homepage   ='http://www.diplomail.ru';
	public $version    ='1';
	public $codeVersion='1.0.4';

	public $countries=array('Austria', 'Bavaria', 'England', 'France', 'Ottoman Empire', 'Piedmont-Sardinia', 'Prussia', 'Russia', 'Spain');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = 'AustrianSuccession';
		$this->variantClasses['adjudicatorPreGame'] = 'AustrianSuccession';
		$this->variantClasses['OrderInterface']     = 'AustrianSuccession';
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1740);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1740);
		};';
	}
}

?>