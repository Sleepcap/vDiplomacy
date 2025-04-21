<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ClassicLayeredVariant extends WDVariant {
	public $id         = 86;
	public $mapID      = 86;
	public $name       = 'ClassicLayered';
	public $fullName   = 'Classic - Layered';
	public $description= 'The classic game but 2 boards.';
	public $author     = 'Ralan Hill';
	public $adapter    = 'kaner406';
	public $version    = '1';
	public $codeVersion= '1.0';

	public $countries=array('England', 'France', 'Italy', 'Germany', 'Austria', 'Turkey', 'Russia');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'ClassicLayered';
		$this->variantClasses['adjudicatorPreGame'] = 'ClassicLayered';
	}
	
	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 35;
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