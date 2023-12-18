<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class Transform_OrderInterface extends BuildAnywhere_OrderInterface {

	protected function jsLoadBoard() {

		if(!$this->Variant->rules[RULE_TRANSFORM]){
			return parent::jsLoadBoard();
		}

		parent::jsLoadBoard();

		if( $this->phase=='Diplomacy' )
		{
			// Expanding order controls with transform command
			libHTML::$footerIncludes[] = l_jf('../variants/RuleExtensions/resources/4_Transform/transform.js');
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadOrdersPhase();','loadOrdersPhase();loadTransform();', $script);
		}
	}

}