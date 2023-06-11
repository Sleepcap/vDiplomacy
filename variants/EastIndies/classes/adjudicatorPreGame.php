<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class EastIndiesVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Tondo' => array('Tondo' => 'Fleet','Namayan (South Coast)' => 'Fleet','Kasiguran' => 'Fleet'),
		'Dai Viet' => array('Hanoi' => 'Army','Haiphong' => 'Army','Faifo' => 'Fleet'),
		'Ternate' => array('Halmahera' => 'Fleet','Buru' => 'Fleet','Seram' => 'Fleet'),
		'Ayutthaya' => array('Ayutthaya' => 'Army','Roi Et' => 'Army','Dawei (West Coast)' => 'Fleet'),
		'Malacca' => array('Malacca' => 'Fleet','Pahang' => 'Fleet','Riau' => 'Fleet'),
		'Majapahit' => array('Pajajaran' => 'Fleet','Javadvipa (South Coast)' => 'Fleet','Trowulan' => 'Fleet'),
		'Brunei' => array('Brunei' => 'Fleet','Tunku' => 'Fleet','Palawan' => 'Fleet'),
		'Mughalistan' => array('Badakhshan' => 'Army','Kabul' => 'Army','Balkh' => 'Army'),
		'Persia' => array('Isfahan' => 'Army','Meshed' => 'Army','Hormuz' => 'Fleet'),
		'Rajputana' => array('Multan' => 'Army','Jodhpur' => 'Army','Jaisalmer' => 'Fleet'),
		'Delhi' => array('Agra' => 'Army','Awadh' => 'Army','Muzaffarpur' => 'Army'),
		'Gondwana' => array('Raipur' => 'Army','Jabalpur' => 'Army','Sambalpur' => 'Fleet'),
		'Vijayanagar' => array('Calicut' => 'Army','Bangalore' => 'Army','Pulicat' => 'Fleet'),
		'Bahmana' => array('Ahmadnagar' => 'Army','Bijapur' => 'Army','Goa' => 'Fleet'),
	);

}
?>