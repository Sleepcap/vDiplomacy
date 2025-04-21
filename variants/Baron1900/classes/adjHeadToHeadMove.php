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

class Baron1900Variant_adjHeadToHeadMove extends adjHeadToHeadMove
{
	protected function supportStrength($checkCountryID=false)
	{
		global $Game;
		
		$support = parent::supportStrength($checkCountryID);
		
		//check if current move is a move around tip of Southafrica -> less attack strength
		if(isset($Game->Variant->moveAroundAfrica) && in_array($this->id, $Game->Variant->moveAroundAfrica)){
			$support["min"] -= 0.5;
			$support["max"] -= 0.5;
		}
		
		return $support;
	}
}

