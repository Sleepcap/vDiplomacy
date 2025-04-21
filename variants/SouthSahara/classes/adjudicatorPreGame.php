<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class SouthSaharaVariant_adjudicatorPreGame extends RuleExtensionsVariant_adjudicatorPreGame {

	protected $countryUnits = array(
		'Mali' => array('Timbuktu' => 'Army','Kumbi Saleh' => 'Army','Jenne' => 'Army','Walata' => 'Army'),
		'Benin' => array('Owo' => 'Army','Edo' => 'Army','Ife' => 'Army'),
		'Jolof' => array('Baol' => 'Army','Saloum' => 'Army','Kayor' => 'Army'),
		'Bornu' => array('Bilma' => 'Army','Njimi' => 'Army','Masseniya' => 'Army'),
		'Bonoman' => array('Salaga' => 'Army','Begho' => 'Army','Bono Manso' => 'Army'),
	);

}
?>