<?php

/**
 * This variant lets players build on any SC they own, it demos using variants
 * to change what orders are permitted.
 */
class Classic1898Variant extends WDVariant {
	public $id=133;
	public $mapID=133;
	public $name='Classic1898';
	public $fullName='Classic - 1898';
	public $description='The same as the standard map, except each power got only one unit at the start.';
	public $author='Randy Davis';
	public $adapter='Yuriy Hryniv aka Flame';
	public $version    = '1.0';
	public $codeVersion= '1.0';

	public $countries=array('England', 'France', 'Italy', 'Germany', 'Austria', 'Turkey', 'Russia');
	public $variantClasses=array();

	public function __construct() {
		parent::__construct();

		// drawMap extended for country-colors and loading the classic map images
		$this->variantClasses['drawMap'] = 'Classic1898';

		/*
		 * adjudicatorPreGame extended for country starting unit data
		 */
		$this->variantClasses['adjudicatorPreGame'] = 'Classic1898';

		// Order validation code, changed to validate builds on non-home SCs
		$this->variantClasses['userOrderBuilds'] = 'Classic1898';

		// Count all free SCs and not just the home SCs.
		$this->variantClasses['processOrderBuilds'] = 'Classic1898';

		// Order interface/generation code, changed to add javascript in resources which makes non-home SCs an option
		$this->variantClasses['OrderInterface'] = 'Classic1898';
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1899);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1899);
		};';
	}
}

?>