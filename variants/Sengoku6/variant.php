<?php
/*
	Copyright (C) 2017 Oliver Auth

	This file is part of the Sengoku6 variant for webDiplomacy

	The Sengoku6 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Sengoku6 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0:   initial release

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Sengoku6Variant extends Sengoku5Variant {
	public $id         =100;
	public $mapID      =100;
	public $name       ='Sengoku6';
	public $fullName   ='Sengoku: Nagashino (V6)';
	public $description='The Sengoku: Nagashino Variant is a historical transplant to medieval Japan.';
	public $author     ='Benjamin Hester';
	public $adapter    ='Oliver Auth';
	public $version    ='6';
	public $codeVersion='1.1.2';

	public $countries=array('Shimazu','Mori','Oda','Hojo','Uesugi');

	public function __construct() {
		parent::__construct();

		// Setup the game
		$this->variantClasses['adjudicatorPreGame'] = 'Sengoku6';
		$this->variantClasses['drawMap']            = 'Sengoku6';

	}
	
	// New SupplyCenter target
	public function initialize()
	{
		parent::initialize();
		$this->supplyCenterTarget = 20;
	}	
	
	// Needed for Neutral units code
	public function countryID($countryName)
	{
		if ($countryName == 'Neutral units')
			return count($this->countries)+1;		
		return parent::countryID($countryName);
	}
	
	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1570);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1570);
		};';
	}
}
