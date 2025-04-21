<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class AtlanticColoniesVariant extends WDVariant {
	public $id         = 99;
	public $mapID      = 99;
	public $name       ='AtlanticColonies';
	public $fullName   = 'Atlantic Colonies';
	public $description= 'The mightiest nations of Europe strive for dominance on the shores of the great Atlantic.';
	public $author     = 'Safari';
	public $adapter    = 'Safari';
	public $version    = '1';
	public $codeVersion= '1.0.8';

	public $countries=array('France', 'Spain', 'England', 'Portugal' );

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'AtlanticColonies';
		$this->variantClasses['adjudicatorPreGame'] = 'AtlanticColonies';
		$this->variantClasses['OrderInterface']     = 'AtlanticColonies';
	}
	
	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 25;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1675);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1675);
		};';
	}
}

?>