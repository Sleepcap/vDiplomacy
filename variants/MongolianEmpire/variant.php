<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class MongolianEmpireVariant extends WDVariant {
	public $id=138;
	public $mapID=138;
	public $name='MongolianEmpire';
	public $fullName='13th Century Mongolian Empire';
	public $description="War in the Mongol's strive to conquer the known world as of the mid-thirteenth century.";
	public $author='kaner406, Ninjanrd';
	public $adapter='Yuri Hryniv aka Flame';
	public $version='2';
	public $codeVersion='1.6.3';
	public $homepage='https://www.webdiplomacy.ru';

	public $countries=array('Chagatai Khanate','Crusader States','Delhi Sultanate','Golden Horde','Ilkhanate','Khmer Empire','Sirivijaya','The Caliphate','The Great Khans','Tibet','Yuan Dynasty');

	public function __construct() {
		parent::__construct();

		// Setup the basic game parameter
		$this->variantClasses['drawMap']            = 'MongolianEmpire';
		$this->variantClasses['adjudicatorPreGame'] = 'MongolianEmpire';

		// New medival icons for armies and fleets
		$this->variantClasses['drawMap']            = 'MongolianEmpire';
		$this->variantClasses['OrderInterface']     = 'MongolianEmpire';

		// Build anywhere
		$this->variantClasses['OrderInterface']     = 'MongolianEmpire';
		$this->variantClasses['processOrderBuilds'] = 'MongolianEmpire';
		$this->variantClasses['userOrderBuilds']    = 'MongolianEmpire';

		// Neutral units:
		$this->variantClasses['OrderArchiv']        = 'MongolianEmpire';
		$this->variantClasses['processGame']        = 'MongolianEmpire';
		$this->variantClasses['processMembers']     = 'MongolianEmpire';

	}

	public function countryID($countryName)
	{
		if ($countryName == 'Neutral units')
			return count($this->countries)+1;

		return parent::countryID($countryName);
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1231);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1231);
		};';
	}
}
?>