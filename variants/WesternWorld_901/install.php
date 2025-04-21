<?php
// This is file installs the map data for the WesternWorld_901 variant
defined('IN_CODE') or die('This script can not be run by itself.');
require_once("variants/install.php");

InstallTerritory::$Territories=array();
$countries=$this->countries;
$territoryRawData=array(
	array('Munster', 'Coast', 'No', 0, 79, 414, 79, 414),
	array('Dublin', 'Coast', 'Yes', 10, 119, 398, 119, 398),
	array('Icelandic Sea', 'Sea', 'No', 0, 185, 174, 185, 174),
	array('Caledonia', 'Coast', 'No', 3, 154, 320, 154, 320),
	array('Jorvik', 'Coast', 'Yes', 3, 188, 384, 188, 384),
	array('Jorvik (East Coast)', 'Coast', 'No', 3, 203, 395, 203, 395),
	array('Jorvik (West Coast)', 'Coast', 'No', 3, 168, 390, 168, 390),
	array('Wales', 'Coast', 'No', 0, 163, 442, 163, 442),
	array('Welsh Sea', 'Sea', 'No', 0, 124, 472, 124, 472),
	array('Wessex', 'Coast', 'Yes', 10, 244, 457, 244, 457),
	array('North German Sea', 'Sea', 'No', 0, 281, 329, 281, 329),
	array('South German Sea', 'Sea', 'No', 0, 320, 383, 320, 383),
	array('Brittish Channel', 'Sea', 'No', 0, 175, 509, 175, 509),
	array('Ocean Sea', 'Sea', 'No', 0, 53, 542, 53, 542),
	array('Norway', 'Coast', 'No', 0, 355, 260, 355, 260),
	array('Uppland', 'Coast', 'No', 0, 489, 250, 489, 250),
	array('Viken', 'Coast', 'Yes', 3, 419, 305, 419, 305),
	array('Gottland', 'Coast', 'No', 3, 480, 340, 480, 340),
	array('Scania', 'Coast', 'Yes', 3, 434, 368, 434, 368),
	array('Saamiland', 'Coast', 'No', 0, 610, 113, 610, 113),
	array('Saamiland (North Coast)', 'Coast', 'No', 0, 573, 68, 573, 68),
	array('Saamiland (South Coast)', 'Coast', 'No', 0, 672, 160, 672, 160),
	array('Bjarmaland', 'Coast', 'Yes', 10, 860, 216, 860, 216),
	array('Komia', 'Coast', 'No', 0, 1087, 170, 1087, 170),
	array('White Sea', 'Sea', 'No', 0, 1062, 47, 1062, 47),
	array('Stone Belt', 'Land', 'No', 0, 1146, 300, 1146, 300),
	array('Kipchak', 'Coast', 'No', 0, 1111, 583, 1111, 583),
	array('Kyzyl Kum', 'Land', 'No', 0, 1150, 752, 1150, 752),
	array('Khorasan', 'Land', 'No', 0, 1147, 875, 1147, 875),
	array('Sijistan', 'Land', 'No', 0, 1099, 900, 1099, 900),
	array('Basra', 'Land', 'No', 1, 1081, 998, 1081, 998),
	array('Nefud', 'Land', 'No', 0, 890, 958, 890, 958),
	array('Mecca', 'Land', 'No', 0, 794, 977, 794, 977),
	array('Al-Qattai', 'Land', 'No', 4, 701, 1005, 701, 1005),
	array('Fazzan', 'Land', 'No', 0, 430, 993, 424, 993),
	array('Tahert', 'Land', 'No', 0, 286, 886, 286, 886),
	array('Sijilmassa', 'Land', 'No', 0, 202, 939, 202, 939),
	array('Barghawata', 'Coast', 'No', 0, 107, 931, 107, 931),
	array('Sea of Tangiers', 'Sea', 'No', 0, 54, 824, 54, 824),
	array('Mauretania', 'Coast', 'Yes', 10, 172, 882, 172, 882),
	array('Kutamia', 'Coast', 'No', 0, 286, 805, 286, 805),
	array('Ifriqiya', 'Coast', 'Yes', 10, 370, 850, 370, 850),
	array('Tripolitania', 'Coast', 'No', 0, 446, 920, 446, 920),
	array('Barca', 'Coast', 'Yes', 4, 555, 950, 555, 950),
	array('Alexandria', 'Coast', 'Yes', 4, 616, 973, 616, 973),
	array('Baghdad', 'Land', 'Yes', 1, 913, 903, 913, 903),
	array('Constantinople', 'Coast', 'Yes', 2, 664, 740, 664, 740),
	array('Sarkel', 'Land', 'Yes', 7, 907, 575, 907, 575),
	array('Novgorod', 'Coast', 'Yes', 8, 704, 299, 704, 299),
	array('Bremen', 'Coast', 'Yes', 6, 346, 453, 346, 453),
	array('Paris', 'Coast', 'Yes', 5, 225, 523, 225, 523),
	array('Cadiz', 'Coast', 'Yes', 9, 95, 750, 95, 750),
	array('Kattegat', 'Sea', 'No', 0, 408, 360, 408, 360),
	array('Abodrite Sea', 'Sea', 'No', 0, 461, 385, 461, 385),
	array('Lettish Sea', 'Sea', 'No', 0, 523, 365, 523, 365),
	array('Finnish Sea', 'Sea', 'No', 0, 586, 282, 586, 282),
	array('Straits of Jebel Tarik', 'Sea', 'No', 0, 305, 762, 305, 762),
	array('Balearic Sea', 'Sea', 'No', 0, 255, 733, 255, 733),
	array('Ligurian Sea', 'Sea', 'No', 0, 337, 669, 337, 669),
	array('Tyrrhenian Sea', 'Sea', 'No', 0, 416, 728, 416, 728),
	array('Illyrian Sea', 'Sea', 'No', 0, 488, 675, 488, 675),
	array('Ionian Sea', 'Sea', 'No', 0, 524, 775, 524, 775),
	array('Libyan Sea', 'Sea', 'No', 0, 505, 895, 505, 895),
	array('Aegean Sea', 'Sea', 'No', 0, 625, 788, 625, 788),
	array('Egyptian Sea', 'Sea', 'No', 0, 677, 909, 677, 909),
	array('Cilician Sea', 'Sea', 'No', 0, 660, 828, 660, 828),
	array('Sea of Tyre', 'Sea', 'No', 0, 748, 898, 748, 898),
	array('West Euxine Sea', 'Sea', 'No', 0, 720, 632, 720, 632),
	array('East Euxine Sea', 'Sea', 'No', 0, 830, 682, 830, 682),
	array('North Khazar Sea', 'Sea', 'No', 0, 1019, 680, 1019, 680),
	array('South Khazar Sea', 'Sea', 'No', 0, 1035, 725, 1035, 725),
	array('Karelia', 'Coast', 'No', 0, 690, 230, 690, 230),
	array('Esteland', 'Coast', 'Yes', 10, 650, 310, 650, 310),
	array('Livonia', 'Coast', 'No', 0, 573, 339, 573, 339),
	array('Borussia', 'Coast', 'Yes', 10, 511, 401, 511, 401),
	array('Pomerania', 'Coast', 'No', 0, 461, 414, 461, 414),
	array('Veletia', 'Coast', 'No', 0, 393, 412, 393, 412),
	array('Veletia (North Coast)', 'Coast', 'No', 0, 407, 408, 407, 408),
	array('Veletia (West Coast)', 'Coast', 'No', 0, 366, 407, 366, 407),
	array('Jelling', 'Coast', 'Yes', 3, 376, 341, 376, 341),
	array('Rostov', 'Land', 'Yes', 8, 828, 339, 828, 339),
	array('Cheremissia', 'Land', 'No', 0, 896, 294, 896, 294),
	array('Bulgar', 'Land', 'Yes', 10, 963, 350, 963, 350),
	array('Udmurtia', 'Land', 'No', 0, 1085, 365, 1085, 365),
	array('Bashkortostan', 'Land', 'Yes', 10, 1090, 445, 1090, 445),
	array('Atil', 'Coast', 'Yes', 7, 1017, 526, 1017, 526),
	array('Urgench', 'Coast', 'Yes', 10, 1102, 724, 1102, 724),
	array('Alidistan', 'Coast', 'No', 0, 1083, 795, 1083, 795),
	array('Isfahan', 'Land', 'Yes', 1, 1039, 858, 1039, 858),
	array('Irak', 'Land', 'Yes', 1, 978, 905, 978, 905),
	array('Ardebil', 'Coast', 'Yes', 1, 992, 842, 992, 842),
	array('Tamantarka', 'Coast', 'Yes', 7, 838, 595, 838, 595),
	array('Mordvinia', 'Land', 'No', 0, 928, 450, 928, 450),
	array('Balanjar', 'Coast', 'Yes', 7, 945, 646, 945, 646),
	array('Derbent', 'Coast', 'No', 7, 995, 700, 995, 700),
	array('Azerbaijan', 'Coast', 'Yes', 10, 967, 763, 967, 763),
	array('Abkhazia', 'Coast', 'No', 7, 886, 670, 886, 670),
	array('Georgia', 'Coast', 'Yes', 10, 890, 722, 890, 722),
	array('Kakheti', 'Land', 'No', 0, 925, 725, 925, 725),
	array('Bucellaria', 'Coast', 'No', 2, 760, 694, 760, 694),
	array('Mosul', 'Land', 'No', 1, 947, 815, 947, 815),
	array('Armenia', 'Land', 'Yes', 10, 868, 786, 868, 786),
	array('Cappadocia', 'Coast', 'No', 0, 805, 808, 805, 808),
	array('Attalia', 'Coast', 'Yes', 2, 740, 790, 740, 790),
	array('Jazira', 'Land', 'No', 0, 856, 848, 856, 848),
	array('Damascus', 'Coast', 'Yes', 4, 812, 897, 812, 897),
	array('Jerusalem', 'Coast', 'Yes', 4, 776, 899, 776, 899),
	array('Cyprus', 'Coast', 'Yes', 10, 757, 836, 757, 836),
	array('Crete', 'Coast', 'Yes', 10, 605, 840, 605, 840),
	array('Thrace', 'Coast', 'Yes', 10, 631, 662, 631, 662),
	array('Cherson', 'Coast', 'Yes', 2, 748, 611, 748, 611),
	array('Pechenega', 'Coast', 'Yes', 10, 778, 532, 778, 532),
	array('Pechenega (East Coast)', 'Coast', 'No', 0, 793, 570, 793, 570),
	array('Pechenega (West Coast)', 'Coast', 'No', 0, 735, 581, 735, 581),
	array('Krivichia', 'Land', 'No', 8, 760, 360, 760, 360),
	array('Vyatichia', 'Land', 'No', 0, 815, 438, 815, 438),
	array('Smolensk', 'Land', 'Yes', 8, 709, 398, 709, 398),
	array('Severyana', 'Land', 'No', 0, 880, 470, 880, 470),
	array('Kiev', 'Land', 'Yes', 8, 711, 466, 711, 466),
	array('Vlacha', 'Coast', 'No', 0, 683, 587, 683, 587),
	array('Dregovochia', 'Land', 'No', 0, 647, 435, 647, 435),
	array('Volhynia', 'Land', 'No', 0, 610, 512, 610, 512),
	array('Macedonia', 'Land', 'No', 0, 578, 652, 578, 652),
	array('Epirus', 'Coast', 'No', 2, 533, 713, 533, 713),
	array('Dalmatia', 'Coast', 'Yes', 10, 521, 658, 521, 658),
	array('Onoguria', 'Land', 'No', 0, 573, 554, 573, 554),
	array('Slavonia', 'Land', 'No', 0, 490, 580, 490, 580),
	array('Sardinia', 'Coast', 'Yes', 10, 370, 720, 370, 720),
	array('Corsica', 'Coast', 'Yes', 10, 364, 668, 364, 668),
	array('Sicily', 'Coast', 'Yes', 10, 460, 796, 460, 796),
	array('Taranto', 'Coast', 'Yes', 2, 486, 749, 486, 749),
	array('Rome', 'Coast', 'Yes', 10, 427, 692, 427, 692),
	array('Rome (East Coast)', 'Coast', 'No', 0, 417, 630, 417, 630),
	array('Rome (West Coast)', 'Coast', 'No', 0, 422, 694, 422, 694),
	array('Spoleto', 'Coast', 'No', 0, 452, 677, 452, 677),
	array('Salerno', 'Coast', 'No', 0, 462, 719, 462, 719),
	array('Aqileia', 'Coast', 'No', 0, 441, 614, 441, 614),
	array('Moravia', 'Land', 'Yes', 10, 470, 545, 470, 545),
	array('Mazovia', 'Land', 'Yes', 10, 583, 468, 583, 468),
	array('Polania', 'Land', 'No', 0, 525, 490, 525, 490),
	array('Saxony', 'Land', 'Yes', 6, 412, 474, 412, 474),
	array('Bavaria', 'Land', 'Yes', 6, 411, 565, 411, 565),
	array('Swabia', 'Land', 'Yes', 6, 342, 520, 342, 520),
	array('Helvetia', 'Land', 'No', 6, 360, 563, 360, 563),
	array('Lombardy', 'Coast', 'No', 0, 384, 638, 384, 638),
	array('Friesland', 'Coast', 'No', 0, 309, 432, 309, 432),
	array('Franconia', 'Land', 'No', 6, 367, 470, 367, 470),
	array('Lothairingia', 'Coast', 'Yes', 10, 275, 472, 275, 472),
	array('Upper Burgundy', 'Land', 'No', 0, 308, 591, 308, 591),
	array('Lower Burgundy', 'Coast', 'Yes', 10, 309, 651, 309, 651),
	array('Narbonne', 'Coast', 'Yes', 5, 282, 631, 282, 631),
	array('Autun', 'Land', 'No', 5, 279, 576, 279, 576),
	array('Brittany', 'Coast', 'Yes', 10, 154, 538, 154, 538),
	array('Aquitaine', 'Coast', 'Yes', 5, 250, 587, 250, 587),
	array('Toulouse', 'Land', 'No', 5, 232, 652, 232, 652),
	array('Gascony', 'Coast', 'Yes', 5, 218, 610, 218, 610),
	array('Spanish March', 'Coast', 'No', 0, 253, 695, 253, 695),
	array('Asturias', 'Coast', 'No', 0, 146, 650, 146, 650),
	array('Galicia', 'Coast', 'No', 0, 97, 657, 97, 657),
	array('Salamanca', 'Land', 'Yes', 9, 127, 741, 127, 741),
	array('Zaragoza', 'Land', 'No', 0, 184, 705, 184, 705),
	array('Toledo', 'Land', 'No', 9, 168, 725, 168, 725),
	array('Valencia', 'Coast', 'Yes', 9, 220, 719, 220, 719),
	array('Cordova', 'Land', 'Yes', 9, 167, 772, 167, 772),
	array('Granada', 'Coast', 'No', 9, 201, 785, 201, 785),
	array('Pamplona', 'Coast', 'Yes', 10, 182, 656, 182, 656),
	array('Cantabric Sea', 'Sea', 'No', 0, 156, 586, 156, 586)
);

