<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class SouthSaharaVariant_drawMap extends RuleExtensionsVariant_drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), // Neutral
		1 => array(252,  64,  58), // Benin
		2 => array(121, 175, 198), // Bonoman
		3 => array(164, 196, 153), // Bornu
		4 => array(172, 101, 171), // Jolof
		5 => array(234, 234, 175)  // Mali
	);
}
?>