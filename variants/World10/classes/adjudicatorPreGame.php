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
*/

class World10Variant_adjudicatorPreGame extends adjudicatorPreGame {

	protected $countryUnits = array(
		'Argentina'    => array('Santa Cruz' =>'Army' , 'Buenos Aires' =>'Fleet', 'Chile' =>'Fleet'),
		'Brazil'       => array('Brasillia' =>'Army' , 'Rio de Janeiro' =>'Army' , 'Recife' =>'Fleet'),
		'China'        => array('Shanghai' => 'Army','Guangzhou' => 'Fleet','Lanzhou' => 'Army'),
		'EU'           => array('France (North Coast)' => 'Fleet','Italy' => 'Fleet','Germany' => 'Army'),
		'Frozen'       => array('Marie Byrd Land' => 'Fleet','Wilkes Land' => 'Fleet','Maud Land' => 'Fleet'),
		'Ghana'        => array('Ghana' =>'Army' , 'Mali' =>'Army' , 'Guinea' =>'Fleet'),
		'India'        => array('Calcutta' =>'Army' , 'Delhi' =>'Army' , 'Bombay' =>'Fleet'),
		'Japan'        => array('Hokkaido' => 'Fleet','Honshu' => 'Fleet','Korea' => 'Army'),
		'Egypt'        => array('Libya' => 'Army','Egypt' => 'Fleet','Sudan' => 'Army'),
		'Near East'    => array('Saudi Arabia' => 'Army','Iraq' => 'Army','Syria' => 'Army'),
		'Siberia'      => array('Kamchatka' => 'Fleet','Yakutsk' => 'Army','Norilsk' => 'Army'),
		'Quebec'       => array('Quebec' =>'Army' , 'Newfoundland' =>'Fleet', 'Ontario' =>'Fleet'),
		'Russia'       => array('Belorussia' =>'Army' , 'Saint Petersburg' =>'Army' , 'Moscow' =>'Army' ),
		'South Africa' => array('South Africa' => 'Fleet','Namibia' => 'Army','Mozambique' => 'Army'),
		'USA'          => array('Texas' =>'Army' , 'California' =>'Fleet', 'Florida' =>'Fleet'),
		'Canada'       => array('British Columbia' => 'Fleet','Yukon' => 'Army','Northwest Territories' => 'Fleet'),
		'Australia'    => array('Victoria' => 'Fleet','Western Australia' => 'Fleet','New South Wales' => 'Fleet')
	);
}