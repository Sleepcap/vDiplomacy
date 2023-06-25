<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class RuleExtensionsVariant_IAmap_base extends IAmap 
{
	public $Variant;

	public function __construct($Variant, $mapName = 'IA_smallmap.png')
	{
		$this->Variant = $Variant;

        if(!$this->Variant->rules[RULE_CUSTOM_MAP]){
            // by default, construct the interactive map with a Classic variant instance to laod classic map data
            $classicVariant = libVariant::loadFromVariantName('Classic');
            parent::__construct($classicVariant, $mapName);
        } else {
            parent::__construct($Variant, $mapName);
        }
	}
}

require_once('1_CustomIcons/interactiveMap.php');

class RuleExtensionsVariant_IAmap extends CustomIcons_IAmap {}