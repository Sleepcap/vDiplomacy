<?php
defined('IN_CODE') or die('This script can not be run by itself.');

class DarkAgesVariant_adjudicatorPreGame extends adjudicatorPreGame
{
	protected $countryUnits = array(
		'Gaels'=> array('Meath' => 'Army', 'Ulster' => 'Fleet', 'Leinster' => 'Fleet'),
		'Scots'=> array('Circinn' => 'Fleet', 'Fortrenn' => 'Army', 'Cait' => 'Fleet'),
		'Bretons'=> array('Dyfed' => 'Fleet', 'Gwynned' => 'Army', 'Powys' => 'Army'),
		'Norse'=> array('Hordaland' => 'Fleet', 'Rogaland' => 'Fleet', 'Vestfold' => 'Army'),
		'AngloSaxons'=> array('East Anglia' => 'Fleet', 'Kent' => 'Army', 'Hamptonshire' => 'Fleet'),
		'Danes'=> array('Jelling (West Coast)' => 'Fleet', 'Ribe' => 'Army', 'Roskilde' => 'Fleet'),
		'Swedes'=> array('Svear' => 'Fleet', 'Gotar' => 'Army', 'Lappland' => 'Army')
	);
}