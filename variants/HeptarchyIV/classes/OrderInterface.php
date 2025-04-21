<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class HeptarchyIVVariant_OrderInterface extends OrderInterface
{
	protected function jsLoadBoard()
	{
		global $Variant;
		parent::jsLoadBoard();

		// Custom Unit-Icons in javascript-code
		if( $this->phase!='Builds' )
		{
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/iconscorrect.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadOrdersPhase();','loadOrdersPhase();IconsCorrect("'.$Variant->name.'");', $script);
		}
		
	}

}