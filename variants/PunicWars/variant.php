<?php
/*
	Copyright (C) 2018 Yuri Hryniv aka Flame / tobi1

	This file is part of the Sail Ho II variant for webDiplomacy

	The Sail Ho variant II for webDiplomacy" is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either
	version 3 of the License, or (at your option) any later version.

	The Sail Ho II variant for webDiplomacy is distributed in the hope that it
	will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.

	---

	Based on Sail HO Variant by Michael "Tarzan" Golbe.
	Map by M.A.S.V

	Changelog:
	map-
	1.0:     initial release (all the hard work) (by Yuriy Hryniv aka Flame), map idea: M.A.S.V
	1.0.1:   some changes in provinces names
	1.0.2:	 Brundisium and Tarentum added
	1.0.3:	 Map changes
	1.1:	 Map changes: Mare Etrurium and Mare Latium merged; Neapolis Sinus and Mare Lucanum merged
	1.2:	 Map changes: Neapolis Sinus added;
	code-
	1.0.:    initial release
	1.0.1:	 start with build-phase code implemented
	1.0.2:	 start with build-phase code removed
	1.1:	 Added FoW
	1.2:	 FoW fix
	1.3.1:	 Fixed orderstatus no longer being hidden after anon update & updated fogmap.php
	1.3.2:	 Include OrderStatus fix on home page as well
	1.3.3:	 Savely removed any information about SC order in running games
	1.3.4:	 Added map history (code already used in RatWars and TenSixtySix)
	1.3.5:	 Added working order archive
	1.3.6:   updated FoW code (less information for participating admins)
	1.3.7:	 Fixed unintended preview behavior when saving orders
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class PunicWarsVariant extends WDVariant {
	public $id         =208;
	public $mapID      =208;
	public $name       ='PunicWars';
	public $fullName   ='Punic Wars';
	public $description='Punic wars based on Sail Ho variant for 4 players';
	public $author     ='M.A.S.V & Yuriy Hryniv aka Flame';
	public $adapter    ='Yuriy Hryniv aka Flame & tobi1';
	public $version    ='1.3';
	public $codeVersion='1.3.7';
	public $homepage   ='http://www.diplomail.ru';

	public $countries=array('Puni','Helleni','Etrusci','Romani');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = 'PunicWars';
		$this->variantClasses['adjudicatorPreGame'] = 'PunicWars';
		$this->variantClasses['OrderInterface']     = 'PunicWars';
		// Altered to change the starting order of a game's phases; Spring 260 BC Pre-game|Unit-placing|Diplomacy|Retreats ->
		//$this->variantClasses['processGame'] = 'PunicWars2';

		// Fog of War
		$this->variantClasses['drawMap']			  = 'PunicWars';
		$this->variantClasses['drawMapXML']           = 'PunicWars';
		$this->variantClasses['adjudicatorPreGame']   = 'PunicWars';
		$this->variantClasses['adjudicatorDiplomacy'] = 'PunicWars';
		$this->variantClasses['panelGameBoard']       = 'PunicWars';
		$this->variantClasses['OrderInterface']       = 'PunicWars';
		$this->variantClasses['OrderArchiv']          = 'PunicWars';
		$this->variantClasses['panelMember']          = 'PunicWars';
		$this->variantClasses['panelMemberHome']      = 'PunicWars';
		$this->variantClasses['processGame']          = 'PunicWars';
		$this->variantClasses['panelMembers']         = 'PunicWars';
		$this->variantClasses['panelMembersHome']     = 'PunicWars';
		$this->variantClasses['userOrderDiplomacy']   = 'PunicWars';
		$this->variantClasses['Maps']				  = 'PunicWars';

	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 9;
	}


	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(-1*(floor($turn/2) - 260))." BC.";
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(-1*(Math.floor(turn/2) - 260)) +" BC.";
		};';
	}
}

?>