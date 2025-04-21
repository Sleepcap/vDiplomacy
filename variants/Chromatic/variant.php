<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ChromaticVariant extends WDVariant {
	public $id         = 93;
	public $mapID      = 93;
	public $name       ='Chromatic';
	public $fullName   = 'Chromatic';
	public $description= 'A balanced 5 player variant';
	public $author    = 'Jimmy Millington, Robs Schone and Lynsey Smith';
	public $adapter    = 'Tristan';
	public $homepage       ='http://dipwiki.com/index.php?title=Chromatic';
	public $version    = '1';
	public $codeVersion= '1.1';
		
	public $countries=array('Blue','Yellow','Dark','Light','Red');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'Chromatic';
		$this->variantClasses['adjudicatorPreGame'] = 'Chromatic';
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