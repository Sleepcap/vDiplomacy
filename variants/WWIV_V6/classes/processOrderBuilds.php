<?php
/*
	Copyright (C) 2013 kaner406 & Oliver Auth

	This file is part of the WWIV_V6 variant for webDiplomacy

	The WWIV_V6 variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.

	The WWIV_V6 variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class WWIV_V6Variant_processOrderBuilds extends WWIVVariant_processOrderBuilds
{
	protected $countryUnits = array(
		'Amazon-Empire' => array ('Manaus (man)'=>'Army', 'Belem (blm)'=>'Fleet', 'Guyana (guy)'=>'Fleet'),
		'Argentina' => array ('Patagonia (pat)'=>'Fleet', 'Rosario (rsr)'=>'Army', 'Buenos-Aries (bue)'=>'Army'),
		'Australia' => array ('Perth (per)'=>'Fleet', 'Sydney (syd)'=>'Fleet', 'Melbourne (mel)'=>'Army'),
		'Brazil' => array ('Sao Paulo (sao)'=>'Army', 'Rio De Janeiro (rio)'=>'Fleet', 'Belo Horizonte (blh)'=>'Army'),
		'California' => array ('San Francisco (sf)'=>'Fleet', 'Los Angeles (la)'=>'Army', 'Las Vegas (lv)'=>'Army'),
		'Canada' => array ('Edmonton (edm)'=>'Army', 'Vancouver (van)'=>'Fleet', 'Calgary (clg)'=>'Army'),
		'Catholica' => array ('Marseilles (mar)'=>'Army', 'Rome (rom)'=>'Fleet', 'Spain (spa) (South Coast)'=>'Fleet'),
		'Central-Asia' => array ('Kazakhstan (kaz)'=>'Army', 'Almaty (alm)'=>'Army', 'Uzbekistan (uzb)'=>'Army'),
		'Colombia' => array ('Barranquilla (brn)'=>'Fleet', 'Medellin (med)'=>'Fleet', 'Bogota (bog)'=>'Army'),
		'Kongo' => array ('Kinshasa (kin)'=>'Army', 'Libreville (lib)'=>'Fleet', 'Luanda (lua)'=>'Army'),
		'Cuba' => array ('Santa Clara (snc)'=>'Fleet', 'Guantanamo (gno)'=>'Fleet', 'Havana (hav)'=>'Fleet'),
		'East-Africa' => array ('Kenya (ken)'=>'Army', 'Mogadishu (mog)'=>'Fleet', 'Addis Ababa (ads)'=>'Army'),
		'North-Africa' => array ('Tunisia (tun)'=>'Fleet', 'Tripoli (tri)'=>'Army', 'Egypt (egy)'=>'Fleet'),
		'Germany' => array ('Munich (mun)'=>'Army', 'Hamburg (ham)'=>'Fleet', 'Prague (prg)'=>'Army'),
		'Central-States' => array ('St Louis (stl)'=>'Army', 'Wisconsin (wis)'=>'Army', 'Chicago (chi)'=>'Army'),
		'Inca-Empire' => array ('La Paz (lpz)'=>'Army', 'Valparaiso (val)'=>'Army', 'Lima (lim)'=>'Fleet'),
		'India' => array ('Delhi (del)'=>'Army', 'Calcutta (cal)'=>'Army', 'Bombay (bom)'=>'Fleet'),
		'Indonesia' => array ('Jakarta (jak)'=>'Fleet', 'Palembang (plm)'=>'Army', 'Surabaya (sby)'=>'Fleet'),
		'Iran' => array ('Shiraz (shi)'=>'Fleet', 'Tabriz (tab)'=>'Army', 'Tehran (teh)'=>'Army'),
		'Japan' => array ('Sapporo (sap)'=>'Fleet', 'Kyoto (kyo)'=>'Fleet', 'Tokyo (tok)'=>'Fleet'),
		'Manchuria' => array ('Shenyang (she)'=>'Fleet', 'Harbin (har)'=>'Army', 'Beijing (bei)'=>'Army'),
		'Mexico' => array ('Veracruz (vcz)'=>'Fleet', 'Mexico City (mxc)'=>'Army', 'Acapulco (aca)'=>'Army'),
		'Nigeria' => array ('Ghana (gha)'=>'Fleet', 'Lagos (lag)'=>'Army', 'Kano (kno)'=>'Army'),
		'Oceania' => array ('Pitcairn Island (pit)'=>'Fleet', 'Tahiti (tah)'=>'Fleet', 'Samoa (sam)'=>'Fleet'),
		'Philippines' => array ('Cebu (ceb)'=>'Fleet', 'Mindanao (min)'=>'Fleet', 'Manila (mnl)'=>'Fleet'),
		'Quebec' => array ('Montreal (mtl)'=>'Army', 'Newfoundland (nwf)'=>'Fleet', 'Quebec (qbc)'=>'Army'),
		'Russia' => array ('Murmansk (mur)'=>'Army', 'Moscow (mos)'=>'Army', 'Perm (prm)'=>'Army'),
		'Saudi-Arabia' => array ('Jeddah (jed)'=>'Army', 'Riyadh (riy)'=>'Army', 'Qatar (qat)'=>'Fleet'),
		'Sichuan-Empire' => array ('Xi\'an (xia)'=>'Army', 'Lanzhou (lnz)'=>'Army', 'Cheng Du (che)'=>'Army'),
		'Song-Empire' => array ('Hong Kong (hk)'=>'Fleet', 'Fuzhou (fuz)'=>'Fleet', 'Nanchang (nan)'=>'Army'),
		'South-Africa' => array ('Durban (dur)'=>'Army', 'Pretoria (pre)'=>'Army', 'Cape Town (cap)'=>'Fleet'),
		'Texas' => array ('Houston (hou)'=>'Fleet', 'San Antonio (san)'=>'Army', 'Dallas (dal)'=>'Army'),
		'Thailand' => array ('Rangoon (ran)'=>'Fleet', 'Korat Plateau (krt)'=>'Army', 'Bangkok (bnk)'=>'Army'),
		'Turkey' => array ('Istanbul (ist)'=>'Army', 'Izmir (izm)'=>'Army', 'Ankara (ank)'=>'Army'),
		'United-Kingdom' => array ('Ireland (ire)'=>'Fleet', 'London (lon)'=>'Fleet', 'Edinburgh (edi)'=>'Fleet'),
		'United-States' => array ('Carolinas (car)'=>'Army', 'Washington DC (wdc)'=>'Fleet', 'New York City (nyc)'=>'Fleet')
	);
}