foreach($territoryRawData as $territoryRawRow)
{
	list($name, $type, $supply, $countryID, $x, $y, $sx, $sy)=$territoryRawRow;
	new InstallTerritory($name, $type, $supply, $countryID, $x, $y, $sx, $sy);
}
unset($territoryRawData);

$bordersRawData=array(
	array('Munster','Dublin','Yes','Yes'),
	array('Munster','Icelandic Sea','Yes','No'),
	array('Munster','Ocean Sea','Yes','No'),
	array('Dublin','Icelandic Sea','Yes','No'),
	array('Dublin','Welsh Sea','Yes','No'),
	array('Dublin','Ocean Sea','Yes','No'),
	array('Icelandic Sea','Caledonia','Yes','No'),
	array('Icelandic Sea','Jorvik (East Coast)','Yes','No'),
	array('Icelandic Sea','Welsh Sea','Yes','No'),
	array('Icelandic Sea','North German Sea','Yes','No'),
	array('Icelandic Sea','Ocean Sea','Yes','No'),
	array('Icelandic Sea','Norway','Yes','No'),
	array('Icelandic Sea','Saamiland (North Coast)','Yes','No'),
	array('Icelandic Sea','Bjarmaland','Yes','No'),
	array('Icelandic Sea','White Sea','Yes','No'),
	array('Caledonia','Jorvik','No','Yes'),
	array('Caledonia','Jorvik (East Coast)','Yes','No'),
	array('Caledonia','Jorvik (West Coast)','Yes','No'),
	array('Caledonia','Welsh Sea','Yes','No'),
	array('Jorvik','Wales','No','Yes'),
	array('Jorvik','Wessex','No','Yes'),
	array('Jorvik (East Coast)','Wessex','Yes','No'),
	array('Jorvik (East Coast)','North German Sea','Yes','No'),
	array('Jorvik (West Coast)','Wales','Yes','No'),
	array('Jorvik (West Coast)','Welsh Sea','Yes','No'),
	array('Wales','Welsh Sea','Yes','No'),
	array('Wales','Wessex','Yes','Yes'),
	array('Welsh Sea','Wessex','Yes','No'),
	array('Welsh Sea','Ocean Sea','Yes','No'),
	array('Wessex','North German Sea','Yes','No'),
	array('Wessex','South German Sea','Yes','No'),
	array('Wessex','Brittish Channel','Yes','No'),
	array('Wessex','Ocean Sea','Yes','No'),
	array('North German Sea','South German Sea','Yes','No'),
	array('North German Sea','Norway','Yes','No'),
	array('North German Sea','Kattegat','Yes','No'),
	array('North German Sea','Jelling','Yes','No'),
	array('South German Sea','Brittish Channel','Yes','No'),
	array('South German Sea','Bremen','Yes','No'),
	array('South German Sea','Veletia (West Coast)','Yes','No'),
	array('South German Sea','Jelling','Yes','No'),
	array('South German Sea','Friesland','Yes','No'),
	array('South German Sea','Lothairingia','Yes','No'),
	array('Brittish Channel','Ocean Sea','Yes','No'),
	array('Brittish Channel','Paris','Yes','No'),
	array('Brittish Channel','Lothairingia','Yes','No'),
	array('Brittish Channel','Brittany','Yes','No'),
	array('Ocean Sea','Sea of Tangiers','Yes','No'),
	array('Ocean Sea','Cadiz','Yes','No'),
	array('Ocean Sea','Brittany','Yes','No'),
	array('Ocean Sea','Galicia','Yes','No'),
	array('Ocean Sea','Cantabric Sea','Yes','No'),
	array('Norway','Uppland','No','Yes'),
	array('Norway','Viken','Yes','Yes'),
	array('Norway','Saamiland','No','Yes'),
	array('Norway','Saamiland (North Coast)','Yes','No'),
	array('Norway','Kattegat','Yes','No'),
	array('Uppland','Viken','No','Yes'),
	array('Uppland','Gottland','Yes','Yes'),
	array('Uppland','Saamiland','No','Yes'),
	array('Uppland','Saamiland (South Coast)','Yes','No'),
	array('Uppland','Lettish Sea','Yes','No'),
	array('Uppland','Finnish Sea','Yes','No'),
	array('Viken','Gottland','No','Yes'),
	array('Viken','Scania','Yes','Yes'),
	array('Viken','Kattegat','Yes','No'),
	array('Gottland','Scania','Yes','Yes'),
	array('Gottland','Lettish Sea','Yes','No'),
	array('Scania','Kattegat','Yes','No'),
	array('Scania','Abodrite Sea','Yes','No'),
	array('Scania','Lettish Sea','Yes','No'),
	array('Saamiland','Bjarmaland','No','Yes'),
	array('Saamiland','Karelia','No','Yes'),
	array('Saamiland (North Coast)','Bjarmaland','Yes','No'),
	array('Saamiland (South Coast)','Finnish Sea','Yes','No'),
	array('Saamiland (South Coast)','Karelia','Yes','No'),
	array('Bjarmaland','Komia','Yes','Yes'),
	array('Bjarmaland','White Sea','Yes','No'),
	array('Bjarmaland','Karelia','Yes','Yes'),
	array('Bjarmaland','Cheremissia','No','Yes'),
	array('Komia','White Sea','Yes','No'),
	array('Komia','Stone Belt','No','Yes'),
	array('Komia','Cheremissia','No','Yes'),
	array('Komia','Bulgar','No','Yes'),
	array('Komia','Udmurtia','No','Yes'),
	array('Stone Belt','Kipchak','No','Yes'),
	array('Stone Belt','Udmurtia','No','Yes'),
	array('Stone Belt','Bashkortostan','No','Yes'),
	array('Kipchak','Kyzyl Kum','No','Yes'),
	array('Kipchak','North Khazar Sea','Yes','No'),
	array('Kipchak','Bashkortostan','No','Yes'),
	array('Kipchak','Atil','Yes','Yes'),
	array('Kipchak','Urgench','Yes','Yes'),
	array('Kyzyl Kum','Khorasan','No','Yes'),
	array('Kyzyl Kum','Urgench','No','Yes'),
	array('Khorasan','Sijistan','No','Yes'),
	array('Khorasan','Urgench','No','Yes'),
	array('Khorasan','Alidistan','No','Yes'),
	array('Sijistan','Basra','No','Yes'),
	array('Sijistan','Alidistan','No','Yes'),
	array('Sijistan','Isfahan','No','Yes'),
	array('Basra','Nefud','No','Yes'),
	array('Basra','Isfahan','No','Yes'),
	array('Basra','Irak','No','Yes'),
	array('Nefud','Mecca','No','Yes'),
	array('Nefud','Baghdad','No','Yes'),
	array('Nefud','Irak','No','Yes'),
	array('Nefud','Jazira','No','Yes'),
	array('Mecca','Al-Qattai','No','Yes'),
	array('Mecca','Alexandria','No','Yes'),
	array('Mecca','Jazira','No','Yes'),
	array('Mecca','Jerusalem','No','Yes'),
	array('Al-Qattai','Fazzan','No','Yes'),
	array('Al-Qattai','Alexandria','No','Yes'),
	array('Fazzan','Tahert','No','Yes'),
	array('Fazzan','Ifriqiya','No','Yes'),
	array('Fazzan','Tripolitania','No','Yes'),
	array('Fazzan','Alexandria','No','Yes'),
	array('Tahert','Sijilmassa','No','Yes'),
	array('Tahert','Mauretania','No','Yes'),
	array('Tahert','Kutamia','No','Yes'),
	array('Tahert','Ifriqiya','No','Yes'),
	array('Sijilmassa','Barghawata','No','Yes'),
	array('Sijilmassa','Mauretania','No','Yes'),
	array('Barghawata','Sea of Tangiers','Yes','No'),
	array('Barghawata','Mauretania','Yes','Yes'),
	array('Sea of Tangiers','Mauretania','Yes','No'),
	array('Sea of Tangiers','Cadiz','Yes','No'),
	array('Sea of Tangiers','Straits of Jebel Tarik','Yes','No'),
	array('Mauretania','Kutamia','Yes','Yes'),
	array('Mauretania','Straits of Jebel Tarik','Yes','No'),
	array('Kutamia','Ifriqiya','Yes','Yes'),
	array('Kutamia','Straits of Jebel Tarik','Yes','No'),
	array('Ifriqiya','Tripolitania','Yes','Yes'),
	array('Ifriqiya','Straits of Jebel Tarik','Yes','No'),
	array('Ifriqiya','Tyrrhenian Sea','Yes','No'),
	array('Ifriqiya','Libyan Sea','Yes','No'),
	array('Tripolitania','Barca','Yes','Yes'),
	array('Tripolitania','Alexandria','No','Yes'),
	array('Tripolitania','Libyan Sea','Yes','No'),
	array('Barca','Alexandria','Yes','Yes'),
	array('Barca','Libyan Sea','Yes','No'),
	array('Barca','Egyptian Sea','Yes','No'),
	array('Alexandria','Egyptian Sea','Yes','No'),
	array('Alexandria','Jerusalem','Yes','Yes'),
	array('Baghdad','Irak','No','Yes'),
	array('Baghdad','Ardebil','No','Yes'),
	array('Baghdad','Mosul','No','Yes'),
	array('Baghdad','Jazira','No','Yes'),
	array('Constantinople','Ionian Sea','Yes','No'),
	array('Constantinople','Aegean Sea','Yes','No'),
	array('Constantinople','West Euxine Sea','Yes','No'),
	array('Constantinople','Bucellaria','Yes','Yes'),
	array('Constantinople','Attalia','Yes','Yes'),
	array('Constantinople','Thrace','Yes','Yes'),
	array('Constantinople','Macedonia','No','Yes'),
	array('Constantinople','Epirus','Yes','Yes'),
	array('Sarkel','Atil','No','Yes'),
	array('Sarkel','Tamantarka','No','Yes'),
	array('Sarkel','Mordvinia','No','Yes'),
	array('Sarkel','Balanjar','No','Yes'),
	array('Sarkel','Severyana','No','Yes'),
	array('Novgorod','Finnish Sea','Yes','No'),
	array('Novgorod','Karelia','Yes','Yes'),
	array('Novgorod','Esteland','Yes','Yes'),
	array('Novgorod','Livonia','No','Yes'),
	array('Novgorod','Rostov','No','Yes'),
	array('Novgorod','Krivichia','No','Yes'),
	array('Novgorod','Smolensk','No','Yes'),
	array('Bremen','Veletia','No','Yes'),
	array('Bremen','Veletia (West Coast)','Yes','No'),
	array('Bremen','Saxony','No','Yes'),
	array('Bremen','Friesland','Yes','Yes'),
	array('Bremen','Franconia','No','Yes'),
	array('Paris','Lothairingia','Yes','Yes'),
	array('Paris','Autun','No','Yes'),
	array('Paris','Brittany','Yes','Yes'),
	array('Paris','Aquitaine','No','Yes'),
	array('Cadiz','Straits of Jebel Tarik','Yes','No'),
	array('Cadiz','Galicia','Yes','Yes'),
	array('Cadiz','Salamanca','No','Yes'),
	array('Cadiz','Cordova','No','Yes'),
	array('Cadiz','Granada','Yes','Yes'),
	array('Kattegat','Abodrite Sea','Yes','No'),
	array('Kattegat','Jelling','Yes','No'),
	array('Abodrite Sea','Lettish Sea','Yes','No'),
	array('Abodrite Sea','Borussia','Yes','No'),
	array('Abodrite Sea','Pomerania','Yes','No'),
	array('Abodrite Sea','Veletia (North Coast)','Yes','No'),
	array('Abodrite Sea','Jelling','Yes','No'),
	array('Lettish Sea','Finnish Sea','Yes','No'),
	array('Lettish Sea','Livonia','Yes','No'),
	array('Lettish Sea','Borussia','Yes','No'),
	array('Finnish Sea','Karelia','Yes','No'),
	array('Finnish Sea','Esteland','Yes','No'),
	array('Finnish Sea','Livonia','Yes','No'),
	array('Straits of Jebel Tarik','Balearic Sea','Yes','No'),
	array('Straits of Jebel Tarik','Tyrrhenian Sea','Yes','No'),
	array('Straits of Jebel Tarik','Sardinia','Yes','No'),
	array('Straits of Jebel Tarik','Valencia','Yes','No'),
	array('Straits of Jebel Tarik','Granada','Yes','No'),
	array('Balearic Sea','Ligurian Sea','Yes','No'),
	array('Balearic Sea','Tyrrhenian Sea','Yes','No'),
	array('Balearic Sea','Sardinia','Yes','No'),
	array('Balearic Sea','Corsica','Yes','No'),
	array('Balearic Sea','Spanish March','Yes','No'),
	array('Balearic Sea','Valencia','Yes','No'),
	array('Ligurian Sea','Tyrrhenian Sea','Yes','No'),
	array('Ligurian Sea','Corsica','Yes','No'),
	array('Ligurian Sea','Rome (West Coast)','Yes','No'),
	array('Ligurian Sea','Lombardy','Yes','No'),
	array('Ligurian Sea','Lower Burgundy','Yes','No'),
	array('Ligurian Sea','Narbonne','Yes','No'),
	array('Ligurian Sea','Spanish March','Yes','No'),
	array('Tyrrhenian Sea','Ionian Sea','Yes','No'),
	array('Tyrrhenian Sea','Libyan Sea','Yes','No'),
	array('Tyrrhenian Sea','Sardinia','Yes','No'),
	array('Tyrrhenian Sea','Corsica','Yes','No'),
	array('Tyrrhenian Sea','Sicily','Yes','No'),
	array('Tyrrhenian Sea','Taranto','Yes','No'),
	array('Tyrrhenian Sea','Rome (West Coast)','Yes','No'),
	array('Tyrrhenian Sea','Salerno','Yes','No'),
	array('Illyrian Sea','Ionian Sea','Yes','No'),
	array('Illyrian Sea','Epirus','Yes','No'),
	array('Illyrian Sea','Dalmatia','Yes','No'),
	array('Illyrian Sea','Taranto','Yes','No'),
	array('Illyrian Sea','Rome (East Coast)','Yes','No'),
	array('Illyrian Sea','Spoleto','Yes','No'),
	array('Illyrian Sea','Aqileia','Yes','No'),
	array('Ionian Sea','Libyan Sea','Yes','No'),
	array('Ionian Sea','Aegean Sea','Yes','No'),
	array('Ionian Sea','Egyptian Sea','Yes','No'),
	array('Ionian Sea','Crete','Yes','No'),
	array('Ionian Sea','Epirus','Yes','No'),
	array('Ionian Sea','Sicily','Yes','No'),
	array('Ionian Sea','Taranto','Yes','No'),
	array('Libyan Sea','Egyptian Sea','Yes','No'),
	array('Libyan Sea','Sicily','Yes','No'),
	array('Aegean Sea','Cilician Sea','Yes','No'),
	array('Aegean Sea','Attalia','Yes','No'),
	array('Aegean Sea','Crete','Yes','No'),
	array('Egyptian Sea','Cilician Sea','Yes','No'),
	array('Egyptian Sea','Sea of Tyre','Yes','No'),
	array('Egyptian Sea','Jerusalem','Yes','No'),
	array('Egyptian Sea','Cyprus','Yes','No'),
	array('Egyptian Sea','Crete','Yes','No'),
	array('Cilician Sea','Sea of Tyre','Yes','No'),
	array('Cilician Sea','Cappadocia','Yes','No'),
	array('Cilician Sea','Attalia','Yes','No'),
	array('Cilician Sea','Cyprus','Yes','No'),
	array('Cilician Sea','Crete','Yes','No'),
	array('Sea of Tyre','Cappadocia','Yes','No'),
	array('Sea of Tyre','Damascus','Yes','No'),
	array('Sea of Tyre','Jerusalem','Yes','No'),
	array('Sea of Tyre','Cyprus','Yes','No'),
	array('West Euxine Sea','East Euxine Sea','Yes','No'),
	array('West Euxine Sea','Bucellaria','Yes','No'),
	array('West Euxine Sea','Thrace','Yes','No'),
	array('West Euxine Sea','Cherson','Yes','No'),
	array('West Euxine Sea','Pechenega (West Coast)','Yes','No'),
	array('West Euxine Sea','Vlacha','Yes','No'),
	array('East Euxine Sea','Tamantarka','Yes','No'),
	array('East Euxine Sea','Abkhazia','Yes','No'),
	array('East Euxine Sea','Georgia','Yes','No'),
	array('East Euxine Sea','Bucellaria','Yes','No'),
	array('East Euxine Sea','Cherson','Yes','No'),
	array('East Euxine Sea','Pechenega (East Coast)','Yes','No'),
	array('North Khazar Sea','South Khazar Sea','Yes','No'),
	array('North Khazar Sea','Atil','Yes','No'),
	array('North Khazar Sea','Urgench','Yes','No'),
	array('North Khazar Sea','Balanjar','Yes','No'),
	array('North Khazar Sea','Derbent','Yes','No'),
	array('South Khazar Sea','Urgench','Yes','No'),
	array('South Khazar Sea','Alidistan','Yes','No'),
	array('South Khazar Sea','Ardebil','Yes','No'),
	array('South Khazar Sea','Derbent','Yes','No'),
	array('South Khazar Sea','Azerbaijan','Yes','No'),
	array('Karelia','Rostov','No','Yes'),
	array('Karelia','Cheremissia','No','Yes'),
	array('Esteland','Livonia','Yes','Yes'),
	array('Livonia','Borussia','Yes','Yes'),
	array('Livonia','Smolensk','No','Yes'),
	array('Livonia','Dregovochia','No','Yes'),
	array('Borussia','Pomerania','Yes','Yes'),
	array('Borussia','Dregovochia','No','Yes'),
	array('Borussia','Mazovia','No','Yes'),
	array('Borussia','Polania','No','Yes'),
	array('Pomerania','Veletia','No','Yes'),
	array('Pomerania','Veletia (North Coast)','Yes','No'),
	array('Pomerania','Polania','No','Yes'),
	array('Pomerania','Saxony','No','Yes'),
	array('Veletia','Jelling','No','Yes'),
	array('Veletia','Saxony','No','Yes'),
	array('Veletia (North Coast)','Jelling','Yes','No'),
	array('Veletia (West Coast)','Jelling','Yes','No'),
	array('Rostov','Cheremissia','No','Yes'),
	array('Rostov','Krivichia','No','Yes'),
	array('Cheremissia','Bulgar','No','Yes'),
	array('Cheremissia','Krivichia','No','Yes'),
	array('Cheremissia','Vyatichia','No','Yes'),
	array('Bulgar','Udmurtia','No','Yes'),
	array('Bulgar','Mordvinia','No','Yes'),
	array('Bulgar','Vyatichia','No','Yes'),
	array('Udmurtia','Bashkortostan','No','Yes'),
	array('Udmurtia','Atil','No','Yes'),
	array('Udmurtia','Mordvinia','No','Yes'),
	array('Bashkortostan','Atil','No','Yes'),
	array('Atil','Mordvinia','No','Yes'),
	array('Atil','Balanjar','Yes','Yes'),
	array('Urgench','Alidistan','Yes','Yes'),
	array('Alidistan','Isfahan','No','Yes'),
	array('Alidistan','Ardebil','Yes','Yes'),
	array('Isfahan','Irak','No','Yes'),
	array('Isfahan','Ardebil','No','Yes'),
	array('Irak','Ardebil','No','Yes'),
	array('Ardebil','Azerbaijan','Yes','Yes'),
	array('Ardebil','Mosul','No','Yes'),
	array('Tamantarka','Balanjar','No','Yes'),
	array('Tamantarka','Abkhazia','Yes','Yes'),
	array('Tamantarka','Pechenega','No','Yes'),
	array('Tamantarka','Pechenega (East Coast)','Yes','No'),
	array('Tamantarka','Severyana','No','Yes'),
	array('Mordvinia','Vyatichia','No','Yes'),
	array('Mordvinia','Severyana','No','Yes'),
	array('Balanjar','Derbent','Yes','Yes'),
	array('Balanjar','Abkhazia','No','Yes'),
	array('Balanjar','Georgia','No','Yes'),
	array('Balanjar','Kakheti','No','Yes'),
	array('Derbent','Azerbaijan','Yes','Yes'),
	array('Derbent','Kakheti','No','Yes'),
	array('Azerbaijan','Kakheti','No','Yes'),
	array('Azerbaijan','Mosul','No','Yes'),
	array('Abkhazia','Georgia','Yes','Yes'),
	array('Georgia','Kakheti','No','Yes'),
	array('Georgia','Bucellaria','Yes','Yes'),
	array('Georgia','Cappadocia','No','Yes'),
	array('Kakheti','Mosul','No','Yes'),
	array('Kakheti','Armenia','No','Yes'),
	array('Kakheti','Cappadocia','No','Yes'),
	array('Bucellaria','Cappadocia','No','Yes'),
	array('Bucellaria','Attalia','No','Yes'),
	array('Mosul','Armenia','No','Yes'),
	array('Mosul','Jazira','No','Yes'),
	array('Armenia','Cappadocia','No','Yes'),
	array('Armenia','Jazira','No','Yes'),
	array('Cappadocia','Attalia','Yes','Yes'),
	array('Cappadocia','Jazira','No','Yes'),
	array('Cappadocia','Damascus','Yes','Yes'),
	array('Jazira','Damascus','No','Yes'),
	array('Jazira','Jerusalem','No','Yes'),
	array('Damascus','Jerusalem','Yes','Yes'),
	array('Thrace','Vlacha','Yes','Yes'),
	array('Thrace','Macedonia','No','Yes'),
	array('Thrace','Onoguria','No','Yes'),
	array('Cherson','Pechenega','No','Yes'),
	array('Cherson','Pechenega (East Coast)','Yes','No'),
	array('Cherson','Pechenega (West Coast)','Yes','No'),
	array('Pechenega','Severyana','No','Yes'),
	array('Pechenega','Kiev','No','Yes'),
	array('Pechenega','Vlacha','No','Yes'),
	array('Pechenega (West Coast)','Vlacha','Yes','No'),
	array('Krivichia','Vyatichia','No','Yes'),
	array('Krivichia','Smolensk','No','Yes'),
	array('Vyatichia','Smolensk','No','Yes'),
	array('Vyatichia','Severyana','No','Yes'),
	array('Vyatichia','Kiev','No','Yes'),
	array('Smolensk','Kiev','No','Yes'),
	array('Smolensk','Dregovochia','No','Yes'),
	array('Severyana','Kiev','No','Yes'),
	array('Kiev','Vlacha','No','Yes'),
	array('Kiev','Dregovochia','No','Yes'),
	array('Kiev','Volhynia','No','Yes'),
	array('Vlacha','Volhynia','No','Yes'),
	array('Vlacha','Onoguria','No','Yes'),
	array('Dregovochia','Volhynia','No','Yes'),
	array('Dregovochia','Mazovia','No','Yes'),
	array('Volhynia','Onoguria','No','Yes'),
	array('Volhynia','Moravia','No','Yes'),
	array('Volhynia','Mazovia','No','Yes'),
	array('Volhynia','Polania','No','Yes'),
	array('Macedonia','Epirus','No','Yes'),
	array('Macedonia','Dalmatia','No','Yes'),
	array('Macedonia','Onoguria','No','Yes'),
	array('Epirus','Dalmatia','Yes','Yes'),
	array('Dalmatia','Onoguria','No','Yes'),
	array('Dalmatia','Slavonia','No','Yes'),
	array('Dalmatia','Aqileia','Yes','Yes'),
	array('Onoguria','Slavonia','No','Yes'),
	array('Onoguria','Moravia','No','Yes'),
	array('Slavonia','Aqileia','No','Yes'),
	array('Slavonia','Moravia','No','Yes'),
	array('Slavonia','Bavaria','No','Yes'),
	array('Taranto','Spoleto','Yes','Yes'),
	array('Taranto','Salerno','Yes','Yes'),
	array('Rome','Spoleto','No','Yes'),
	array('Rome','Salerno','No','Yes'),
	array('Rome','Aqileia','No','Yes'),
	array('Rome','Lombardy','No','Yes'),
	array('Rome (East Coast)','Spoleto','Yes','No'),
	array('Rome (East Coast)','Aqileia','Yes','No'),
	array('Rome (West Coast)','Salerno','Yes','No'),
	array('Rome (West Coast)','Lombardy','Yes','No'),
	array('Spoleto','Salerno','No','Yes'),
	array('Aqileia','Bavaria','No','Yes'),
	array('Aqileia','Lombardy','No','Yes'),
	array('Moravia','Polania','No','Yes'),
	array('Moravia','Bavaria','No','Yes'),
	array('Mazovia','Polania','No','Yes'),
	array('Polania','Saxony','No','Yes'),
	array('Polania','Bavaria','No','Yes'),
	array('Saxony','Bavaria','No','Yes'),
	array('Saxony','Swabia','No','Yes'),
	array('Saxony','Franconia','No','Yes'),
	array('Bavaria','Swabia','No','Yes'),
	array('Bavaria','Helvetia','No','Yes'),
	array('Bavaria','Lombardy','No','Yes'),
	array('Swabia','Helvetia','No','Yes'),
	array('Swabia','Franconia','No','Yes'),
	array('Swabia','Lothairingia','No','Yes'),
	array('Swabia','Upper Burgundy','No','Yes'),
	array('Helvetia','Lombardy','No','Yes'),
	array('Helvetia','Upper Burgundy','No','Yes'),
	array('Helvetia','Lower Burgundy','No','Yes'),
	array('Lombardy','Lower Burgundy','Yes','Yes'),
	array('Friesland','Franconia','No','Yes'),
	array('Friesland','Lothairingia','Yes','Yes'),
	array('Franconia','Lothairingia','No','Yes'),
	array('Lothairingia','Upper Burgundy','No','Yes'),
	array('Lothairingia','Autun','No','Yes'),
	array('Upper Burgundy','Lower Burgundy','No','Yes'),
	array('Upper Burgundy','Autun','No','Yes'),
	array('Lower Burgundy','Narbonne','Yes','Yes'),
	array('Lower Burgundy','Autun','No','Yes'),
	array('Narbonne','Autun','No','Yes'),
	array('Narbonne','Toulouse','No','Yes'),
	array('Narbonne','Spanish March','Yes','Yes'),
	array('Autun','Aquitaine','No','Yes'),
	array('Autun','Toulouse','No','Yes'),
	array('Brittany','Aquitaine','Yes','Yes'),
	array('Brittany','Cantabric Sea','Yes','No'),
	array('Aquitaine','Toulouse','No','Yes'),
	array('Aquitaine','Gascony','Yes','Yes'),
	array('Aquitaine','Cantabric Sea','Yes','No'),
	array('Toulouse','Gascony','No','Yes'),
	array('Toulouse','Spanish March','No','Yes'),
	array('Toulouse','Pamplona','No','Yes'),
	array('Gascony','Pamplona','Yes','Yes'),
	array('Gascony','Cantabric Sea','Yes','No'),
	array('Spanish March','Zaragoza','No','Yes'),
	array('Spanish March','Valencia','Yes','Yes'),
	array('Spanish March','Pamplona','No','Yes'),
	array('Asturias','Galicia','Yes','Yes'),
	array('Asturias','Salamanca','No','Yes'),
	array('Asturias','Zaragoza','No','Yes'),
	array('Asturias','Pamplona','Yes','Yes'),
	array('Asturias','Cantabric Sea','Yes','No'),
	array('Galicia','Salamanca','No','Yes'),
	array('Galicia','Cantabric Sea','Yes','No'),
	array('Salamanca','Zaragoza','No','Yes'),
	array('Salamanca','Toledo','No','Yes'),
	array('Salamanca','Cordova','No','Yes'),
	array('Zaragoza','Toledo','No','Yes'),
	array('Zaragoza','Valencia','No','Yes'),
	array('Zaragoza','Pamplona','No','Yes'),
	array('Toledo','Valencia','No','Yes'),
	array('Toledo','Cordova','No','Yes'),
	array('Valencia','Cordova','No','Yes'),
	array('Valencia','Granada','Yes','Yes'),
	array('Cordova','Granada','No','Yes'),
	array('Pamplona','Cantabric Sea','Yes','No')
);

foreach($bordersRawData as $borderRawRow)
{
	list($from, $to, $fleets, $armies)=$borderRawRow;
	InstallTerritory::$Territories[$to]  ->addBorder(InstallTerritory::$Territories[$from],$fleets,$armies);
}
unset($bordersRawData);

// Custom footer not changed by edit tool

// Just create the database as usual:
InstallTerritory::runSQL($this->mapID);
InstallCache::terrJSON($this->territoriesJSONFile(),$this->mapID);

// Copy the smallmap-sample to the cache-directory to avoid a screwed variantpage (there is no smallmap available)
//copy ('variants/'.$this->name.'/resources/sampleMap.png','variants/'.$this->name.'/cache/sampleMap.png');











































































































































































