<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomIcons_IAmap extends RuleExtensionsVariant_IAmap_base
{
    public function __construct($variant, $mapName = 'IA_smallmap.png')
    {
        parent::__construct($variant, $mapName);
                
        if($this->Variant->rules[RULE_CUSTOM_ICONS])
            $this->buildButtonAutogeneration = true;
    }

    protected function resources() {

        if(!$this->Variant->rules[RULE_CUSTOM_ICONS]){
			return parent::resources();
		}

		$resources = parent::resources();

		$resources['army'] = 'variants/'.$this->Variant->name.'/resources/smallarmy.png';
		$resources['fleet'] = 'variants/'.$this->Variant->name.'/resources/smallfleet.png';

		return $resources;
    }

    protected function setTransparancies() {}

    protected function jsFooterScript() {
        if(!$this->Variant->rules[RULE_CUSTOM_ICONS]){
			return parent::jsFooterScript();
		}

        libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army&variantID=".$this->Variant->id."';
                                interactiveMap.parameters.imgBuildFleet = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Fleet&variantID=".$this->Variant->id."';";

        parent::jsFooterScript();
    }
}