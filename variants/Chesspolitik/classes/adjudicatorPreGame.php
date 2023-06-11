<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ChesspolitikVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Chinese' => array('H2' => 'Fleet','H4' => 'Army','H5' => 'Army','H7' => 'Fleet'),
		'Mughals' => array('B1' => 'Fleet','D1' => 'Army','E1' => 'Army','G1' => 'Fleet'),
		'Ottomans' => array('A2' => 'Fleet','A4' => 'Army','A5' => 'Army','A7' => 'Fleet'),
		'Russians' => array('B8' => 'Fleet','D8' => 'Army','E8' => 'Army','G8' => 'Fleet'),
	);

}
?>