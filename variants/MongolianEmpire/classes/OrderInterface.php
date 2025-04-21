<?php

// Medival Unit-Icons in javascript-code
class CustomIcons_OrderInterface extends OrderInterface
{
	protected function jsLoadBoard() {

		global $Variant;
		parent::jsLoadBoard();

		if( $this->phase!='Builds' )
		{
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/iconscorrect.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadOrdersModel();','loadOrdersModel();IconsCorrect("'.$Variant->name.'");', $script);
		}
	}
}

// Build anywhere:
class BuildAnywhere_OrderInterface extends CustomIcons_OrderInterface
{
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
class MongolianEmpireVariant_OrderInterface extends BuildAnywhere_OrderInterface {}

