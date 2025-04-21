<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class MongolianEmpireVariant_adjudicatorPreGame extends adjudicatorPreGame
{
	protected $countryUnits = array(
		'The Caliphate' => array('Axum (South Coast)'=>'Fleet', 'Yemen'=>'Fleet', 'Mecca'=>'Army', 'Ayyubid'=>'Fleet', 'Chadic'=>'Army', 'Abbasid'=>'Army', 'Makura'=>'Army', 'Almohad'=>'Fleet', 'Berber'=>'Army'),
		'Crusader States' => array('Spain'=>'Army', '#2 Moscow'=>'Army', 'Teutonic Order'=>'Fleet', 'Western Byzantine (South Coast)'=>'Fleet', 'Lithuania'=>'Army', 'Crusader States'=>'Army', 'The Danes'=>'Fleet', 'Hungary'=>'Fleet', 'Venice'=>'Fleet', 'Norsk Territories'=>'Fleet', 'Poland'=>'Army'),
		'Yuan Dynasty' => array('Nan Chao'=>'Army', 'Laos'=>'Army', 'Hanoi'=>'Army', 'Fokien'=>'Fleet', 'Nanqi'=>'Fleet', 'Xanton'=>'Army', 'Honao'=>'Fleet', 'Sanchi'=>'Army', 'Xiam'=>'Army', 'Peking'=>'Army', 'Quan'=>'Army', 'Sinfai'=>'Fleet'),
		'Delhi Sultanate' => array('Nepal East'=>'Army', 'Nepal West'=>'Army', 'Bengal'=>'Army', '#6 Kandahar'=>'Fleet', 'Sindh'=>'Army', 'Malwa'=>'Fleet', 'Kamataka'=>'Fleet'),
		'Tibet' => array('Hindhu Kush'=>'Army', 'Nagqu'=>'Army', '#8 Lhasar'=>'Army', 'Chamdo'=>'Army', 'Qamdo'=>'Army', 'Bhutan'=>'Army', 'Nyingchi'=>'Army', 'Xigaze'=>'Army', 'Ngari'=>'Army'),
		'Sirivijaya' => array('Sumatra'=>'Fleet', 'Flores Sea'=>'Fleet', 'Sarawak'=>'Fleet', 'Borneo Bay'=>'Fleet', 'Luzon'=>'Fleet', 'Java Isles'=>'Fleet', 'Borneo'=>'Fleet'),
		'Golden Horde' => array('Bulgars'=>'Army', 'Muscovy'=>'Army', 'Bashkir'=>'Army', 'Riga'=>'Army', 'Urgrian'=>'Army', 'Novogorad'=>'Army', 'Bjarmaland'=>'Fleet'),
		'Chagatai Khanate' => array('Khuram'=>'Army', 'Pamir'=>'Army', 'Furghana'=>'Army', 'Kudchan'=>'Army', 'Samoyedes'=>'Army', 'Naiman'=>'Army', 'Carazan'=>'Army', '#7 Almalik'=>'Army'),
		'Ilkhanate' => array('Seljuk'=>'Army', 'Eastern Byzantine'=>'Army', 'Zangid'=>'Army', 'Choralan'=>'Fleet', '#3 Bagdhad'=>'Fleet', 'Armenia'=>'Army', 'Herat'=>'Army', 'Merv'=>'Army', 'Georgia'=>'Army', 'Diyarnakir'=>'Army'),
		'The Great Khans' => array('Sungar'=>'Fleet', 'Yupi (East Coast)'=>'Fleet', 'South Cathay'=>'Fleet', 'North Cathay'=>'Army', 'Bargul'=>'Army', 'Stingui'=>'Army', 'Lop'=>'Army', 'Kilango'=>'Army', 'Leaotung'=>'Army', 'Corea'=>'Fleet', 'Xanadu'=>'Army', 'Curguth'=>'Army', 'Serra'=>'Army'),
		'Khmer Empire' => array('Lower Pagan'=>'Fleet', 'Siam'=>'Army', 'Pegu'=>'Fleet', 'Khmer'=>'Army', 'Champa'=>'Army', '#13 Angkor'=>'Army'),
		'Neutral units' => array('#1 Jerusalem'=>'Army', '#4 Samarkand'=>'Army', '#5 Khotum'=>'Army', '#9 Si-Ning-Fu'=>'Army', '#10 Kharakorum'=>'Army', '#11 Lo-Yang'=>'Army', '#12 Kainan'=>'Army')
	);
}