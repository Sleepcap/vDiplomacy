<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the Manifest Destiny variant for vDiplomacy

	The Manifest Destiny variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Manifest Destiny variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0:   Initial version
	1.0.1: Starting units adjusted
	1.1:   IA-map added
	1.1.1: new smallmapNames
	1.1.2: new icons for Britain
	1.2:   build anywhere did not work
	1.3:   build anywhere did not work again... (oh, well)
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class ManifestDestinyVariant extends WDVariant {
	public $id         =112;
	public $mapID      =112;
	public $name       ='ManifestDestiny';
	public $fullName    ='Manifest Destiny';
	public $description ='North America during the conquest of the Wild West.';
	public $author      ='Morg (minor changes by Enriador)';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='1.0';
	public $codeVersion ='1.4.2';
	public $homepage    ='https://playdiplomacy.com/forum/viewtopic.php?f=617&t=40302';

	public $countries=array('Britain','United States','Texas','Mexico','Spain');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = $this->name;
		$this->variantClasses['adjudicatorPreGame'] = $this->name;
		$this->variantClasses['OrderInterface']     = $this->name;

		// Build anywhere
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['processOrderBuilds'] = $this->name;
		$this->variantClasses['userOrderBuilds']    = $this->name;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1841);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1841);
		};';
	}
}

?>
