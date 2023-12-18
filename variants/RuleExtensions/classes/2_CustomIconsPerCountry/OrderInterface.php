<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class CustomIconsPerCountry_OrderInterface extends CustomIcons_OrderInterface {

	protected function jsLoadBoard() {

		if(!$this->Variant->rules[RULE_CUSTOM_ICONS_PER_COUNTRY]){
			return parent::jsLoadBoard();
		}

		parent::jsLoadBoard();

		if( $this->phase!='Builds' )
		{
			// Fix the icons in the board user interface
			libHTML::$footerIncludes[] = l_jf('../variants/RuleExtensions/resources/2_CustomIconsPerCountry/iconscorrect.js');
			foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersPhase();','loadOrdersPhase();IconsCorrect("'.$this->Variant->name.'","'.$this->Variant->countries[$this->countryID-1].'");', $script);
		}
	}

}