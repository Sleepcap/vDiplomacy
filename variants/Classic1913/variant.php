<?php
/*
	Copyright (C) 2018 Enriador / Oliver Auth

	This file is part of the Classic1913 variant for webDiplomacy

	The Classic1913 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Classic1913 variant for webDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0:   initial version
	1.0.1: names-overlay improvements
	
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Classic1913Variant extends WDVariant {
	public $id=106;
	public $mapID=106;
	public $name='Classic1913';
	public $fullName='Classic - 1913';
	public $description='Europe in the prelude of the Great War.';
	public $author='Enriador';
	public $adapter ='Enriador / Oliver Auth';
	public $version ='1';
	public $codeVersion ='1.0.1';

	public $countries=array('England', 'France', 'Italy', 'Germany', 'Austria', 'Turkey', 'Russia');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = 'Classic1913';
		$this->variantClasses['adjudicatorPreGame'] = 'Classic1913';
	}
	
	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1913);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1913);
		};';
	}
}

?>