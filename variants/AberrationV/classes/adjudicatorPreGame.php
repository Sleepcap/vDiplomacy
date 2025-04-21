<?php
defined('IN_CODE') or die('This script can not be run by itself.');

class AberrationVVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Burgundy' => array('Hague'=>'Fleet', 'Brussels'=>'Army', 'Dijon'=>'Army'),
		'Byzantium'=> array('Athens'=>'Army', 'Constantinople'=>'Fleet', 'Smyrna'=>'Army'),
		'Hungary'  => array('Zara'=>'Fleet', 'Budapest'=>'Army', 'Szeged'=>'Army'),
		'Ireland'     => array('Edinburgh'=>'Fleet', 'Dublin'=>'Fleet', 'Alcuyd'=>'Army'),
		'Israel'   => array('Cairo'=>'Fleet', 'Jerusalem'=>'Army', 'Damascus'=>'Army'),
		'Poland'   => array('Riga'=>'Army', 'Warsaw'=>'Army', 'Gdansk'=>'Fleet'),
		'Sicily'   => array('Palermo'=>'Fleet', 'Naples'=>'Fleet', 'Rome'=>'Army'),
		'Spain'    => array('Valencia'=>'Fleet', 'Santiago'=>'Army', 'Toledo'=>'Army'),
		'Ukraine'  => array('Yalta'=>'Fleet', 'Odessa'=>'Army', 'Kiev'=>'Army'),
	);

}