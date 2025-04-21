<?php
/*
	Copyright (C) 2018 Yuriy Hryniv aka Flame

	This file is part of the WesternWorld_901 variant for webDiplomacy

	The WesternWorld_901 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The WesternWorld_901 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0: initial release
	1.1: missing OrderArchiv (needed for neutral player)
	1.2: Build anywhere now working properly (fixed)
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class WesternWorld_901Variant extends WDVariant {
	public $id         =127;
	public $mapID      =127;
	public $name       ='WesternWorld_901';
	public $fullName   ='Western World 901';
	public $description='Conquer the Western World of 901';
	public $author     ='David E. Cohen';
	public $adapter    ='Yuriy Hryniv aka Flame';
	public $version    ='4.0';
	public $codeVersion='1.2';
	public $homepage  ='http://diplomiscellany.tripod.com/id16.html';

	public $countries=array('Arabia','Byzantinum','Denmark','Egypt','France','Germany','Khazaria','Rus','Spain');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'WesternWorld_901';
		$this->variantClasses['adjudicatorPreGame'] = 'WesternWorld_901';

		//buildanywhere
		$this->variantClasses['OrderInterface']     = 'WesternWorld_901';
		$this->variantClasses['userOrderBuilds']    = 'WesternWorld_901';
		$this->variantClasses['processOrderBuilds'] = 'WesternWorld_901';

		// Zoom-Map
		$this->variantClasses['panelGameBoard']     = 'WesternWorld_901';
		$this->variantClasses['drawMap']            = 'WesternWorld_901';

		// Neutral units:
		$this->variantClasses['OrderArchiv']        = 'WesternWorld_901';
		$this->variantClasses['processGame']        = 'WesternWorld_901';
		$this->variantClasses['processMembers']     = 'WesternWorld_901';

	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 33;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 901);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 901);
		};';
	}

	public function countryID($countryName)
	{
		if ($countryName == 'Neutral units')
			return count($this->countries)+1;

		return parent::countryID($countryName);
	}
}

?>