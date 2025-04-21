<?php
/*
	Copyright (C) 2013 kaner406 & Oliver Auth

	This file is part of the WWIV_V6 variant for webDiplomacy

	The WWIV_V6 variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.

	The WWIV_V6 variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0:   initial release
	1.1.1: Problem with the new default builds fixed.
	1.1.2: Problem with the new default builds fixed.
	1.1.4: Problem with the new default builds fixed.
	1.1.5: Problem with the new default builds fixed.
	1.1.7: Problem with the interactive map fixed.
	1.1.8: Problem with the new default builds fixed.
	1.1.9: Fixed spelling
	1.1.10:Interactive map bugfixes
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class WWIV_V6Variant extends WWIVVariant {
	public $id         = 102;
	public $mapID      = 102;
	public $name       = 'WWIV_V6';
	public $fullName   = 'World War IV (Version 6.2)';
	public $description= 'A variant with an enormous map for 36 players over the whole globe.';
	public $author     = 'Tom Reinecker';
	public $adapter    = 'kaner406 & Oliver Auth';
	public $version    = '6.2';
	public $codeVersion= '1.1.10';
	public $homepage   = 'http://www.freewebs.com/tomahaha/ww4.htm';
	
	public $countries=array('Amazon-Empire', 'Argentina', 'Australia', 'Brazil', 'California', 'Canada', 'Catholica', 'Central-Asia', 'Colombia', 'Kongo', 'Cuba', 'North-Africa', 'Germany', 'Central-States', 'Inca-Empire', 'India', 'Indonesia', 'Iran', 'Japan', 'East-Africa', 'Manchuria', 'Mexico', 'Nigeria', 'Oceania', 'Philippines', 'Quebec', 'Russia', 'Sichuan-Empire', 'Song-Empire', 'South-Africa', 'Texas', 'Thailand', 'Turkey', 'United-Kingdom', 'United-States', 'Saudi-Arabia');

	public function __construct() {
		parent::__construct();
		
		// Setup
		$this->variantClasses['processOrderBuilds'] = 'WWIV_V6';
		$this->variantClasses['drawMap']            = 'WWIV_V6';
		
		// convoy coasts
		$this->variantClasses['OrderInterface']     = 'WWIV_V6';
		$this->variantClasses['userOrderDiplomacy'] = 'WWIV_V6';

	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 100;
	}

	public $convoyCoasts = array (
		'383','374','448','489','471','446','47',
		'13','357','97','56','511','253','297','529',
		'304','282','255','299','573','281','247','187','211','209'
	);

	
	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 2101);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 2101);
		};';
	}
}

?>