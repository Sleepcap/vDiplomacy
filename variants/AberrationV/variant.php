<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class AberrationVVariant extends WDVariant {
	public $id         = 88;
	public $mapID      = 88;
	public $name       = 'AberrationV';
	public $fullName   = 'Aberration V';
	public $description= 'A version of Diplomacy based on an alternate form of history.';
	public $author     = 'Rod Walker & Nick Fitzpatrick';
	public $adapter    = 'Ninjanrd, General Cool';
	public $version    = '5';
	public $codeVersion= '1.1.3';

	public $countries=array('Burgundy','Byzantium','Hungary','Ireland','Israel','Poland','Sicily','Spain','Ukraine');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'AberrationV';
		$this->variantClasses['adjudicatorPreGame'] = 'AberrationV';
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