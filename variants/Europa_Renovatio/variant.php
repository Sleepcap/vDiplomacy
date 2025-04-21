<?php
/*
	Copyright (C) 2019 Technostar

	This file is part of the Europa Renovatio variant for webDiplomacy

	The Europa Renovatio variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Divided States variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
 
	---
	
	Changelog:
	1.1:	Bug with Build Anywhere variants using the unit destroy index fixed
	
*/

/*
	Players who enjoy the theme and/or time period of this variant should check out 
	Europa Universalis IV, a grand strategy game made by Paradox Interactive. 
*/
defined('IN_CODE') or die('This script can not be run by itself.');

class Europa_RenovatioVariant extends WDVariant {
	public $id         = 155;
	public $mapID      = 155;
	public $name       = 'Europa_Renovatio';
	public $fullName   = 'Europa Renovatio';
	public $description= 'Europe at the dawn of the Renaissance';
	public $author     = 'Technostar';
	public $adapter    = 'Technostar';
	public $version    = '1';
	public $codeVersion= '1.1';
	
	public $countries=array('Aragon','Austria','Bavaria','Bohemia','Brandenburg','Burgundy','Castille','Denmark','England','France','Genoa','Great-Horde','Hungary','Lithuania','Livonian-Order','Mamluks','Milan','Morocco','Muscovy','Naples','Norway','Novgorod','Ottomans','Papacy','Poland','Portugal','Qara-Qoyunlu','Savoy','Saxony','Scotland','Sweden','Switzerland','Teutonic-Order','Tlemcen','Tunis','Venice');
	public function __construct() {
		parent::__construct();

		// Move flags behind the units:
		$this->variantClasses['drawMap']            = 'Europa_Renovatio';
		
		// Custom icons for each country
		$this->variantClasses['drawMap']            = 'Europa_Renovatio';
		
		// Map is build from 2 images (because it's more than 256 land-territories)
		$this->variantClasses['drawMap']            = 'Europa_Renovatio';

		// Map is Warparound
		$this->variantClasses['drawMap']            = 'Europa_Renovatio';
		
		// Bigger message-limit because of that much players:
		$this->variantClasses['Chatbox']            = 'Europa_Renovatio';
		
		// Zoom-Map
		$this->variantClasses['panelGameBoard']     = 'Europa_Renovatio';
		$this->variantClasses['drawMap']            = 'Europa_Renovatio';

		// Write the countryname in global chat
		$this->variantClasses['Chatbox']            = 'Europa_Renovatio';

		// EarlyCD: Set players that missed the first phase as Left
		$this->variantClasses['processGame']        = 'Europa_Renovatio';

		// Custom start
		$this->variantClasses['adjudicatorPreGame'] = 'Europa_Renovatio';
		$this->variantClasses['processOrderBuilds'] = 'Europa_Renovatio';
		$this->variantClasses['processGame']        = 'Europa_Renovatio';

		// Build anywhere
		$this->variantClasses['OrderInterface']     = 'Europa_Renovatio';
		$this->variantClasses['userOrderBuilds']    = 'Europa_Renovatio';
		$this->variantClasses['processOrderBuilds'] = 'Europa_Renovatio';
		
		// Split Home-view after 9 countries for better readability:
		$this->variantClasses['panelMembersHome']   = 'Europa_Renovatio';

		// Convoy-Fix
		$this->variantClasses['OrderInterface']     = 'Europa_Renovatio';
		$this->variantClasses['userOrderDiplomacy'] = 'Europa_Renovatio'; 
		
		// Transform
		$this->variantClasses['OrderArchiv']        = 'Europa_Renovatio';
		$this->variantClasses['processOrderDiplomacy'] = 'Europa_Renovatio';
	}
	


	public function initialize() {
		parent::initialize();
		//SC target should be floor(50% + 1.5) to enable 2-way draws.
		$this->supplyCenterTarget = 155;
	}

	//Variant shenanigans: Make year increase in 5-year increments so that the game runs in a more realistic time-frame
	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) * 5 + 1450);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) * 5 + 1450);
		};';
	}
}

?>