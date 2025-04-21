<?php
/*
	Copyright (C) 2020 alifeee

	This file is part of the Scottish_Clan_Wars variant for vDiplomacy

	The Scottish_Clan_Wars variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Scottish_Clan_Wars variant for vDiplomacy is distributed in the hope that it will
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

class Scottish_Clan_WarsVariant extends WDVariant {
	public $id         = 141;
	public $mapID      = 141;
	public $name       = 'Scottish_Clan_Wars';
	public $fullName    ='Scottish Clan Wars';
	public $description ='The clans of Scotland fight for control of the peninsula.';
	public $author      ='alifeee';
	public $adapter     ='alifeee';
	public $version     ='1';
	public $codeVersion ='1.0.1';

	public $countries=array('Edinburgh','Glasgow','Dundee','Aberdeen','Orkney Islands','Outer Hebrides','Kintyre');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Custom units
		$this->variantClasses['OrderInterface']     = $this->name;

	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1701);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1701);
		};';
	}
}
?>