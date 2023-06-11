<?php
/*
	Copyright (C) 2018 Oliver Auth

	This file is part of the Chesspolitik variant for vDiplomacy

	The Chesspolitik variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Chesspolitik variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0: initial version

	Code:
	0.8: Removal of retreat phase and unique icons
	0.9: Renamed unit names and added additional home SCs
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class ChesspolitikVariant extends WDVariant {
	public $id         = 132;
	public $mapID      = 132;
	public $name       = 'Chesspolitik';
	public $fullName    ='Chesspolitik';
	public $description ='A perfectly balanced Diplomacy variant for 4 players.';
	public $author      ='Alex Ronke';
	public $adapter     ='tobi1, Enriador & Oli';
	public $version     ='1.0';
	public $codeVersion ='0.9';
	public $homepage    ='https://nopunin10did.com/chesspolitik/';

	public $countries=array('Russians','Ottomans','Mughals','Chinese');

	public static $countryAdditionalSCs = array(
		'Russians' =>  array('A7','G7','H8'),
		'Ottomans' => array('A1','B2','B8'),
		'Mughals' => array('A1','B2','H2'),
		'Chinese' => array('G1','G7','H8'),
	);

	public static function countryAdditionalSCsByID($id) {
		return array_values(self::$countryAdditionalSCs)[$id-1];
	}

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// No retreats
		$this->variantClasses['processOrderDiplomacy'] = $this->name;

		// Custom units
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['drawMap']            = $this->name;
		
		// Custom unitnames:
		$this->variantClasses['OrderArchiv']        = $this->name;
		$this->variantClasses['OrderInterface']     = $this->name;

		// Additional Home-SCs
		$this->variantClasses['userOrderBuilds']	= $this->name;
		$this->variantClasses['processOrderBuilds']	= $this->name;
		$this->variantClasses['OrderInterface']		= $this->name;

	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1601);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1601);
		};';
	}
}
?>