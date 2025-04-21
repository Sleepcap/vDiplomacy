<?php
defined('IN_CODE') or die('This script can not be run by itself.');

class AtlanticColoniesVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'France'  => array('Brest'  =>'Fleet', 'Gor&eacute;e'   =>'Fleet', 'Marseilles'  =>'Fleet', 'Windward Islands'  =>'Fleet', 'Montreal'  =>'Army', 'St. Louis'  =>'Army', 'Cayenne'  =>'Army'),
		'Spain'   => array('Valencia'=>'Fleet', 'Gibraltar'   =>'Fleet', 'Lima'=>'Army', 'Santiago'=>'Army', 'Merida'  =>'Fleet', 'Mexico City'  =>'Army', 'Canary Islands'  =>'Army'),
		'England' => array('London'  =>'Fleet', 'Bristol' =>'Fleet', 'Cape of Good Hope'=>'Fleet', 'Jamaica'  =>'Fleet', 'New England'  =>'Fleet', 'Moose Fort'  =>'Army', 'Georgia'  =>'Army'),
		'Portugal'=> array('Portugal'=>'Fleet', 'Azores Islands'   =>'Fleet', 'Mazagon'=>'Army', 'Angola'  =>'Fleet', 'Maranh&atilde;o'  =>'Army', 'S&atilde;o Salvador'  =>'Fleet', 'Rio De Janeiro'  =>'Fleet')
	);

}