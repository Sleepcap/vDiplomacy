<?php
/*
	Copyright (C) 2018 Yuriy Hryniv aka Flame

	This file is part of the WesternWorld_901 variant for webDiplomacy

	The WesternWorld_901 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The WesternWorld_901 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

// Build anywhere
class BuildAnywhere_OrderInterface extends OrderInterface {

	protected function jsLoadBoard()
	{
		global $Variant;
		parent::jsLoadBoard();

		if( $this->phase=='Builds' )
		{
			libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/buildanywhere.js';
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace('loadBoard();','loadBoard();SupplyCentersCorrect();', $script);
		}
	}
}

// Unit-Icons in javascript-code
class CustomIcons_OrderInterface extends BuildAnywhere_OrderInterface
{
	protected function jsLoadBoard() {
		parent::jsLoadBoard();

		libHTML::$footerIncludes[] = '../variants/WesternWorld_901/resources/iconscorrect.js';
		foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersModel();','loadOrdersModel();IconsCorrect('.$this->countryID.');', $script);
	}
}

class WesternWorld_901Variant_OrderInterface extends CustomIcons_OrderInterface {}



