<?php
/*
	Copyright (C) 2010 Oliver Auth / 2014 Tobias Florin

	This file is part of the Colonial variant for webDiplomacy

	The Colonial variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Colonial variant for webDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Rules for the The Colonial Variant by Peter Hawes:
	http://www.dipwiki.com/index.php?title=Colonial

	Changelog:
	1.0: initial release
	1.1: corrected some spelling mistake
	1.2: added support for "neutral" support-centers, recolored starting map and territories
	1.5: new webdip v.97 code
	1.5.2: missing Borders added, special-rule HongKong added
	1.6: minor tweaks
	1.6.1: missing Borders added, land bridges allow movement for fleets too
	1.6.2: missing Borders added, improved routine to avoid black country-flags
	1.7: no more flag drawing for "neutral" support-centers with colored territories
	     if they get occupied by their own country
	1.7.1: improved algorithm for flag placing
	1.7.2: New places for some units on the smallmap to improve readability
	1.7.3: New places for some units on the smallmap to improve readability
	1.7.5: Adjustments for the new variant.php code
	1.7.6: Fixed absolute-link in rules.html
	1.7.7: missing Borders added, land bridges allow movement for fleets too
	1.7.8: missing Borders added
        
	2.0: implemented special-rules "Trans-Siberian Railroad", "Suez Canal" (new variant: "Colonial Diplomacy - Original Rules")
	2.1: adjustments for the new interactive map directory structure
	2.1.2: Errorfix standoffs now working again.
	2.1.5: Errorfix Suez canal now do not give Support-Hold...
	2.1.5: Coast-Childs have no SC

	2.2: Rules revision: Adjusted rules for TSR to be more intuitive in corner cases
		+ several stuff overworked (fixed TSR with test cases, overworked map output, overworked rules)
	2.2.1: Fixed Suez being incorrectly counted as unit
	2.2.2: Updated js code and fixed datc test for stricter order interface
	
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class ColonialVariant extends WDVariant {
	public $id=12;
	public $mapID=12;
	public $name='Colonial';
	public $fullName='Colonial Diplomacy';
	public $description='Diplomacy with the colonial countries sparring over the lands and riches of the Far East.';
	public $author='Peter Hawes';
	public $adapter='Oliver Auth, Tobias Florin (Trans-Siberian Railroad, Suez Canal)';
	public $version='2.2';
	public $codeVersion='2.2.2';
	public $homepage='http://www.dipwiki.com/index.php?title=Colonial';

	public $countries=array('Britain','China','France','Holland','Japan','Russia','Turkey');

	static $transSibTerritories = array('28','35','29','39','40','30');
        
	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']               = 'Colonial';
		$this->variantClasses['adjudicatorPreGame']    = 'Colonial';
		$this->variantClasses['processMembers']        = 'Colonial';
                
		//Trans-Sib Railroad
		$this->variantClasses['drawMap']               = 'Colonial';
		$this->variantClasses['OrderArchiv']           = 'Colonial';
		$this->variantClasses['OrderInterface']        = 'Colonial';
		$this->variantClasses['adjudicatorDiplomacy']  = 'Colonial';
		$this->variantClasses['processOrderDiplomacy'] = 'Colonial';
		$this->variantClasses['userOrderDiplomacy']    = 'Colonial';
                
		//Suez Canal                       
		$this->variantClasses['drawMap']               = 'Colonial';
		$this->variantClasses['OrderArchiv']           = 'Colonial';
                
		$this->variantClasses['adjudicatorPreGame']    = 'Colonial';
                
		$this->variantClasses['processMembers']        = 'Colonial';
                
		$this->variantClasses['OrderInterface']        = 'Colonial'; 
		$this->variantClasses['adjudicatorDiplomacy']  = 'Colonial';
	}
        
        public function countryID($countryName)
	{
		if ($countryName == 'Neutral Suez')
			return count($this->countries)+1;
		
		return parent::countryID($countryName);
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 30;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1870);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1870);
		};';
	}
}

?>