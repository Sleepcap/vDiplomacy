<?php
/*
	Copyright (C) 2018 Enriador / Oliver Auth

	This file is part of the Canton variant for webDiplomacy

	The Canton variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Canton variant for webDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0:   initial version
	1.0.1: improved unit-placement
	1.0.2: fixed a problem with thy occupation-bar
	1.2:   new unit-icons for the smallmap
	1.2.1: small colorfix for the smallmap
	1.5:   lots of visual improvements
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class CantonVariant extends WDVariant {
	public $id=108;
	public $mapID=108;
	public $name='Canton';
	public $fullName    ='Canton Diplomacy';
	public $description ='Asia at the beginning of the 20th century.';
	public $author      ='Paul Webb';
	public $adapter     ='Enriador / Oliver Auth';
	public $version     ='1';
	public $codeVersion ='1.5';
	public $homepage    ='http://www.dipwiki.com/index.php?title=Canton';

	public $countries=array('Britain', 'China', 'France', 'Holland', 'Japan', 'Russia', 'Turkey');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['adjudicatorPreGame'] = $this->name;
		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['OrderInterface']     = $this->name;
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