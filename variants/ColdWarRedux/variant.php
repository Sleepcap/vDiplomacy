<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ColdWarReduxVariant extends WDVariant {
	public $id         = 128;
	public $mapID      = 128;
	public $name       = 'ColdWarRedux';
	public $fullName    ='Cold War Redux';
	public $description ='The Cold War variant, expanded for 4 players.';
	public $author      ='Enriador (original design by Firehawk & Safari)';
	public $adapter     ='Enriador & Oliver Auth';
	public $version     ='1.0';
	public $codeVersion ='0.3';
	public $homepage    ='';

	public $countries=array('USSR','NATO','USA','PRC');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'ColdWarRedux';
		$this->variantClasses['adjudicatorPreGame'] = 'ColdWarRedux';
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1960);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1960);
		};';
	}
}

?>