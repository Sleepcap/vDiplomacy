<?php
/*
	Copyright (C) 2011 kaner406 / Oliver Auth

	This file is part of the Rat Wars variant for webDiplomacy

	The Rat Wars variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.

	The Rat Wars variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---
	
	Changelog:
	1.0:     initial release
	1.1  : only one class in ProcessGame to avoid problems with the banPlayer or uncrashGames code
	1.1.1: Fixed supportMove option from foggy multi-coast territory
	1.1.2: updated fogmap.php
	1.1.3: Added working order archive
	1.2  : Added missing FoW files (member list revealed SC count etc) 
	1.2.1: updated FoW code (less information for participating admins)
	1.2.2: Fixed unintended preview behavior when saving orders
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class RatWarsVariant extends WDVariant {
	public $id         =65;
	public $mapID      =65;
	public $name       ='RatWars';
	public $fullName   ='Rat Wars';
	public $description='Rat Armies Battle it out';
	public $author     ='kaner406';
	public $adapter    ='kaner406 / Oliver Auth';
	public $version    ='1';
	public $codeVersion='1.2.2';	
	
	public $countries=array('Dead Rabbits','Plug Uglies','Shirt Tails','Hell-Cats');	

	public function __construct() {
		parent::__construct();

		// Basic gamesetup
		$this->variantClasses['drawMap']            = 'RatWars';
		$this->variantClasses['adjudicatorPreGame'] = 'RatWars';
		
		// Build anywhere
		$this->variantClasses['OrderInterface']     = 'RatWars';		
		$this->variantClasses['processOrderBuilds'] = 'RatWars';
		$this->variantClasses['userOrderBuilds']    = 'RatWars';
		
		// Start with a build phase:
		$this->variantClasses['adjudicatorPreGame'] = 'RatWars';
		$this->variantClasses['processGame']        = 'RatWars';
		
		// FogOfWar
		$this->variantClasses['drawMap']              = 'RatWars';
		$this->variantClasses['drawMapXML']           = 'RatWars';
		$this->variantClasses['adjudicatorPreGame']   = 'RatWars';
		$this->variantClasses['adjudicatorDiplomacy'] = 'RatWars';
		$this->variantClasses['panelGameBoard']       = 'RatWars';
		$this->variantClasses['OrderInterface']       = 'RatWars';
		$this->variantClasses['OrderArchiv']          = 'RatWars';
		$this->variantClasses['processGame']          = 'RatWars';
		$this->variantClasses['userOrderDiplomacy']   = 'RatWars';
		$this->variantClasses['Maps']                 = 'RatWars';
		$this->variantClasses['panelMember']          = 'RatWars';
		$this->variantClasses['panelMemberHome']      = 'RatWars';
		$this->variantClasses['panelMembers']         = 'RatWars';
		$this->variantClasses['panelMembersHome']     = 'RatWars';
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