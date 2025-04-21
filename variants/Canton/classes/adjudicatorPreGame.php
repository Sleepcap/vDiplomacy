<?php
/*
	Copyright (C) 2018 Enriador / Oliver Auth

	This file is part of the Canton variant for webDiplomacy

	The Canton variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Canton variant for webDiplomacy is distributed in the hope that it will
	be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/
defined('IN_CODE') or die('This script can not be run by itself.');

class CantonVariant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Britain' => array('Madras'   =>'Fleet', 'Delhi'   =>'Army',  'Calcutta'   => 'Army'),
		'France'  => array('Hanoi'    =>'Army' , 'Hue'     =>'Army' , 'Saigon'     =>'Fleet'),
		'Holland' => array('Sumatra'  =>'Fleet', 'Java'    =>'Fleet', 'Borneo'     =>'Fleet'),
		'Japan'   => array('Kyoto'    =>'Army' , 'Tokyo'   =>'Fleet', 'Sasebo'     =>'Fleet'),
		'Russia'  => array('Moscow'   =>'Army' , 'Irkutsk' =>'Army' , 'Sevastopol' =>'Fleet', 'Khabarovsk'=>'Fleet'),
		'China'   => array('Shanghai' =>'Army' , 'Peking'  =>'Fleet', 'Tibet'      =>'Army' , 'Chungking' =>'Army'),
		'Turkey'  => array('Damascus' =>'Army' , 'Baghdad' =>'Army' , 'Constantinople'  =>'Fleet')
	);

}
