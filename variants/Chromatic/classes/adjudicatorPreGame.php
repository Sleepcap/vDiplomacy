<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ChromaticVariant_adjudicatorPreGame extends adjudicatorPreGame
{
	protected $countryUnits = array(
		'Blue'   => array('Cobalt'    =>'Army' , 'Royal'   =>'Army' , 'Sapphire'=>'Fleet'),
		'Yellow' => array('Topaz'     =>'Fleet', 'Gold'    =>'Army' , 'Sunshine'=>'Army' ),
		'Dark'   => array('Jet'       =>'Army' , 'Obsidian'=>'Fleet', 'Ebony'   =>'Army' ),
		'Light'  => array('Alabaster' =>'Fleet', 'Ghost'   =>'Army' , 'Ivory'   =>'Army' ),
		'Light'  => array('Alabaster' =>'Fleet', 'Ghost'   =>'Army' , 'Ivory'   =>'Army' ),
		'Red'    => array('Vermillion'=>'Fleet', 'Ruby'    =>'Army' , 'Crimson' =>'Army' )
	);
}
