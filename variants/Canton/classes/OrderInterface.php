<?php

defined('IN_CODE') or die('This script can not be run by itself.');

// Unit-Icons in javascript-code
class CustomIcons_OrderInterface extends OrderInterface
{
	protected function jsLoadBoard() {
		parent::jsLoadBoard();

		libHTML::$footerIncludes[] = '../variants/'.$GLOBALS['Variants'][VARIANTID]->name.'/resources/iconscorrect.js';
		foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersModel();','loadOrdersModel();IconsCorrect("'.$GLOBALS['Variants'][VARIANTID]->name.'","'.$GLOBALS['Variants'][VARIANTID]->countries[$this->countryID -1].'");', $script);
	}
}

class CantonVariant_OrderInterface extends CustomIcons_OrderInterface {}

?>