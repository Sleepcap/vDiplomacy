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

class Baron1900Variant_adjSupportMove extends adjSupportMove
{
	
	/* 
	 * Attacks from units moving around South Africa are only included if they
	 * dislodge our unit.
	 */
	protected function attacked()
	{
		global $Game;
		
		foreach($this->attackers as $attacker)
		{
			//ignore moves around South Africa, if attack doesn't result in our unit being dislodged
			if(isset($Game->Variant->moveAroundAfrica) && in_array($attacker->id, $Game->Variant->moveAroundAfrica) && !$attacker->success())
				continue;
					
			//Original code:
			if ( isset($this->supporting->defender) )
				if ( $attacker->id == $this->supporting->defender->id )
					continue; // The unit attacking me is the unit I'm supporting against
			
			try
			{
				if ( $attacker->compare('attackStrength','>',0) )
					return true;
			}
			catch(adjParadoxException $pe)
			{
				if ( isset($p) ) $p->downSizeTo($pe);
				else $p = $pe;
			}
		}
		
		if ( isset($p) ) throw $p;
		else return false;
	}
}

