<?php
/*
	Copyright (C) 2010 Oliver Auth / 2014 Tobias Florin

	This file is part of the Colonial variant for webDiplomacy

	The Colonial variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Colonial variant for webDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of 
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class NeutralMember
{
	public $supplyCenterNo;
	public $unitNo;
}

class NeutralUnits_processMembers extends processMembers
{
	// bevore the processing add a minimal-member-object for the "neutral  player"
	function countUnitsSCs()
	{
		$this->ByCountryID[count($this->Game->Variant->countries)+1] = new NeutralMember();
		parent::countUnitsSCs();
		unset($this->ByCountryID[count($this->Game->Variant->countries)+1]);
	}
}

class HongKong_processMembers extends NeutralUnits_processMembers {

	function countUnitsSCs()
	{
		parent::countUnitsSCs();

		// After the Support-Center-Counter got updated check if HongKong is occupied by china.
		// If that's true subtract 1 for Chinas total SC's
		
		global $DB;
		list($owner_hk)=$DB->sql_row("SELECT countryID 
			FROM wD_TerrStatus
			WHERE (gameID=".$this->Game->id." AND terrID=9)");
		if ($owner_hk == 2) 
		{
			$this->ByCountryID[2]->supplyCenterNo--;
			$DB->sql_put("UPDATE wD_Members 
				SET	supplyCenterNo = ". $this->ByCountryID[2]->supplyCenterNo ."
				WHERE (gameID = ".$this->Game->id." AND countryID = 2)");
		};
	
	}	

}

class Suez_processMembers extends HongKong_processMembers {
	
	// remove a Suez order unit from units before counting SCs (in a way that works with DATC test)
	function countUnitsSCs() {
		global $DB;
		
		$DB->sql_put("UPDATE wD_Units u SET u.countryID = 8 WHERE u.terrID = 126 AND u.gameID = ".$this->Game->id); //126 = Egypt
			
		parent::countUnitsSCs();
	}
}

class ColonialVariant_processMembers extends Suez_processMembers {}

?>
