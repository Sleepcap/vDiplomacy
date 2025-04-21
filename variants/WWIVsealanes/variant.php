<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class WWIVsealanesVariant extends WDVariant {
	public $id         = 95;
	public $mapID      = 95;
	public $name       = 'WWIVsealanes';
	public $fullName   = 'World War IV sealanes';
	public $description= '';
	public $author     = '';
	public $adapter    = '';
	public $version    = '1';
	public $codeVersion= '1.3.1';
	
	public $countries=array('Amazon-Empire', 'Argentina', 'Australia', 'Brazil', 'California', 'Canada', 'Catholica', 'Central-Asia', 'Colombia', 'Congo', 'Cuba', 'Egypt', 'Germany', 'Illinois', 'Inca-Empire', 'India', 'Indonesia', 'Iran', 'Japan', 'Kenya', 'Manchuria', 'Mexico', 'Nigeria', 'Oceania', 'Philippines', 'Quebec', 'Russia', 'Sichuan-Empire', 'Song-Empire', 'South-Africa', 'Texas', 'Thailand', 'Turkey', 'United-Kingdom', 'United-States');

	public function __construct() {
		parent::__construct();

		// Move flags behind the units:
		$this->variantClasses['drawMap']            = 'WWIVsealanes';
		
		// Custom icons for each country
		$this->variantClasses['drawMap']            = 'WWIVsealanes';
		
		// Map is build from 2 images (because it's more than 256 land-territories)
		$this->variantClasses['drawMap']            = 'WWIVsealanes';

		// Map is Warparound
		$this->variantClasses['drawMap']            = 'WWIVsealanes';
		
		// Bigger message-limit because of that much players:
		$this->variantClasses['Chatbox']            = 'WWIVsealanes';
		
		// Zoom-Map
		$this->variantClasses['panelGameBoard']     = 'WWIVsealanes';
		$this->variantClasses['drawMap']            = 'WWIVsealanes';

		// Write the countryname in global chat
		$this->variantClasses['Chatbox']            = 'WWIVsealanes';

		// EarlyCD: Set players that missed the first phase as Left
		$this->variantClasses['processGame']        = 'WWIVsealanes';

		// Custom start
		$this->variantClasses['adjudicatorPreGame'] = 'WWIVsealanes';
		$this->variantClasses['processOrderBuilds'] = 'WWIVsealanes';
		$this->variantClasses['processGame']        = 'WWIVsealanes';

		// Build anywhere
		$this->variantClasses['OrderInterface']     = 'WWIVsealanes';
		$this->variantClasses['userOrderBuilds']    = 'WWIVsealanes';
		$this->variantClasses['processOrderBuilds'] = 'WWIVsealanes';
		
		// Split Home-view after 9 countries for better readability:
		$this->variantClasses['panelMembersHome']   = 'WWIVsealanes';

		// Convoy-Fix
		$this->variantClasses['OrderInterface']     = 'WWIVsealanes';
		$this->variantClasses['userOrderDiplomacy'] = 'WWIVsealanes'; 
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 123;
	}

	
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