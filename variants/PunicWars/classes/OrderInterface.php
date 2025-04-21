<?php
/*
	Copyright (C) 2010 Carey Jensen / Oliver Auth

	This file is part of the Sail Ho II variant for webDiplomacy

	The Sail Ho II variant for webDiplomacy" is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either
	version 3 of the License, or (at your option) any later version.

	The Sail Ho II variant for webDiplomacy is distributed in the hope that it
	will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Fog_OrderInterface extends OrderInterface {

	protected function jsLiveBoardData() {
	
		global $User, $DB, $Game;

		list($ccode)=$DB->sql_row("SELECT text FROM wD_Notices WHERE toUserID=3 AND timeSent=0 AND fromID=".$this->gameID);
		$verify=substr($ccode,((int)$Game->Members->ByUserID[$User->id]->countryID)*6,6);
		
		$jsonBoardDataFile = Game::mapFilename($this->gameID, ($this->phase=='Diplomacy'?$this->turn-1:$this->turn), 'json');
		$jsonBoardDataFile = str_replace(".map","-".$verify.".map",$jsonBoardDataFile);

		if( !file_exists($jsonBoardDataFile) )
		{
			$jsonBoardDataFile='variants/'.$Game->Variant->name.'/resources/fogmap.php?verify='.$verify.'&gameID='.$this->gameID.'&turn='.$this->turn.'&phase=';
			$jsonBoardDataFile.=$this->phase.'&mapType=json'.(defined('DATC')?'&DATC=1':'').'&nocache='.rand(0,1000);
		}
		else
			$jsonBoardDataFile.='?phase='.$this->phase.'&nocache='.rand(0,10000);

		return '<script type="text/javascript" src="'.STATICSRV.$jsonBoardDataFile.'"></script>';
	
	}
	
	protected function jsLoadBoard() {
		global $Game;
		
		parent::jsLoadBoard();

		if( $this->phase=='Diplomacy') {
			libHTML::$footerIncludes[] = '../variants/'.$Game->Variant->name.'/resources/supportfog.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadModel();','loadModel();SupportFog();', $script);
		}
	}

}

class PunicWarsVariant_OrderInterface extends Fog_OrderInterface {

	/**
	 * Call the parent constructor transparently to keep things working
	 */
	public function __construct($gameID, $variantID, $userID, $memberID, $turn, $phase, $countryID,
		setMemberOrderStatus $orderStatus, $tokenExpireTime, $maxOrderID=false)
	{
		parent::__construct($gameID, $variantID, $userID, $memberID, $turn, $phase, $countryID,
			$orderStatus, $tokenExpireTime, $maxOrderID);
	}

	protected function jsLoadBoard() {
		parent::jsLoadBoard();

		// Unit-Icons in javascript-code
		libHTML::$footerIncludes[] = '../variants/PunicWars/resources/iconscorrect.js';
		foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersModel();','loadOrdersModel();IconsCorrect();', $script);
	}


}

?>