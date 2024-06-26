<?php
/*
	Copyright (C) 2023 Tobias Florin

	This file is part of the EastIndies variant for vDiplomacy

	The EastIndies variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The EastIndies variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');
	

class ZoomMap_panelGameBoard extends panelGameBoard
{
	function mapHTML() {
		$mapTurn = (($this->phase=='Pre-game'||$this->phase=='Diplomacy') ? $this->turn-1 : $this->turn);
		$mapLink = 'map.php?gameID='.$this->id.'&turn='.$mapTurn.'&mapType=large';

		$html = parent::mapHTML();
		
		$old = '/img id="mapImage" src="(\S*)" alt=" " title="The small map for the current phase. If you are starting a new turn this will show the last turn\'s orders" \/>/';
		$new = 'iframe id="mapImage" src="'.$mapLink.'" alt=" " width="750" height="590"> </iframe>';
		
		$html = preg_replace($old,$new,$html);
		
		return $html;
	}
}

class EastIndiesVariant_panelGameBoard extends ZoomMap_panelGameBoard {}
