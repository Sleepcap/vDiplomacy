<?php

defined('IN_CODE') or die('This script can not be run by itself.');
	

class ZoomMap_panelGameBoard extends panelGameBoard
{
	function mapHTML() {
		$mapTurn = (($this->phase=='Pre-game'||$this->phase=='Diplomacy') ? $this->turn-1 : $this->turn);
		$mapLink = 'map.php?gameID='.$this->id.'&turn='.$mapTurn.'&mapType=large';

		$html = parent::mapHTML();
		
		$old = '/img id="mapImage" src="(\S*)" alt=" " title="The small map for the current phase. If you are starting a new turn this will show the last turn\'s orders" \/>/';
		$new = 'iframe id="mapImage" src="'.$mapLink.'" alt=" " width="750" height="403"> </iframe>';
		
		$html = preg_replace($old,$new,$html);
		
		return $html;
	}
}

class WWIVsealanesVariant_panelGameBoard extends ZoomMap_panelGameBoard {}

