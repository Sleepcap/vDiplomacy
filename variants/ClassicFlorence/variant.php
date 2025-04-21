<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of vDiplomacy.

    vDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    vDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with vDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('IN_CODE') or die('This script can not be run by itself.');

class ClassicFlorenceVariant extends WDVariant {
	public $id=121;
	public $mapID=121;
	public $name='ClassicFlorence';
	public $fullName    ='Classic - Florence';
	public $description ='The classic map of Diplomacy, with key changes in Italy.';
	public $author      ='Enriador';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='1';
	public $codeVersion ='1.0';
	public $homepage    ='https://www.reddit.com/r/diplomacy/comments/8iec9m/newvariantflorencediplomacy/';

	public $countries=array('England', 'France', 'Italy', 'Germany', 'Austria', 'Turkey', 'Russia');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return l_t("Pre-game");
		else return ( $turn % 2 ? l_t("Autumn").", " : l_t("Spring").", " ).(floor($turn/2) + 1901);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return l_t("Pre-game");
			else return ( turn%2 ? l_t("Autumn")+", " : l_t("Spring")+", " )+(Math.floor(turn/2) + 1901);
		};';
	}
}

?>