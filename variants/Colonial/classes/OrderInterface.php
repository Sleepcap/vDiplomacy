<?php
/*
	Copyright (C) 2011 Oliver Auth / 2014 Tobias Florin

	This file is part of the Duo variant for webDiplomacy

	The Duo variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Duo variant for webDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class TransSib_OrderInterface extends OrderInterface
{
	protected function jsLoadBoard()
	{
		global $Variant;
		parent::jsLoadBoard();

		if( $this->phase=='Diplomacy' )
		{
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/transSib.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadOrdersPhase();','loadOrdersPhase();loadTransSib();', $script);
		}
	}
}

class SuezCanal_OrderInterface extends TransSib_OrderInterface
{
        protected function jsLoadBoard()
	{
		global $Variant;
		parent::jsLoadBoard();

		if( $this->phase=='Diplomacy' )
		{
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/suez.js';
                        libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/supportValueFix.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadTransSib();','loadTransSib();loadSuez();fixOrders();', $script);
		}
	}
        
        public function load() 
        {
                global $DB;
                
                $this->switchSuezTo(8);
				
                //Check if a player is allowed to enter a special "Suez Order" (allowing a fleet to use the Suez)
                if($this->phase == 'Diplomacy')
                {
                        $country = $DB->sql_row('SELECT u.countryID FROM wD_Units u INNER JOIN wD_TerrStatus t ON u.id = t.occupyingUnitID WHERE t.terrID = 66 AND t.gameID = '.$this->gameID); //66 = Egypt

                        if($country)
                                $this->switchSuezTo($country[0]);
                }
                
                parent::load();
        }
        
        private function switchSuezTo($countryID){
                global $DB;
				
                $DB->sql_put('UPDATE wD_Units u INNER JOIN wD_Orders o ON u.id = o.unitID SET u.countryID = '.$countryID.', o.countryID = '.$countryID.' WHERE u.gameID = '.$this->gameID.' AND u.terrID = 126');
        }
}

class ColonialVariant_OrderInterface extends SuezCanal_OrderInterface {}