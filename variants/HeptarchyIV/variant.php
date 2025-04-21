<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class HeptarchyIVVariant extends WDVariant {
	public $id         = 89;
	public $mapID      = 89;
	public $name       = 'HeptarchyIV';
	public $fullName   = 'HeptarchyIV';
	public $description= 'Dark Age kingdoms of Britain battle it out';
	public $adapter    = 'kaner406';
	public $author     = 'Geoff Bache ';
	public $homepage   = 'http://www.dipwiki.com/index.php?title=Heptarchy_IV';
	public $version    = '4';
	public $codeVersion= '1.0';

	public $countries=array('Anglia', 'Cornubia', 'Ireland', 'Mercia', 'Northumbria', 'Scotland', 'Wales');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'HeptarchyIV';
		$this->variantClasses['adjudicatorPreGame'] = 'HeptarchyIV';
		$this->variantClasses['OrderInterface']     = 'HeptarchyIV';
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 651);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 651);
		};';
	}
}

?>