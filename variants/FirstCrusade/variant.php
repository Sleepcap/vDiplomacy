<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class FirstCrusadeVariant extends WDVariant {
	public $id         = 98;
	public $mapID      = 98;
	public $name       = 'FirstCrusade';
	public $fullName   = 'First Crusade';
	public $description= 'Diplomacy at the time of the First Crusade';
	public $author     = 'Firehawk';
	public $adapter    = 'Firehawk';
	public $version    = '1';
	public $codeVersion= '1.9';
	public $homepage   = '';

	public $countries=array('Holy Roman Empire', 'England', 'France', 'Seljuk Turks', 'Byzantine Empire', 'Russia', 'Almoravids');

	public function __construct() {
		parent::__construct();

		$this->variantClasses['drawMap']            = 'FirstCrusade';
		$this->variantClasses['adjudicatorPreGame'] = 'FirstCrusade';
		
		// Each country it's own icons:
		$this->variantClasses['drawMap']            = 'FirstCrusade';
		$this->variantClasses['OrderInterface']     = 'FirstCrusade';
		
	}
	
	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 27;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1096);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1096);
		};';
	}
}

?>