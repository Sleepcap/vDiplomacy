<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class GobbleEarthVariant_OrderInterface extends OrderInterface {

	protected function jsLoadBoard() {
		global $Variant, $Member;
		parent::jsLoadBoard();
		if( $this->phase=='Builds' )
		{
			// Build anywhere
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/buildanywhere.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadBoard();','loadBoard();SupplyCentersCorrect();', $script);
			
			// Rename Wait command.
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/renameWait.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadOrdersPhase();','loadOrdersPhase(); RenameWait();', $script);
				
			$homeBuilds='Array("'.implode($Variant->homeSCs[$Member->country], '","').'")';
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/renameSCsV2.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadBoard();','loadBoard();RenameSCs('.$homeBuilds.');', $script);
				
		}
	}
	
	public function html()
	{
		if( $this->phase=='Builds' )
		{
			global $DB;
			list($cB)=$DB->sql_row("SELECT COUNT(*) FROM wD_MovesArchive WHERE gameID=".$this->gameID." AND countryID=".$this->countryID." AND turn=".($this->turn - 2)." AND type='Wait'");

			return '<div style="text-align:center;">You have <b>'.$cB.'</b> colonial build'.($cB==1?'':'s').' this phase.</div>'.parent::html();
		}
		return parent::html();
	}
}
