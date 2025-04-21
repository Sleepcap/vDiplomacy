<?php

class FirstCrusadeVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Holy Roman Empire'=> array('Nuremberg'=>'Army', 'Bremen' =>'Army', 'Vienna'=>'Army', 'Genoa'=>'Fleet', 'Hamburg (East Coast)'=>'Fleet'),
		'England'          => array('London'=>'Fleet', 'Naples' =>'Fleet', 'Canterbury'=>'Fleet', 'York'=>'Army'),
		'France'           => array('Paris'=>'Army', 'Toulouse (West Coast)' =>'Fleet', 'Clermont'=>'Army', 'Flanders'=>'Fleet'),
		'Seljuk Turks'     => array('Antioch'=>'Fleet', 'Jerusalem' =>'Army', 'Iconium'=>'Army', 'Nicaea'=>'Army'),
		'Byzantine Empire' => array('Constantinople'=>'Army', 'Cherson'=>'Fleet', 'Nissa'=>'Army', 'Athens'=>'Fleet'),
		'Russia'           => array('Kief'=>'Army', 'Novgorod (South Coast)' =>'Fleet', 'Smolensk'=>'Army', 'Minsk'=>'Army'),
		'Almoravids'       => array('Tunis'=>'Fleet', 'Tripoli' =>'Army', 'Cordova'=>'Army', 'Fez'=>'Fleet')
	);
	
}