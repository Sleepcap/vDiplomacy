<?php
/*
	Copyright (C) 2018 Technostar

	This file is part of the WorldAtWar1937 variant for webDiplomacy

	The WorldAtWar1937 variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License 
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The WorldAtWar1937 variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

class WorldAtWar1937Variant_adjudicatorPreGame extends adjudicatorPreGame
{
	protected $countryUnits = array(
		/*'Britain'  => array('Edinburgh'=>'Fleet','London'=>'Fleet','Liverpool'=>'Army','Gibraltar'=>'Fleet','Gold Coast'=>'Army','Singapore'=>'Fleet','Aden'=>'Fleet','Cape Town'=>'Fleet','Hong Kong'=>'Fleet','Bombay'=>'Fleet','Delhi'=>'Army','New South Wales'=>'Army'),
		'France'  => array('Brest'=>'Fleet','Paris'=>'Army','Marseilles'=>'Army','Algiers'=>'Army','Senegambia'=>'Fleet','Tongking'=>'Fleet','Saigon'=>'Army','Madagascar'=>'Fleet'),
		'Germany'  => array('Kiel'=>'Fleet','Berlin'=>'Army','Posen'=>'Army','Munich'=>'Army','Kamerun'=>'Army','Dar es Salaam'=>'Fleet','Wilhelmsland'=>'Fleet'),
		'Austria'  => array('Trieste'=>'Fleet','Sarajevo'=>'Army','Budapest'=>'Army','Vienna'=>'Army'),
		'Italy'  => array('Venice'=>'Fleet','Rome'=>'Army','Sicily'=>'Army','Naples'=>'Fleet','Eritrea'=>'Army'),
		'Holland'  => array('Holland'=>'Fleet','Borneo'=>'Army','Sumatra'=>'Fleet','Java'=>'Fleet','Transvaal'=>'Army'),
		'Russia'  => array('St. Petersburg (South Coast)'=>'Fleet','Moscow'=>'Army','Warsaw'=>'Army','Sevastopol'=>'Fleet','Orenburg'=>'Army','Vladivostok'=>'Fleet','Port Arthur'=>'Fleet','Irkutsk'=>'Army','Omsk'=>'Army','Rostov'=>'Army'),
		'Turkey'  => array('Constantinople'=>'Army','Smyrna'=>'Fleet','Angora'=>'Fleet','Cairo'=>'Army','Baghdad'=>'Fleet'),
		'China'  => array('Sinkiang'=>'Army','Peking'=>'Army','Shanghai'=>'Army','Kashgar'=>'Army','Nanking'=>'Army','Mukden'=>'Army','Canton'=>'Army'),
		'Japan' => array('Kyoto'=>'Fleet','Sapporo'=>'Fleet','Kyushu'=>'Fleet','Tokyo'=>'Army')
	*/
		'Britain'  => array('Nunavut (NNV)'=>'Fleet', 'Newfoundland (NFL)'=>'Fleet', 'South Central Canada (SCC)'=>'Army', 'Yukon Territory (YKT)'=>'Army',
							'Peru (PRU)'=>'Army', 'South Africa (SAF)'=>'Fleet', 'Congo (CNG)'=>'Fleet', 'Ivory Coast (IVC)'=>'Fleet',
							'London (LON)'=>'Army', 'Yorkshire (YOR)'=>'Fleet', 'Cairo (CAI)'=>'Fleet', 'Saudi Arabia (SDA)'=>'Army',
							'South India (SIN)'=>'Fleet', 'Singapore (SGP)'=>'Fleet', 'Perth (PER)'=>'Army'),
		'America'  => array('Alaska (ASK)'=>'Fleet', 'Washington (WSH)'=>'Fleet', 'New England (NEG)'=>'Fleet', 'St. Louis (STL)'=>'Army',
							'California (CLF)'=>'Fleet', 'Texas (TEX)'=>'Fleet', 'Florida (FLO)'=>'Fleet', 'Manila (MAN)'=>'Army',
							'Wake Island'=>'Army', 'Midway'=>'Army', 'Hawaii'=>'Fleet'),
		'France'   => array('French Guiana (FGU)'=>'Fleet', 'Argentina (AGT)'=>'Fleet', 'Cameroon (CMR)'=>'Fleet', 'Marseilles (MAR)'=>'Fleet',
							'Bordeaux (BDX)'=>'Army', 'Brittany (BTY)'=>'Fleet', 'Paris (PAR)'=>'Army', 'Madagascar (MDG)'=>'Fleet',
							'Tunis (TUN)'=>'Fleet', 'Indochina (IDC)'=>'Fleet'),
		'Germany'  => array('Kiel (KIE)'=>'Fleet', 'Rhineland (RHI)'=>'Army', 'Berlin (BER)'=>'Army', 'Prussia (PRU)'=>'Fleet',
							'Silesia (SIL)'=>'Army', 'Danzig (DAN)'=>'Army'),
		'Italy'    => array('Albania (ALB)'=>'Fleet', 'Venice (VEN)'=>'Army', 'Rome (ROM)'=>'Fleet', 'Naples (NAP)'=>'Army',
							'Benghazi (BGZ)'=>'Army', 'Somaliland (SOM)'=>'Fleet'),
		'Portugal' => array('Salvador (SVD)'=>'Army', 'Rio de Janeiro (RIO)'=>'Fleet', 'Sao Paulo (SPU)'=>'Army', 'Mozambique (MZB)'=>'Fleet',
							'Angola (AGL)'=>'Fleet', 'Portugal (PRG)'=>'Fleet'),
		'Mexico'   => array('Coahulia (COA)'=>'Fleet', 'Baja California (BJA)'=>'Army', 'Mexico City (MXC)'=>'Army', 'Yucatan (YCT) (North Coast)'=>'Fleet'),
		'Russia'   => array('Ukraine (UKR)'=>'Fleet', 'Leningrad (LNG)'=>'Fleet', 'Murmansk (MSK)'=>'Fleet', 'Moscow (MOS)'=>'Army',
							'Stalingrad (STA)'=>'Army', 'Western Siberia (WSB)'=>'Army', 'Northern Siberia (NSB)'=>'Fleet', 'Irkutsk (IRK)'=>'Army',
							'Kamchatka (KAM)'=>'Fleet', 'Vladivostok (VDV)'=>'Fleet'),
		'Turkey'   => array('Adana (ADA)'=>'Army', 'Istanbul (IST)'=>'Fleet', 'Ankara (ANK)'=>'Fleet'),
		'China'    => array('Western China (WCH)'=>'Army', 'Tibet (TIB)'=>'Army', 'Jiangxi (JXI)'=>'Army', 'Canton (CTO)'=>'Fleet'),
		'Japan'    => array('Manchuria (MCH)'=>'Fleet', 'Peking (PEK)'=>'Fleet', 'Taiwan (TAI)'=>'Army', 'Okinawa'=>'Fleet',
							'Korea (KOR)'=>'Army', 'Tokyo (TKY)'=>'Fleet', 'Hokkaido (HOK)'=>'Fleet'),
		'Thailand' => array('Chiang Mai (CHM)'=>'Army', 'Bangkok (BGK)'=>'Army', 'Phuket (PHU) (East Coast)'=>'Fleet')
	);
}