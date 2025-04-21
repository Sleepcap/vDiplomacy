<?php
/*
	Copyright (C) 2014 

	This file is part of the Napoleonic variant for webDiplomacy

	The Napoleonic variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.

	The Napoleonic variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---
	
	Changelog:
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class NapoleonicVariant extends WDVariant {
	public $id         = 101;
	public $mapID      = 101;
	public $name       = 'Napoleonic';
	public $fullName   = 'Napoleonic';
	public $description= 'Napoleonic Wars variation';
	public $author     = 'Firehawk';
	public $adapter    = 'Firehawk';
	public $version    = '1';
	public $codeVersion= '1.0.2';
	public $homepage   = 'http://diplomail.ru';

	public $countries=array('Austria', 'Britain', 'Denmark', 'France', 'Naples', 'Prussia', 'Russia', 'Spain','Sweden', 'Turkey');
	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 18;
	}
	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = 'Napoleonic';
		$this->variantClasses['adjudicatorPreGame'] = 'Napoleonic';
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1800);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1800);
		};';
	}
}

?>