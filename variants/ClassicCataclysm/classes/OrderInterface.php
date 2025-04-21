<?php

defined('IN_CODE') or die('This script can not be run by itself.');

// Build anywhere:
class ClassicCataclysmVariant_OrderInterface extends OrderInterface
{
	protected function jsLoadBoard()
	{
		global $Variant;
		parent::jsLoadBoard();
		
		$landTerrs='Array("'.implode($Variant->landTerrs, '","').'")';

		libHTML::$footerIncludes[] = '../variants/ClassicCataclysm/resources/units_and_icons_correct_V1.02.js';
		foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersPhase();','loadOrdersPhase(); NewUnitNames();', $script);			
		foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersModel();','loadOrdersModel();IconsCorrect('.$landTerrs.');', $script);
	}
}
