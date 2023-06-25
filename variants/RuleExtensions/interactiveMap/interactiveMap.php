<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class RuleExtensionsVariant_IAmap_base extends IAmap 
{
	public $Variant;

	public function __construct($Variant)
	{
		$this->Variant = $Variant;

        // by default, construct the interactive map with a Classic variant instance to laod classic map data
        $classicVariant = libVariant::loadFromVariantName('Classic');
		parent::__construct($classicVariant);
	}
}

class RuleExtensionsVariant_IAmap extends RuleExtensionsVariant_IAmap_base {}