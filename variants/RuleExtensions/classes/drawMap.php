<?php

defined('IN_CODE') or die('This script can not be run by itself.');

// by default we extend the classic variant for loading resources and country colors
class RuleExtensionsVariant_drawMap_base extends ClassicVariant_drawMap 
{
	public $Variant;

	public function __construct($Variant, $smallmap)
	{
		$this->Variant = $Variant;

		parent::__construct($smallmap);
	}
}

require_once('0_CustomMap/drawMap.php');
require_once('1_CustomIcons/drawMap.php');
require_once('2_CustomIconsPerCountry/drawMap.php');

class RuleExtensionsVariant_drawMap  extends CustomIconsPerCountry_drawMap {}

?>