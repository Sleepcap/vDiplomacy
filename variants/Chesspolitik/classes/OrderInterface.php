<?php

defined('IN_CODE') or die('This script can not be run by itself.');

// Unit-Icons in javascript-code
class CustomIcons_OrderInterface extends OrderInterface
{
	protected function jsLoadBoard() {
		
		global $Variant;
		parent::jsLoadBoard();

		if( $this->phase!='Builds' )
		{
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/iconscorrect.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadOrdersModel();','loadOrdersModel();IconsCorrect("'.$Variant->name.'","'.$Variant->countries[$this->countryID -1].'");', $script);
		}
	}
}

// New Unit-names in javascript-code
class CustomIconNames_OrderInterface extends CustomIcons_OrderInterface
{
	protected function jsLoadBoard() {
		global $Variant;
		parent::jsLoadBoard();

		libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/unitnames.js';
		foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersPhase();','loadOrdersPhase(); NewUnitNames();', $script);			
	}
}

// Special additional Home-SCs
class AddHomeSCs_OrderInterface extends CustomIconNames_OrderInterface {

	protected function jsLoadBoard() {
		global $Variant;
		parent::jsLoadBoard();

		// Expand the allowed SupplyCenters array to include non-home SCs.
		if( $this->phase=='Builds' )
		{
			$addSCs = ChesspolitikVariant::countryAdditionalSCsByID($this->countryID); 
		
			if (isset($addSCs))
			{
				libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/supplycenterscorrect.js';
				foreach(libHTML::$footerScript as $index=>$script)
					libHTML::$footerScript[$index]=str_replace('loadBoard();','loadBoard();SupplyCentersCorrect(["'.implode('","', $addSCs).'"]);', $script);
			}
		}
	}
}

class ChesspolitikVariant_OrderInterface extends AddHomeSCs_OrderInterface {}
