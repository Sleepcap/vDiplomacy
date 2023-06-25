<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomIcons_OrderInterface extends RuleExtensionsVariant_OrderInterface_base {

	protected function jsLoadBoard() {

		if(!$this->Variant->rules[RULE_CUSTOM_ICONS]){
			return parent::jsLoadBoard();
		}

		parent::jsLoadBoard();

		if( $this->phase!='Builds' )
		{
			// Fix the icons in the board user interface
			libHTML::$footerIncludes[] = l_jf('../variants/RuleExtensions/resources/1_CustomIcons/iconscorrect.js');
			foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersPhase();','loadOrdersPhase();IconsCorrect("'.$this->Variant->name.'");', $script);
		}
	}

}