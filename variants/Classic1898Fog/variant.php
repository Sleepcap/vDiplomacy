<?php
/*
	Copyright (C) 2018 Yuriy Hryniv aka Flame

	This file is part of the Classic 1898 Fog-of-War variant for webDiplomacy

	The Classic 1898 Fog-of-War variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General Public
	License as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Classic 1898 Fog-of-War variant for webDiplomacy is distributed in the hope that 
	it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---
	
	Changelog:
	1.0:    initial release
	1.0.1:  updated FoW code
	1.0.2:  updated FoW code (less information for participating admins)
	1.0.3: Fixed unintended preview behavior when saving orders
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Classic1898FogVariant extends WDVariant {
	public $id         = 134;
	public $mapID      = 134;
	public $name       = 'Classic1898Fog';
	public $fullName   = 'Classic - 1898 Fog of War';
	public $description= 'The same as the standard map, except each power got only one unit at the start.';
	public $author='Randy Davis';
	public $adapter='Yuriy Hryniv aka Flame';
	public $version    = '1.0';
	public $codeVersion= '1.0.3';

	public $countries=array('England', 'France', 'Italy', 'Germany', 'Austria', 'Turkey', 'Russia');
	
	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']              = 'Classic1898Fog';
		$this->variantClasses['drawMapXML']           = 'Classic1898Fog';
		$this->variantClasses['adjudicatorPreGame']   = 'Classic1898Fog';
		$this->variantClasses['adjudicatorDiplomacy'] = 'Classic1898Fog';
		$this->variantClasses['panelGameBoard']       = 'Classic1898Fog';
		$this->variantClasses['OrderInterface']       = 'Classic1898Fog';
		$this->variantClasses['OrderArchiv']          = 'Classic1898Fog';
		$this->variantClasses['panelMember']          = 'Classic1898Fog';
		$this->variantClasses['panelMemberHome']      = 'Classic1898Fog';
		$this->variantClasses['processGame']          = 'Classic1898Fog';
		$this->variantClasses['panelMembers']         = 'Classic1898Fog';
		$this->variantClasses['panelMembersHome']     = 'Classic1898Fog';
		$this->variantClasses['userOrderDiplomacy']   = 'Classic1898Fog';
		$this->variantClasses['Maps']				  = 'Classic1898Fog';

		// Order validation code, changed to validate builds on non-home SCs
		$this->variantClasses['userOrderBuilds'] = 'Classic1898Fog';

		// Count all free SCs and not just the home SCs.
		$this->variantClasses['processOrderBuilds'] = 'Classic1898Fog';

		// Order interface/generation code, changed to add javascript in resources which makes non-home SCs an option
		$this->variantClasses['OrderInterface'] = 'Classic1898Fog';
	 }

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1899);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1899);
		};';
	}

}

?>