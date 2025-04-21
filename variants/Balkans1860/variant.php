<?php
/*
	Copyright (C) 2017 Oliver Auth

	This file is part of the Balkans 1860 variant for webDiplomacy

	The Balkans 1860 variant for webDiplomacy is free software:
	you can redistribute it and/or modify it under the terms of the GNU Affero
	General Public License as published by the Free Software Foundation, either
	version 3 of the License, or (at your option) any later version.

	The Balkans 1860 variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
	
	If you have questions or suggestions send me a mail: Oliver.Auth@rhoen.de

	---

	Changelog:
	1.0  : initial version
	1.0.1: Target-SCs set to 19
	1.1:   Missing SC on the largemap added
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Balkans1860Variant extends WDVariant {
	public $id=103;
	public $mapID=103;
	public $name='Balkans1860';
	public $fullName='Balkans 1860';
	public $description='The variant is set in the Balkans region, (almost) historically accurate to the year 1860.';
	public $author='Benjamin Hester ';
	public $adapter='Oliver Auth';
	public $codeVersion='1.1';
	public $homepage='http://dipwiki.com/index.php?title=Balkans1860';
	
	public $countries=array('Austria-Hungary', 'Bulgaria', 'Greece', 'Italy', 'Ottomans', 'Romania', 'Serbia');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = 'Balkans1860';
		$this->variantClasses['adjudicatorPreGame'] = 'Balkans1860';
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 19;
	}


	public function turnAsDate($turn) {
		if ( $turn==-1 ) return l_t("Pre-game");
		else return ( $turn % 2 ? l_t("Autumn").", " : l_t("Spring").", " ).(floor($turn/2) + 1860);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return l_t("Pre-game");
			else return ( turn%2 ? l_t("Autumn")+", " : l_t("Spring")+", " )+(Math.floor(turn/2) + 1860);
		};';
	}
}

?>