<?php
/*
	Copyright (C) 2018 Technostar / Oliver Auth

	This file is part of the Divided States variant for webDiplomacy

	The Divided States variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Divided States variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
	
	Changelog:
	1.0: initial version
	1.1: Error with the order-arrows fixed.
    1.1: convoy fix code removed as no longer needed
	1.2.1: Fixed border
	1.3: Adjusted to the new chat-archive code.
	
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Divided_StatesVariant extends WDVariant {
	public $id         = 105;
	public $mapID      = 105;
	public $name       = 'Divided_States';
	public $fullName   = 'Divided States';
	public $description= 'Set after the disastrous 2020 election, Divided States pits the 50 US states against each other in a massive civil war.';
	public $author     = 'Technostar';
	public $adapter    = 'Technostar, Oliver Auth';
	public $version    = '1';
	public $codeVersion= '1.3';
	
	public $countries=array('Maine','New-Hampshire','Vermont','Massachusetts','Rhode-Island','Connecticut','New-York','New-Jersey','Pennsylvania','Delaware','Maryland','Virginia','North-Carolina','South-Carolina','Georgia','Florida','Alabama','Mississippi','Tennessee','Kentucky','West-Virginia','Ohio','Michigan','Indiana','Illinois','Wisconsin','Minnesota','Iowa','Missouri','Arkansas','Louisiana','Texas','Oklahoma','Kansas','Nebraska','South-Dakota','North-Dakota','Montana','Wyoming','Colorado','New-Mexico','Arizona','Utah','Nevada','Idaho','Washington','Oregon','California','Alaska','Hawaii');

	public function __construct() {
		parent::__construct();

		// Move flags behind the units:
		$this->variantClasses['drawMap']            = 'Divided_States';
		
		// Custom icons for each country
		$this->variantClasses['drawMap']            = 'Divided_States';
		
		// Map is build from 2 images (because it's more than 256 land-territories)
		$this->variantClasses['drawMap']            = 'Divided_States';

		// Map is Warparound
		$this->variantClasses['drawMap']            = 'Divided_States';
		
		// Bigger message-limit because of that much players:
		$this->variantClasses['Chatbox']            = 'Divided_States';
		
		// Zoom-Map
		$this->variantClasses['panelGameBoard']     = 'Divided_States';
		$this->variantClasses['drawMap']            = 'Divided_States';

		// Write the countryname in global chat
		$this->variantClasses['Chatbox']            = 'Divided_States';

		// EarlyCD: Set players that missed the first phase as Left
		$this->variantClasses['processGame']        = 'Divided_States';

		// Custom start
		$this->variantClasses['adjudicatorPreGame'] = 'Divided_States';
		$this->variantClasses['processOrderBuilds'] = 'Divided_States';
		$this->variantClasses['processGame']        = 'Divided_States';

		// Build anywhere
		$this->variantClasses['OrderInterface']     = 'Divided_States';
		$this->variantClasses['userOrderBuilds']    = 'Divided_States';
		$this->variantClasses['processOrderBuilds'] = 'Divided_States';
		
		// Split Home-view after 9 countries for better readability:
		$this->variantClasses['panelMembersHome']   = 'Divided_States';

		// Convoy-Fix
		$this->variantClasses['OrderInterface']     = 'Divided_States';
		$this->variantClasses['userOrderDiplomacy'] = 'Divided_States'; 
		
		// Neutral units:
		$this->variantClasses['OrderArchiv']        = 'Divided_States';
		//$this->variantClasses['processGame']        = 'Divided_States';
		$this->variantClasses['processMembers']     = 'Divided_States';
		
		// Transform
		$this->variantClasses['processOrderDiplomacy'] = 'Divided_States';
	}
	
	/* Neutral units that hold each other:
	*    Fort Rupert(473)      <=> Saguenay(481)
	*    Durango(493) <=> Mazatlan(494)
	*/
	public $neutralHold = array('473'=>'481','481'=>'473','493'=>'494','494'=>'493');
	
		public function countryID($countryName)
	{
		if ($countryName == 'Neutrals')
			return count($this->countries)+1;
		return parent::countryID($countryName);
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 140;
	}

	
	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 2021);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 2021);
		};';
	}
}

?>