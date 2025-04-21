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

defined('IN_CODE') or die('This script can not be run by itself.');

class WesternWorld_901Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
			'Arabia'    => array('Ardebil'   =>'Army' ,'Baghdad'       =>'Army' ,'Isfahan'     =>'Army' ,'Irak'     =>'Army' ),
			'Byzantinum'=> array('Taranto'   =>'Fleet','Constantinople'=>'Army' ,'Attalia'     =>'Fleet','Cherson'   =>'Fleet' ),
  			'Denmark'   => array('Jelling'   =>'Fleet','Viken'         =>'Army' ,'Scania'      =>'Fleet','Jorvik (East Coast)'=>'Fleet' ),
  			'Egypt'     => array("Damascus"=>'Army' ,'Alexandria'    =>'Army' ,'Barca'       =>'Fleet','Jerusalem' =>'Fleet' ),
  			'France'    => array('Paris'     =>'Fleet','Aquitaine'     =>'Army' ,'Gascony'     =>'Army' ,'Narbonne'  =>'Army' ),
  			'Germany'   => array('Bavaria'   =>'Army' ,'Swabia'        =>'Army' ,'Saxony'      =>'Army' ,'Bremen'    =>'Fleet' ),
			'Khazaria'  => array('Sarkel'    =>'Army' ,'Atil'          =>'Army' ,'Balanjar'    =>'Army' ,'Tamantarka'=>'Army' ),
  			'Rus'    => array('Novgorod'  =>'Fleet','Rostov'        =>'Army' ,'Smolensk'    =>'Army' ,'Kiev'      =>'Army' ),
  			'Spain'     => array('Cadiz'     =>'Fleet','Salamanca'     =>'Army' ,'Cordova'     =>'Army' ,'Valencia'  =>'Fleet' ),
  			'Neutral units'=> array(
				'Dublin'=>'Army' ,'Wessex'=>'Army' ,'Brittany'=>'Army' ,'Lothairingia'=>'Army',
				'Lower Burgundy'=>'Army','Pamplona'=>'Army', 'Mauretania'=>'Army','Corsica'=>'Army',
				'Sardinia'=>'Army','Rome'=>'Army','Sicily'=>'Army','Ifriqiya'=>'Army',
				'Crete'=>'Army','Cyprus'=>'Army','Thrace'=>'Army','Moravia'=>'Army',
				'Mazovia'=>'Army','Borussia'=>'Army','Esteland'=>'Army','Bjarmaland'=>'Army',
				'Bulgar'=>'Army','Georgia'=>'Army','Armenia'=>'Army','Azerbaijan'=>'Army',
				'Pechenega'=>'Army','Dalmatia'=>'Army','Bashkortostan'=>'Army','Urgench'=>'Army'),
	);

}

?>