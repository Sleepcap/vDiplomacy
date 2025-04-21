<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ColdWarReduxVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'USSR' => array('Anadyr' => 'Fleet','Moscow' => 'Army','Havana' => 'Fleet','Leningrad' => 'Army'),
		'USA' => array('New York' => 'Army','Los Angeles' => 'Fleet','Australia' => 'Fleet'),
		'NATO' => array('London' => 'Fleet','Paris' => 'Army','Istanbul' => 'Fleet'),
		'PRC' => array('Shanghai' => 'Fleet','Hanoi' => 'Fleet','Urumchi' => 'Army'),
	);

}
?>