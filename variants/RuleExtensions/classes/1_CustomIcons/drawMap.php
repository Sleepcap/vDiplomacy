<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomIcons_drawMap extends CustomMap_drawMap {

	protected function resources() {

		if(!$this->Variant->rules[RULE_CUSTOM_ICONS]){
			return parent::resources();
		}

		$resources = parent::resources();

		if( $this->smallmap )
		{
			$resources['army'] = 'variants/'.$this->Variant->name.'/resources/smallarmy.png';
			$resources['fleet'] = 'variants/'.$this->Variant->name.'/resources/smallfleet.png';
		}
		else
		{
			$resources['army'] = 'variants/'.$this->Variant->name.'/resources/army.png';
			$resources['fleet'] = 'variants/'.$this->Variant->name.'/resources/fleet.png';
		}

		return $resources;
	}
}

?>