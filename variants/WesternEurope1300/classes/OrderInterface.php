<?php

defined('IN_CODE') or die('This script can not be run by itself.');

// Build anywhere:
class BuildAnywhere_OrderInterface extends OrderInterface {

	protected function jsLoadBoard() {
		global $Variant;
		parent::jsLoadBoard();

		// Expand the allowed SupplyCenters array to include non-home SCs.
		if( $this->phase=='Builds' )
		{
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/supplycenterscorrect.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadBoard();','loadBoard();SupplyCentersCorrect();', $script);
		}
	}
}

class WesternEurope1300Variant_OrderInterface extends BuildAnywhere_OrderInterface {}
