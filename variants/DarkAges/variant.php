<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class DarkAgesVariant extends WDVariant {
	public $id         = 82;
	public $mapID      = 82;
	public $name       = 'DarkAges';
	public $fullName   = 'Dark Ages';
	public $description= 'The Dark Ages Variant is a historical transplant to the North Sea region in 825 AD.';
	public $author     = 'Benjamin Hester';
	public $adapter    = 'kaner406';
	public $version    = '4';
	public $codeVersion= '1.0.2';
	public $homepage   = 'http://www.dipwiki.com/index.php?title=Dark_Ages';

	public $countries=array('Gaels','Scots','Bretons','Norse','AngloSaxons','Danes','Swedes');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'DarkAges';
		$this->variantClasses['adjudicatorPreGame'] = 'DarkAges';
		$this->variantClasses['OrderInterface']     = 'DarkAges';
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 19;
	}
	
	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 825);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 825);
		};';
	}
}

?>