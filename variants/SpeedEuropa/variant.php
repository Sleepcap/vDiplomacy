<?php
/*
    Copyright (C) 2004-2009 Kestas J. Kuliukas

	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.

    1.0 - Initial release
    1.1 - Territory links and map fixes
    1.2 - Interactive map added
    1.3 - Large map added and small map fix
	1.3.1 Border issue fixed
 */

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * Speed Europa variant information
 */
class SpeedEuropaVariant extends WDVariant {
	public $id=253;
	public $mapID=253;
	public $name='SpeedEuropa';
	public $fullName='Speed Europa';
	public $description='A variant map of Europe, with an emphasis on rapid conflict.';
	public $author='John Walko';
        public $adapter='"Triskelli" and Yuriy Hryniv aka Flame';
	public $version    ='1';
	public $codeVersion='1.3.1';
	public $homepage   ='http://www.dipwiki.com/index.php?title=Speed_Europa';

	public $countries=array('England', 'France', 'Italy', 'Germany', 'Austria', 'Turkey', 'Russia');

	public function __construct() {
		parent::__construct();

		// drawMap extended for country-colors and loading the classic map images
		$this->variantClasses['drawMap'] = 'SpeedEuropa';

		/*
		 * adjudicatorPreGame extended to add fair country-balancing, replacing the
		 * default random allocation for classic map games.
		 */
		$this->variantClasses['adjudicatorPreGame'] = 'SpeedEuropa';
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 18;
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