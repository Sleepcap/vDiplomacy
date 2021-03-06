<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class Classic1898FogVariant_OrderInterface extends OrderInterface {

	protected function jsLiveBoardData() {
	
		global $User, $DB, $Game;

		list($ccode)=$DB->sql_row("SELECT text FROM wD_Notices WHERE toUserID=3 AND timeSent=0 AND fromID=".$this->gameID);
		$verify=substr($ccode,((int)$Game->Members->ByUserID[$User->id]->countryID)*6,6);
		
		$jsonBoardDataFile = Game::mapFilename($this->gameID, ($this->phase=='Diplomacy'?$this->turn-1:$this->turn), 'json');
		$jsonBoardDataFile = str_replace(".map","-".$verify.".map",$jsonBoardDataFile);

		if( !file_exists($jsonBoardDataFile) )
			$jsonBoardDataFile='variants/Classic1898Fog/resources/fogmap.php?verify='.$verify.'&gameID='.$this->gameID.'&turn='.$this->turn.'&phase='.$this->phase.'&mapType=json'.(defined('DATC')?'&DATC=1':'').'&nocache='.rand(0,1000);
		else
			$jsonBoardDataFile.='?phase='.$this->phase.'&nocache='.rand(0,10000);

		return '<script type="text/javascript" src="'.STATICSRV.$jsonBoardDataFile.'"></script>';
	
	}
	
	protected function jsLoadBoard() {
		parent::jsLoadBoard();

		// The Staring unit in Benevatto can't move...
		if( $this->phase=='Diplomacy') {
			libHTML::$footerIncludes[] = '../variants/Classic1898Fog/resources/supportfog.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadModel();','loadModel();SupportFog();', $script);
		}

		if( $this->phase=='Builds' )
		{
			// Expand the allowed SupplyCenters array to include non-home SCs.
			libHTML::$footerIncludes[] = l_jf('../variants/Classic1898Fog/resources/supplycenterscorrect.js');
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace(l_jf('loadBoard').'();',l_jf('loadBoard').'();'.l_jf('SupplyCentersCorrect').'();', $script);
		}
	}
	
}