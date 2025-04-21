<?php
/*
	Copyright (C) 2016 Oliver Auth

	This file is part of the 1600 variant for webDiplomacy

	The 1600 variant for webDiplomacy is free software:
	you can redistribute it and/or modify it under the terms of the GNU Affero
	General Public License as published by the Free Software Foundation, either
	version 3 of the License, or (at your option) any later version.

	The 1600 variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Rules for the the 1600 Variant by Tommy Larsson
	http://www.variantbank.org/results/rules/1/1600.htm

	Changelog:
	1.0:   initial version
	1.2:   problem with the custom icons in the rulespress-mode fixed.

*/

defined('IN_CODE') or die('This script can not be run by itself.');


class Europe1600Variant extends WDVariant {
	public $id         = 97;
	public $mapID      = 97;
	public $name       = 'Europe1600';
	public $fullName   = '1600';
	public $description= 'Europe in 1600';
	public $adapter    = 'Oliver Auth';
	public $author     = 'Tommy Larsson';
	public $version    = '2.8';
	public $homepage   ='http://www.variantbank.org/results/rules/1/1600.htm';
	public $codeVersion= '1.2';

	public $countries=array('Denmark','England','France','HabsburgEmpire','OttomanEmpire','Poland','Russia','Spain','Sweden');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = 'Europe1600';
		$this->variantClasses['adjudicatorPreGame'] = 'Europe1600';
		$this->variantClasses['OrderInterface']     = 'Europe1600';
	}
	
	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 27;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return l_t("Pre-game");
		else return ( $turn % 2 ? l_t("Autumn").", " : l_t("Spring").", " ).(floor($turn/2) + 1601);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return l_t("Pre-game");
			else return ( turn%2 ? l_t("Autumn")+", " : l_t("Spring")+", " )+(Math.floor(turn/2) + 1601);
		};';
	}
}

?>