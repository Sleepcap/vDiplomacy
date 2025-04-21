<?php

defined('IN_CODE') or die('This script can not be run by itself.');

include_once ("variants/TenSixtySix/classes/adjudicatorPreGame.php");

class TenSixtySix_V3Variant_adjudicatorPreGame extends TenSixtySixVariant_adjudicatorPreGame {

	protected $countryUnits = array(
		'English'      => array('London' =>'Fleet','Warwick' =>'Army','York' =>'Army','Oxford' =>'Army','Winchester (South Coast)' =>'Fleet'),
		'Normans'      => array('Channel Islands' =>'Fleet','Caen' =>'Army','Rouen' =>'Fleet'),
		'Norwegians'   => array('Oslo' =>'Fleet','Kaupang' =>'Army','Trondheim' =>'Fleet','Hadrian s Wall' =>'Army'),
		'Neutral units'=> array('Edinburgh' =>'Army','Glasgow' =>'Army','Gwynedd and Lakes District' =>'Army','Dublin' =>'Army','Sweden' =>'Army','Denmark' =>'Army','County of Flanders' =>'Army','Duchy of Brittany' =>'Army')
	);

}
