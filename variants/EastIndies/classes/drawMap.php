<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ZoomMap_drawMap extends drawMap
{
	// Always only load the largemap (as there is no smallmap)
	public function __construct($smallmap)
	{
		parent::__construct(false);
	}

	// Always use the small orderarrows...
	protected function loadOrderArrows()
	{
		$this->smallmap=true;
		parent::loadOrderArrows();
		$this->smallmap=false;
	}

	// Always use the small standoff-Icons
	public function drawStandoff($terrName)
	{
		$this->smallmap=true;
		parent::drawStandoff($terrName);
		$this->smallmap=false;
	}
}

class EastIndiesVariant_drawMap extends ZoomMap_drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(239, 196, 228), // Bahmana
		2 => array(194, 197, 206), // Brunei
		3 => array(121, 175, 198), // Delhi
		4 => array(164, 196, 153), // Gondwana
		5 => array(108, 106, 193), // Majapahit
		6 => array( 67, 158,  63), // Malacca
		7 => array(160, 138, 117), // Mughalistan
		8 => array(196, 143, 133), // Persia
		9 => array(234, 234, 175), // Rajputana
		10 => array(249,  75,  72), // Ayutthaya
		11 => array(170,  99, 170), // Ternate
		12 => array(178, 147,  55), // Tondo
		13 => array(219, 219,  74), // Dai Viet
		14 => array(255, 171,  81)  // Vijayanagar
	);

	protected function resources() {

		global $Variant;

		return array(
			'map'     =>'variants/'.$Variant->name.'/resources/map.png',
			'names'   =>'variants/'.$Variant->name.'/resources/mapNames.png',
			'army'    =>'variants/'.$Variant->name.'/resources/army.png',
			'fleet'   =>'variants/'.$Variant->name.'/resources/fleet.png',
			'standoff'=>'images/icons/cross.png'
		);
	}
}
?>