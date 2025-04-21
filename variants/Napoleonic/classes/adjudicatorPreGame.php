<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class NapoleonicVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
			'Austria' => array('Vienna'=>'Army','Budapest'=>'Army' ,'Prague'  =>'Army' ,'Venice' =>'Fleet' ),
			'Britain' => array('London' =>'Fleet','Edinburgh' =>'Army','Dublin'  =>'Fleet' ),
			'Denmark' => array('Copenhagen'  =>'Fleet' ,'Altona' =>'Army' ,'Christiania'   =>'Fleet'),
  			'France'  => array('Paris' =>'Army','Brest' =>'Fleet','Marseilles'=>'Fleet','Amsterdam' =>'Army','Lucerne' =>'Army'),
  			'Naples'  => array('Naples'=>'Fleet' ,'Rome' =>'Army' ,'Palermo' =>'Fleet'),
  			'Prussia' => array('Königsberg'	 =>'Fleet' ,'Warsaw'  =>'Army' ,'Berlin'   =>'Army'),
  			'Russia'  => array('St. Petersburg (South Coast)'   =>'Fleet','Moscow' =>'Army','Odessa'   =>'Fleet','Kiev' =>'Army'),
  			'Spain'   => array('Madrid' =>'Army' ,'Barcelona' =>'Fleet','Burgos'   =>'Fleet'),
			'Sweden'  => array('Stockholm'=>'Fleet','Helsingfors'  =>'Fleet' ,'Gothenburg'   =>'Army' ),
  			'Turkey'  => array('Constantinople'    =>'Army' ,'Ankara' =>'Fleet' ,'Athens' =>'Fleet','Belgrade' =>'Army')
		);

}