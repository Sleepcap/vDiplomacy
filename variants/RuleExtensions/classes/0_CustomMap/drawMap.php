<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomMap_drawMap extends RuleExtensionsVariant_drawMap_base {

	protected function resources() {

		if(!$this->Variant->rules[RULE_CUSTOM_MAP]){
			return parent::resources();
		}

		$resources = parent::resources();

		if( $this->smallmap )
		{
			$resources['map'] = 'variants/'.$this->Variant->name.'/resources/smallmap.png';
			$resources['names'] = 'variants/'.$this->Variant->name.'/resources/smallmapNames.png';
		}
		else
		{
			$resources['map'] = 'variants/'.$this->Variant->name.'/resources/map.png';
			$resources['names'] = 'variants/'.$this->Variant->name.'/resources/mapNames.png';
		}

		return $resources;
	}
}

?>