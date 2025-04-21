<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class AtlanticColoniesVariant_OrderInterface extends OrderInterface {

	// Unit-Icons in javascript-code
	protected function jsLoadBoard()
	{
		parent::jsLoadBoard();
		
		libHTML::$footerIncludes[] = '../variants/AtlanticColonies/resources/iconscorrect.js';
		foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersModel();','loadOrdersModel();IconsCorrect('.$this->countryID.');', $script);
	}

}
