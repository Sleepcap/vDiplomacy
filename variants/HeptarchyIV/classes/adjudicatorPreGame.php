<?php
defined('IN_CODE') or die('This script can not be run by itself.');

class HeptarchyIVVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Anglia'      => array('Dover'=>'Fleet', 'London' =>'Army', 'Oxford'=>'Army'),
		'Cornubia'    => array('Plymouth'=>'Fleet', 'Bristol' =>'Army', 'Exeter'=>'Army'),
		'Ireland'     => array('Dublin'=>'Fleet', 'Belfast' =>'Fleet', 'Cork'=>'Fleet'),
		'Mercia'      => array('Gloucester'=>'Fleet', 'King\'s Lynn'=>'Fleet', 'Nottingham' =>'Army', 'Birmingham'=>'Army'),
		'Northumbria' => array('Durham'=>'Army', 'York' =>'Army', 'Lancaster'=>'Fleet'),
		'Scotland'    => array('Aberdeen'=>'Fleet', 'Edinburgh' =>'Army', 'Glasgow'=>'Fleet'),
		'Wales'       => array('Swansea'=>'Fleet', 'Cardiff' =>'Army', 'Llandudno'=>'Army')
	);	
	
}