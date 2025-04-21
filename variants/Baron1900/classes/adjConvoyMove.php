<?php
/*
	Copyright (C) 2016 Tobias Florin

	This file is part of the 1900 variant for webDiplomacy

	The 1900 variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.

	The 1900 variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Baron1900Variant_adjConvoyMove extends adjConvoyMove
{
	public $MedConvoyingUnits;
	
	/*
	 * If this is the Convoy Move to egypt, load convoying fleets needed in the 
	 * Med to check, if there is a convoy to Egypt via Med possible later, that
	 * would result in full attack strenght instead of half as it would be around
	 * South Africa
	 */
	public function setUnits(array $units, &$chain = false) {
		global $DB, $Game;
		
		if(!is_array($this->MedConvoyingUnits)){
			$this->MedConvoyingUnits = array();
		
			if(isset($Game->Variant->ConvoyMoveToEgypt) && $this->id == $Game->Variant->ConvoyMoveToEgypt){
				//check if all relevant fleets have set the convoy
				$medConvoyNumber = $DB->sql_row(
				"SELECT count(c.id) FROM wD_Moves c
				WHERE c.moveType='Convoy' AND c.terrID IN(".implode(',',Baron1900Variant::$mediterraneanTerritories).")
					AND c.toTerrID=".Baron1900Variant::$egyptID."
					AND c.fromTerrID=(SELECT mao.fromTerrID FROM wD_Moves mao
									WHERE mao.id=".$Game->Variant->MidAtlanticConvoying.")
					AND c.gameID = ".$GLOBALS['GAMEID']);
		
				if($medConvoyNumber[0]==sizeof(Baron1900Variant::$mediterraneanTerritories)){
					/*
					 * There are enough convoying units in the Med to build a complete 
					 * convoy chain, so we can build the array of units, that are needed
					 * for the convoy and might be dislodged
					 */
		
					$convoyingUnits = $DB->sql_tabl(
					"SELECT c.id FROM wD_Moves c
					WHERE c.moveType='Convoy' AND c.terrID IN(".implode(',',Baron1900Variant::$mediterraneanTerritories).")
						AND c.toTerrID=".Baron1900Variant::$egyptID."
						AND c.fromTerrID=(SELECT mao.fromTerrID FROM wD_Moves mao
										WHERE mao.id=".$Game->Variant->MidAtlanticConvoying.")
						AND c.dislodged!='No'
						AND c.gameID = ".$GLOBALS['GAMEID']);
		
					while( list($id) = $DB->tabl_row($convoyingUnits))
							$this->MedConvoyingUnits[]=$units[$id];
				}else{
					$this->MedConvoyingUnits[]='NoConvoy';//mark the path as non-successful
				}
			}		
		}
		
		return parent::setUnits($units, $chain);
	}
	
	protected function supportStrength($checkCountryID=false)
	{
		global $Game; 
		
		$support = parent::supportStrength($checkCountryID);
		
		/*
		 * check if current convoy is a convoy around tip of Southafrica -> less attack Strength
		 * 
		 * if move is to Hejaz, half attack strength is clear, as the only path 
		 * is around South Africa
		 * 
		 * if move is to Egypt, it has to be checked, if a path through Med is
		 * available -> full attack strength
		 */
		
		if(isset($Game->Variant->ConvoyMoveToHejaz) && $this->id == $Game->Variant->ConvoyMoveToHejaz){
			$support["min"] -= 0.5;
			$support["max"] -= 0.5;
		}
		elseif(isset($Game->Variant->ConvoyMoveToEgypt) && $this->id == $Game->Variant->ConvoyMoveToEgypt){
			//check if path through Med is available
			try
			{
				// No path through Med available -> Reduction of attack strength
				if ( !$this->pathThroughMed() ){
					$support["min"] -= 0.5;
					$support["max"] -= 0.5;
				}
					
			}
			catch(adjParadoxException $p)
			{
				// We might end up with no path through Med
				$support["min"] -= 0.5;
				
				if(isset($support["paradox"])) $support["paradox"]->downSizeTo($p);
				else $support["paradox"] = $p;
			}
		}
		
		
		return $support;
	}
	
	/*
	 * Check if a Path through Med is available by checking no relevant unit is
	 * dislodged.
	 */
	protected function pathThroughMed()
	{
		if(sizeof($this->MedConvoyingUnits) == 1 && $this->MedConvoyingUnits[0]=='NoConvoy')
			return false;
		
		foreach($this->MedConvoyingUnits as $unit)
		{
			try
			{
				if ( $unit->dislodged() )
					return false;
			}
			catch(adjParadoxException $pe)
			{
				if ( isset($p) ) $p->downSizeTo($pe);
				else $p = $pe;
			}
		}
		
		if ( isset($p) ) throw $p;
		
		return true;
	}
}

