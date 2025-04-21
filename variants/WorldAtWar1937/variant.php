<?php
/*
	Copyright (C) 2018 Technostar

	This file is part of the WorldAtWar1937 variant for webDiplomacy

	The WorldAtWar1937 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The WorldAtWar1937 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0:   initial release
	1.1:   slight color change for the blue countries
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class WorldAtWar1937Variant extends WDVariant {
	public $id         = 171;
	public $mapID      = 171;
	public $name       ='WorldAtWar1937';
	public $fullName   = 'A World At War - 1937';
	public $description= 'World War II on the global stage';
	public $author     = 'michael_b';
	public $adapter    = 'Technostar';
	public $version    = '1';
	public $codeVersion= '1.1';

	public $countries=array('Britain', 'America', 'France','Germany','Italy','Portugal','Mexico','Russia','Turkey','China','Japan','Thailand');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'WorldAtWar1937';
		$this->variantClasses['adjudicatorPreGame'] = 'WorldAtWar1937';
		$this->variantClasses['panelGameBoard']     = 'WorldAtWar1937';
	}
	
	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 65;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1937);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1937);
		};';
	}
}

?>