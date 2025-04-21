<?php
/*
	Copyright (C) 2018 Technostar / Oliver Auth

	This file is part of the Divided States variant for webDiplomacy

	The Divided States variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Divided States variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class NeutralUnits_OrderArchiv extends OrderArchiv
{	
	public function __construct()
	{
		parent::__construct();
		$this->countryIDToName[]='Neutrals';
	}
}

class Transform_OrderArchiv extends NeutralUnits_OrderArchiv
{
	public function OutputOrder($order)
	{
		if ($order['toTerrID'] > 1000)
		{
			$order['toTerrID'] = $order['toTerrID'] -1000;
			$order['type'] = 'transform';
			if ($order['toTerrID'] == $order['terrID'])
				$order['toTerrID']=false;		
		}
		return parent::OutputOrder($order);
	}
}

class Divided_StatesVariant_OrderArchiv extends Transform_OrderArchiv {}
