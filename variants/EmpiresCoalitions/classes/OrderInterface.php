<?php
/*
	Copyright (C) 2018 Enriador & Oliver Auth

	This file is part of the EmpiresCoalitions variant for vDiplomacy

	The EmpiresCoalitions variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The EmpiresCoalitions variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

// Some Coasts can convoy
class ConvoyCoasts_OrderInterface extends OrderInterface
{
	protected function jsLoadBoard()
	{
		global $Variant;
		parent::jsLoadBoard();

		if( $this->phase=='Diplomacy' )
		{
			$convoyCoastsJS='Array("'.implode($Variant->convoyCoasts, '","').'")';
			
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/coastConvoy.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadModel();','loadModel(); coastConvoy_loadModel('.$convoyCoastsJS.');', $script);
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadBoard();','loadBoard(); coastConvoy_loadBoard('.$convoyCoastsJS.');', $script);
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadOrdersPhase();','loadOrdersPhase(); coastConvoy_loadOrdersPhase('.$convoyCoastsJS.');', $script);
		}
	}
}

// Unit-Icons in javascript-code
class CustomIcons_OrderInterface extends ConvoyCoasts_OrderInterface
{
	protected function jsLoadBoard() {
		
		global $Variant;
		parent::jsLoadBoard();

		if( $this->phase!='Builds' )
		{
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/iconscorrect.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadOrdersModel();','loadOrdersModel(); IconsCorrect("'.$Variant->name.'","'.$Variant->countries[$this->countryID -1].'");', $script);
		}
	}
}

// Special additional Home-SCs
class AddHomeSCs_OrderInterface extends CustomIcons_OrderInterface {

	protected function jsLoadBoard() {
		global $Variant;
		parent::jsLoadBoard();

		// Expand the allowed SupplyCenters array to include non-home SCs.
		if( $this->phase=='Builds' )
		{
			if ($this->countryID == 9) $addSC = 63; // Spain -> Portigal
			if ($this->countryID == 8) $addSC = 70; // Sicily -> Papal States 
			if ($this->countryID == 3) $addSC = 8;  // Denmark -> Sweden 
			if ($this->countryID == 5) $addSC = 52; // Ottoman Empire  -> Egypt 
		
			if (isset($addSC))
			{
				libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/supplycenterscorrect.js';
				foreach(libHTML::$footerScript as $index=>$script)
					libHTML::$footerScript[$index]=str_replace('loadBoard();','loadBoard();SupplyCentersCorrect('.$addSC.');', $script);
			}
		}
	}
}

class EmpiresCoalitionsVariant_OrderInterface extends AddHomeSCs_OrderInterface {}

