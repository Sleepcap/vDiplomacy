<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class WesternEurope1300Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Burgundy' => array('Burgundy (BUR)' => 'Army','Lyonnais (LYO)' => 'Army','Franche-Comt&eacute; (FRA)' => 'Army'),
		'Castile' => array('Asturias (AST)' => 'Fleet','Seville (SEV)' => 'Fleet','Le&oacute;n (LEO)' => 'Army'),
		'Aragon' => array('Valencia (VAL)' => 'Fleet','Aragon (ARA)' => 'Army','Catalonia (CAT)' => 'Fleet'),
		'England' => array('London (LON)' => 'Army','Sussex (SUS)' => 'Fleet','Devon (DEV)' => 'Fleet','Guyenne (GUY)' => 'Army','Gascony (GAS)' => 'Fleet','Yorkshire (YOR)' => 'Fleet','Lincolnshire (LIN)' => 'Army'),
		'France' => array('Flanders (FLA)' => 'Fleet','Normandy (NOR)' => 'Army','&Icirc;le-de-France (ILE)' => 'Army','Blois (BLO)' => 'Army','Anjou (ANJ)' => 'Army','Toulouse (TOU)' => 'Army','Languedoc (LAN)' => 'Fleet'),
	);

}
?>