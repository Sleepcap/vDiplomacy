<?php
defined('IN_CODE') or die('This script can not be run by itself.');

class ClassicLayeredVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'England' => array('Edinburgh 1'=>'Fleet', 'Liverpool 1'=>'Army', 'London 1'=>'Fleet', 'Edinburgh 2'=>'Fleet', 'Liverpool 2'=>'Army', 'London 2'=>'Fleet'),
		'France'  => array('Brest 1'=>'Fleet', 'Paris 1'=>'Army', 'Marseilles 1'=>'Army', 'Brest 2'=>'Fleet', 'Paris 2'=>'Army', 'Marseilles 2'=>'Army'),
		'Italy'   => array('Venice 1'=>'Army', 'Rome 1'=>'Army', 'Naples 1'=>'Fleet', 'Venice 2'=>'Army', 'Rome 2'=>'Army', 'Naples 2'=>'Fleet'),
		'Germany' => array('Kiel 1'=>'Fleet', 'Berlin 1'=>'Army', 'Munich 1'=>'Army', 'Kiel 2'=>'Fleet', 'Berlin 2'=>'Army', 'Munich 2'=>'Army'),
		'Austria' => array('Vienna 1'=>'Army', 'Trieste 1'=>'Fleet', 'Budapest 1'=>'Army', 'Vienna 2'=>'Army', 'Trieste 2'=>'Fleet', 'Budapest 2'=>'Army'),
		'Turkey'  => array('Smyrna 1'=>'Army', 'Ankara 1'=>'Fleet', 'Constantinople 1'=>'Army', 'Smyrna 2'=>'Army', 'Ankara 2'=>'Fleet', 'Constantinople 2'=>'Army'),
		'Russia'  => array('Moscow 1'=>'Army', 'St. Petersburg 1 (South Coast)'=>'Fleet', 'Warsaw 1'=>'Army', 'Sevastopol 1'=>'Fleet', 'Moscow 2'=>'Army', 'St. Petersburg 2 (South Coast)'=>'Fleet', 'Warsaw 2'=>'Army', 'Sevastopol 2'=>'Fleet')
	);

}