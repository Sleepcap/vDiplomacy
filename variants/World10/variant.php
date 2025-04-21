<?php
/*
	Copyright (C) 2019 Mercy & Oliver Auth

	This file is part of the World X variant for webDiplomacy

	The World X variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The World X variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0: initial release

*/

class World10Variant extends WDVariant {
	public $id         = 129;
	public $mapID      = 129;
	public $name       = 'World10';
	public $fullName    ='World Diplomacy X';
	public $description ='A variant with a map which has territories over the whole globe.';
	public $author      ='Mercy';
	public $adapter     ='Mercy & Oliver Auth';
	public $version     ='X';
	public $codeVersion ='1.0.3';
	public $homepage    ='';

	public $countries=array('Argentina','Brazil','China','EU','Frozen','Ghana','India','Japan','Egypt','Near East','Siberia','Quebec','Russia','South Africa','USA','Canada','Australia');

	public function __construct() {
		parent::__construct();

		// Altered to load the correct resources and colors. Also a change to color-loading to account for
		// the large number of colors in this map.
		$this->variantClasses['drawMap']            = $this->name;

		// Altered to build the correct starting units
		$this->variantClasses['adjudicatorPreGame'] = $this->name;

		// Altered to display the country name in the global tab
		$this->variantClasses['Chatbox']            = $this->name;

		// Allow for some coasts to convoy
		$this->variantClasses['OrderInterface']     = $this->name;
		$this->variantClasses['userOrderDiplomacy'] = $this->name;

	}

	// Coasts that allow convoying. Oceanian Islands(183) and Hawaii(184)
	public $convoyCoasts = array ('183','184');


	public function turnAsDate($turn) {
		if ( $turn==-1 ) return l_t("Pre-game");
		else return ( $turn % 2 ? l_t("Autumn").", " : l_t("Spring").", " ).(floor($turn/2) + 2101);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return l_t("Pre-game");
			else return ( turn%2 ? l_t("Autumn")+", " : l_t("Spring")+", " )+(Math.floor(turn/2) + 2101);
		};';
	}
}

?>