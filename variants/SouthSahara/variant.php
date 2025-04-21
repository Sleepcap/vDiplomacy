<?php
/*
	Copyright (C) 2018 Oliver Auth

	This file is part of the SouthSahara variant for vDiplomacy

	The SouthSahara variant for vDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The SouthSahara variant for vDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with vDiplomacy. If not, see <http://www.gnu.org/licenses/>.

	---

	Changelog:
	1.0: initial version

	Code:
	0.1: Initial version
	0.1.1: Updated dates
	0.2: RuleExtensions variant pilot integration
	1.0: Release
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class SouthSaharaVariant extends RuleExtensionsVariant {
	public $id         = 149;
	public $mapID      = 149;
	public $name       = 'SouthSahara';
	public $fullName    ='South of Sahara';
	public $description ='The first variant set entirely in Sub-Saharan Africa.';
	public $author      ='David E. Cohen';
	public $adapter     ='Tobias Florin & Enriador';
	public $version     ='1.0';
	public $codeVersion ='1.0';
	public $homepage    ='https://davidecohen.wixsite.com/diplomiscellany/southofsahara';

	public $countries=array('Benin','Bonoman','Bornu','Jolof','Mali');

	public function __construct() {
		$this->rules[RULE_CUSTOM_MAP] = true;
		$this->rules[RULE_CUSTOM_ICONS] = true;
		$this->rules[RULE_BUILD_ANYHWERE] = true;

		parent::__construct();

		// custom country colors
		$this->variantClasses['drawMap'] = 'SouthSahara';
		// custom starting positions
		$this->variantClasses['adjudicatorPreGame'] = 'SouthSahara';
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1401);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1401);
		};';
	}
}
?>