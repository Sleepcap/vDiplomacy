<?php
// This is file installs the map data for the HeptarchyIV variant
defined('IN_CODE') or die('This script can not be run by itself.');
require_once("variants/install.php");

InstallTerritory::$Territories=array();
$countries=$this->countries;
$territoryRawData=array(
	array('anglia', 'Coast', 'No', 1, 1358, 17, 678, 8),
	array('cornubia', 'Coast', 'No', 2, 1359, 40, 678, 20),
	array('ireland', 'Coast', 'No', 3, 1359, 65, 679, 33),
	array('mercia', 'Coast', 'No', 4, 1358, 88, 680, 45),
	array('northumbria', 'Coast', 'No', 5, 1357, 112, 679, 57),
	array('scotland', 'Coast', 'No', 6, 1358, 138, 680, 68),
	array('wales', 'Coast', 'No', 7, 1359, 161, 679, 80),
	array('The Hebrides', 'Coast', 'Yes', 0, 449, 107, 229, 47),
	array('The Highlands', 'Coast', 'No', 6, 688, 101, 329, 59),
	array('Aberdeen', 'Coast', 'Yes', 6, 849, 145, 421, 73),
	array('Tayside', 'Coast', 'No', 6, 788, 258, 402, 124),
	array('Edinburgh', 'Coast', 'Yes', 6, 830, 314, 421, 153),
	array('Argyll', 'Coast', 'No', 6, 607, 240, 300, 123),
	array('Glasgow', 'Coast', 'Yes', 6, 638, 332, 319, 164),
	array('Southern Uplands', 'Land', 'No', 6, 825, 361, 414, 185),
	array('Donegal', 'Coast', 'No', 3, 334, 398, 169, 200),
	array('Ulster', 'Coast', 'No', 3, 281, 598, 137, 267),
	array('Cork', 'Coast', 'Yes', 3, 283, 805, 139, 405),
	array('Leinster', 'Coast', 'No', 3, 467, 735, 238, 373),
	array('Dublin', 'Coast', 'Yes', 3, 467, 583, 230, 293),
	array('Belfast', 'Coast', 'Yes', 3, 489, 435, 245, 209),
	array('Northumberland', 'Coast', 'No', 5, 900, 351, 452, 173),
	array('Durham', 'Coast', 'Yes', 5, 929, 447, 465, 225),
	array('Wolds', 'Coast', 'No', 5, 992, 508, 498, 271),
	array('Southern Penines', 'Land', 'No', 5, 952, 574, 474, 294),
	array('York', 'Land', 'Yes', 5, 904, 495, 449, 269),
	array('Northern Penines', 'Land', 'No', 5, 851, 408, 432, 209),
	array('Lancaster', 'Coast', 'Yes', 5, 848, 514, 406, 265),
	array('Llandudno', 'Coast', 'Yes', 7, 734, 629, 364, 317),
	array('Gwynedd', 'Coast', 'No', 7, 694, 657, 340, 324),
	array('Powys', 'Land', 'No', 7, 733, 739, 370, 374),
	array('Dyfed', 'Coast', 'No', 7, 669, 818, 305, 431),
	array('Swansea', 'Coast', 'Yes', 7, 675, 893, 337, 445),
	array('Cardiff', 'Coast', 'Yes', 7, 707, 902, 353, 450),
	array('Gwent', 'Coast', 'No', 7, 765, 866, 377, 450),
	array('Gloucester', 'Coast', 'Yes', 4, 819, 902, 410, 445),
	array('Herefordshire', 'Land', 'No', 4, 790, 832, 402, 400),
	array('Staffordshire', 'Land', 'No', 4, 860, 752, 426, 379),
	array('Birmingham', 'Land', 'Yes', 4, 876, 800, 436, 399),
	array('Peak District', 'Land', 'No', 4, 898, 694, 447, 345),
	array('Warwickshire', 'Land', 'No', 4, 930, 833, 465, 409),
	array('Nottingham', 'Land', 'Yes', 4, 961, 696, 481, 343),
	array('King\\\'s Lynn', 'Coast', 'Yes', 4, 1040, 719, 510, 347),
	array('Oxford', 'Land', 'Yes', 1, 970, 920, 495, 451),
	array('London', 'Coast', 'Yes', 1, 1063, 930, 532, 461),
	array('Essex', 'Coast', 'No', 1, 1118, 899, 559, 449),
	array('The Downs', 'Land', 'No', 1, 982, 966, 486, 488),
	array('Sussex', 'Coast', 'No', 1, 1098, 1021, 538, 512),
	array('Dover', 'Coast', 'Yes', 1, 1184, 977, 577, 505),
	array('Cornwall', 'Coast', 'No', 2, 551, 1090, 300, 528),
	array('Plymouth', 'Coast', 'Yes', 2, 695, 1098, 348, 546),
	array('Exmoor', 'Coast', 'No', 2, 666, 983, 329, 491),
	array('Exeter', 'Coast', 'Yes', 2, 783, 1034, 395, 516),
	array('Somerset', 'Land', 'No', 2, 835, 985, 415, 492),
	array('Bristol', 'Coast', 'Yes', 2, 789, 958, 380, 481),
	array('Scillonia', 'Coast', 'Yes', 0, 382, 1085, 192, 537),
	array('Isle of Man', 'Coast', 'Yes', 0, 654, 532, 324, 267),
	array('Calais', 'Coast', 'No', 0, 1288, 1099, 649, 545),
	array('Flanders', 'Coast', 'Yes', 0, 1447, 1001, 721, 498),
	array('Stranraer', 'Coast', 'Yes', 0, 654, 422, 317, 206),
	array('Dumfries', 'Coast', 'Yes', 0, 768, 427, 382, 212),
	array('Cumbria', 'Coast', 'No', 0, 776, 501, 390, 249),
	array('Liverpool', 'Coast', 'Yes', 0, 833, 608, 417, 303),
	array('Manchester', 'Land', 'Yes', 0, 896, 612, 445, 309),
	array('Sheffield', 'Land', 'Yes', 0, 944, 640, 470, 323),
	array('Lincolnshire', 'Coast', 'No', 0, 1014, 609, 505, 319),
	array('Chester', 'Coast', 'Yes', 0, 802, 629, 402, 315),
	array('Shrewsbury', 'Land', 'Yes', 0, 782, 726, 396, 362),
	array('Cotswald', 'Coast', 'No', 0, 796, 924, 396, 460),
	array('Northamptonshire', 'Land', 'No', 0, 996, 771, 490, 406),
	array('Cambridge', 'Land', 'Yes', 0, 1061, 810, 530, 405),
	array('Norwich', 'Coast', 'Yes', 0, 1150, 696, 564, 343),
	array('Ipswich', 'Coast', 'Yes', 0, 1158, 821, 578, 414),
	array('Southampton', 'Coast', 'Yes', 0, 875, 1061, 432, 528),
	array('Portsmouth', 'Coast', 'Yes', 0, 977, 1039, 497, 513),
	array('North Atlantic Ocean', 'Sea', 'No', 0, 79, 400, 49, 189),
	array('Mid-Atlantic Ocean', 'Sea', 'No', 0, 80, 1167, 67, 569),
	array('Celtic Sea', 'Sea', 'No', 0, 263, 967, 152, 482),
	array('Hibernian Sea', 'Sea', 'No', 0, 456, 217, 224, 104),
	array('North Channel', 'Sea', 'No', 0, 565, 412, 281, 206),
	array('Irish Sea', 'Sea', 'No', 0, 577, 554, 287, 272),
	array('Cardigan Bay', 'Sea', 'No', 0, 570, 686, 286, 345),
	array('Bristol Channel', 'Sea', 'No', 0, 512, 976, 253, 487),
	array('Cornish Sea', 'Sea', 'No', 0, 586, 1142, 304, 566),
	array('Norwegian Sea', 'Sea', 'No', 0, 912, 33, 469, 14),
	array('Solway Firth', 'Sea', 'No', 0, 731, 474, 366, 234),
	array('Morecambe Bay', 'Sea', 'No', 0, 731, 531, 364, 266),
	array('Colwyn Bay', 'Sea', 'No', 0, 666, 584, 380, 298),
	array('Lyme Bay', 'Sea', 'No', 0, 824, 1103, 386, 539),
	array('English Channel', 'Sea', 'No', 0, 1034, 1158, 497, 557),
	array('Firths of Forth and Tay', 'Sea', 'No', 0, 899, 262, 450, 134),
	array('North Sea North', 'Sea', 'No', 0, 1180, 355, 561, 192),
	array('The Wash', 'Sea', 'No', 0, 1121, 629, 550, 313),
	array('North Sea South', 'Sea', 'No', 0, 1351, 790, 677, 387),
	array('Thames Estuary', 'Sea', 'No', 0, 1225, 933, 591, 452),
	array('Straits of Dover', 'Sea', 'No', 0, 1200, 1066, 596, 538),
	array('Dogger Bank', 'Sea', 'No', 0, 1410, 375, 707, 185),
	array('Severn Estuary', 'Sea', 'No', 0, 756, 929, 357, 477)
);

foreach($territoryRawData as $territoryRawRow)
{
	list($name, $type, $supply, $countryID, $x, $y, $sx, $sy)=$territoryRawRow;
	new InstallTerritory($name, $type, $supply, $countryID, $x, $y, $sx, $sy);
}
unset($territoryRawData);

$bordersRawData=array(
	array('The Hebrides','North Atlantic Ocean','Yes','No'),
	array('The Hebrides','Hibernian Sea','Yes','No'),
	array('The Hebrides','Norwegian Sea','Yes','No'),
	array('The Highlands','Aberdeen','Yes','Yes'),
	array('The Highlands','Argyll','Yes','Yes'),
	array('The Highlands','Hibernian Sea','Yes','No'),
	array('The Highlands','Norwegian Sea','Yes','No'),
	array('Aberdeen','Tayside','Yes','Yes'),
	array('Aberdeen','Norwegian Sea','Yes','No'),
	array('Aberdeen','Firths of Forth and Tay','Yes','No'),
	array('Tayside','Edinburgh','Yes','Yes'),
	array('Tayside','Glasgow','No','Yes'),
	array('Tayside','Firths of Forth and Tay','Yes','No'),
	array('Edinburgh','Glasgow','No','Yes'),
	array('Edinburgh','Southern Uplands','No','Yes'),
	array('Edinburgh','Northumberland','Yes','Yes'),
	array('Edinburgh','Firths of Forth and Tay','Yes','No'),
	array('Argyll','Glasgow','Yes','Yes'),
	array('Argyll','Hibernian Sea','Yes','No'),
	array('Argyll','North Channel','Yes','No'),
	array('Glasgow','Southern Uplands','No','Yes'),
	array('Glasgow','Stranraer','Yes','Yes'),
	array('Glasgow','North Channel','Yes','No'),
	array('Southern Uplands','Northumberland','No','Yes'),
	array('Southern Uplands','Northern Penines','No','Yes'),
	array('Southern Uplands','Stranraer','No','Yes'),
	array('Southern Uplands','Dumfries','No','Yes'),
	array('Donegal','Ulster','Yes','Yes'),
	array('Donegal','Belfast','Yes','Yes'),
	array('Donegal','North Atlantic Ocean','Yes','No'),
	array('Donegal','Hibernian Sea','Yes','No'),
	array('Ulster','Cork','Yes','Yes'),
	array('Ulster','Leinster','No','Yes'),
	array('Ulster','Dublin','No','Yes'),
	array('Ulster','Belfast','No','Yes'),
	array('Ulster','North Atlantic Ocean','Yes','No'),
	array('Cork','Leinster','Yes','Yes'),
	array('Cork','North Atlantic Ocean','Yes','No'),
	array('Cork','Celtic Sea','Yes','No'),
	array('Leinster','Dublin','Yes','Yes'),
	array('Leinster','Celtic Sea','Yes','No'),
	array('Leinster','Cardigan Bay','Yes','No'),
	array('Dublin','Belfast','Yes','Yes'),
	array('Dublin','Irish Sea','Yes','No'),
	array('Dublin','Cardigan Bay','Yes','No'),
	array('Belfast','Hibernian Sea','Yes','No'),
	array('Belfast','North Channel','Yes','No'),
	array('Belfast','Irish Sea','Yes','No'),
	array('Northumberland','Durham','Yes','Yes'),
	array('Northumberland','Northern Penines','No','Yes'),
	array('Northumberland','Firths of Forth and Tay','Yes','No'),
	array('Northumberland','North Sea North','Yes','No'),
	array('Durham','Wolds','Yes','Yes'),
	array('Durham','York','No','Yes'),
	array('Durham','Northern Penines','No','Yes'),
	array('Durham','North Sea North','Yes','No'),
	array('Wolds','Southern Penines','No','Yes'),
	array('Wolds','York','No','Yes'),
	array('Wolds','Lincolnshire','Yes','Yes'),
	array('Wolds','North Sea North','Yes','No'),
	array('Wolds','The Wash','Yes','No'),
	array('Southern Penines','York','No','Yes'),
	array('Southern Penines','Lancaster','No','Yes'),
	array('Southern Penines','Liverpool','No','Yes'),
	array('Southern Penines','Manchester','No','Yes'),
	array('Southern Penines','Sheffield','No','Yes'),
	array('Southern Penines','Lincolnshire','No','Yes'),
	array('York','Northern Penines','No','Yes'),
	array('York','Lancaster','No','Yes'),
	array('Northern Penines','Lancaster','No','Yes'),
	array('Northern Penines','Dumfries','No','Yes'),
	array('Northern Penines','Cumbria','No','Yes'),
	array('Lancaster','Cumbria','Yes','Yes'),
	array('Lancaster','Liverpool','Yes','Yes'),
	array('Lancaster','Morecambe Bay','Yes','No'),
	array('Llandudno','Gwynedd','Yes','Yes'),
	array('Llandudno','Powys','No','Yes'),
	array('Llandudno','Chester','Yes','Yes'),
	array('Llandudno','Colwyn Bay','Yes','No'),
	array('Gwynedd','Powys','No','Yes'),
	array('Gwynedd','Dyfed','Yes','Yes'),
	array('Gwynedd','Cardigan Bay','Yes','No'),
	array('Gwynedd','Colwyn Bay','Yes','No'),
	array('Powys','Dyfed','No','Yes'),
	array('Powys','Cardiff','No','Yes'),
	array('Powys','Gwent','No','Yes'),
	array('Powys','Herefordshire','No','Yes'),
	array('Powys','Chester','No','Yes'),
	array('Powys','Shrewsbury','No','Yes'),
	array('Dyfed','Swansea','Yes','Yes'),
	array('Dyfed','Cardigan Bay','Yes','No'),
	array('Dyfed','Bristol Channel','Yes','No'),
	array('Swansea','Cardiff','Yes','Yes'),
	array('Swansea','Bristol Channel','Yes','No'),
	array('Cardiff','Gwent','Yes','Yes'),
	array('Cardiff','Bristol Channel','Yes','No'),
	array('Cardiff','Severn Estuary','Yes','No'),
	array('Gwent','Gloucester','Yes','Yes'),
	array('Gwent','Herefordshire','No','Yes'),
	array('Gwent','Severn Estuary','Yes','No'),
	array('Gloucester','Herefordshire','No','Yes'),
	array('Gloucester','Birmingham','No','Yes'),
	array('Gloucester','Warwickshire','No','Yes'),
	array('Gloucester','Cotswald','Yes','Yes'),
	array('Gloucester','Severn Estuary','Yes','No'),
	array('Herefordshire','Staffordshire','No','Yes'),
	array('Herefordshire','Birmingham','No','Yes'),
	array('Herefordshire','Shrewsbury','No','Yes'),
	array('Staffordshire','Birmingham','No','Yes'),
	array('Staffordshire','Peak District','No','Yes'),
	array('Staffordshire','Nottingham','No','Yes'),
	array('Staffordshire','Chester','No','Yes'),
	array('Staffordshire','Shrewsbury','No','Yes'),
	array('Birmingham','Warwickshire','No','Yes'),
	array('Birmingham','Nottingham','No','Yes'),
	array('Peak District','Nottingham','No','Yes'),
	array('Peak District','Manchester','No','Yes'),
	array('Peak District','Sheffield','No','Yes'),
	array('Peak District','Chester','No','Yes'),
	array('Warwickshire','Nottingham','No','Yes'),
	array('Warwickshire','Oxford','No','Yes'),
	array('Warwickshire','Cotswald','No','Yes'),
	array('Warwickshire','Northamptonshire','No','Yes'),
	array('Nottingham','King\\\'s Lynn','No','Yes'),
	array('Nottingham','Sheffield','No','Yes'),
	array('Nottingham','Lincolnshire','No','Yes'),
	array('Nottingham','Northamptonshire','No','Yes'),
	array('King\\\'s Lynn','Lincolnshire','Yes','Yes'),
	array('King\\\'s Lynn','Northamptonshire','No','Yes'),
	array('King\\\'s Lynn','Cambridge','No','Yes'),
	array('King\\\'s Lynn','Norwich','Yes','Yes'),
	array('King\\\'s Lynn','The Wash','Yes','No'),
	array('Oxford','London','No','Yes'),
	array('Oxford','The Downs','No','Yes'),
	array('Oxford','Cotswald','No','Yes'),
	array('Oxford','Northamptonshire','No','Yes'),
	array('Oxford','Cambridge','No','Yes'),
	array('London','Essex','Yes','Yes'),
	array('London','The Downs','No','Yes'),
	array('London','Sussex','No','Yes'),
	array('London','Dover','Yes','Yes'),
	array('London','Cambridge','No','Yes'),
	array('London','Thames Estuary','Yes','No'),
	array('Essex','Cambridge','No','Yes'),
	array('Essex','Ipswich','Yes','Yes'),
	array('Essex','Thames Estuary','Yes','No'),
	array('The Downs','Sussex','No','Yes'),
	array('The Downs','Somerset','No','Yes'),
	array('The Downs','Cotswald','No','Yes'),
	array('The Downs','Southampton','No','Yes'),
	array('The Downs','Portsmouth','No','Yes'),
	array('Sussex','Dover','Yes','Yes'),
	array('Sussex','Portsmouth','Yes','Yes'),
	array('Sussex','English Channel','Yes','No'),
	array('Sussex','Straits of Dover','Yes','No'),
	array('Dover','Thames Estuary','Yes','No'),
	array('Dover','Straits of Dover','Yes','No'),
	array('Cornwall','Plymouth','Yes','Yes'),
	array('Cornwall','Exmoor','Yes','Yes'),
	array('Cornwall','Bristol Channel','Yes','No'),
	array('Cornwall','Cornish Sea','Yes','No'),
	array('Plymouth','Exmoor','No','Yes'),
	array('Plymouth','Exeter','Yes','Yes'),
	array('Plymouth','Cornish Sea','Yes','No'),
	array('Plymouth','Lyme Bay','Yes','No'),
	array('Exmoor','Exeter','No','Yes'),
	array('Exmoor','Somerset','No','Yes'),
	array('Exmoor','Bristol','Yes','Yes'),
	array('Exmoor','Bristol Channel','Yes','No'),
	array('Exmoor','Severn Estuary','Yes','No'),
	array('Exeter','Somerset','No','Yes'),
	array('Exeter','Southampton','Yes','Yes'),
	array('Exeter','Lyme Bay','Yes','No'),
	array('Somerset','Bristol','No','Yes'),
	array('Somerset','Cotswald','No','Yes'),
	array('Somerset','Southampton','No','Yes'),
	array('Bristol','Cotswald','Yes','Yes'),
	array('Bristol','Severn Estuary','Yes','No'),
	array('Scillonia','Celtic Sea','Yes','No'),
	array('Scillonia','Bristol Channel','Yes','No'),
	array('Scillonia','Cornish Sea','Yes','No'),
	array('Isle of Man','North Channel','Yes','No'),
	array('Isle of Man','Irish Sea','Yes','No'),
	array('Isle of Man','Solway Firth','Yes','No'),
	array('Isle of Man','Morecambe Bay','Yes','No'),
	array('Calais','Flanders','Yes','Yes'),
	array('Calais','English Channel','Yes','No'),
	array('Calais','North Sea South','Yes','No'),
	array('Calais','Straits of Dover','Yes','No'),
	array('Flanders','North Sea South','Yes','No'),
	array('Flanders','Dogger Bank','Yes','No'),
	array('Stranraer','Dumfries','Yes','Yes'),
	array('Stranraer','North Channel','Yes','No'),
	array('Stranraer','Solway Firth','Yes','No'),
	array('Dumfries','Cumbria','Yes','Yes'),
	array('Dumfries','Solway Firth','Yes','No'),
	array('Cumbria','Solway Firth','Yes','No'),
	array('Cumbria','Morecambe Bay','Yes','No'),
	array('Liverpool','Manchester','No','Yes'),
	array('Liverpool','Chester','Yes','Yes'),
	array('Liverpool','Morecambe Bay','Yes','No'),
	array('Liverpool','Colwyn Bay','Yes','No'),
	array('Manchester','Sheffield','No','Yes'),
	array('Manchester','Chester','No','Yes'),
	array('Sheffield','Lincolnshire','No','Yes'),
	array('Lincolnshire','The Wash','Yes','No'),
	array('Chester','Shrewsbury','No','Yes'),
	array('Chester','Colwyn Bay','Yes','No'),
	array('Cotswald','Severn Estuary','Yes','No'),
	array('Northamptonshire','Cambridge','No','Yes'),
	array('Cambridge','Norwich','No','Yes'),
	array('Cambridge','Ipswich','No','Yes'),
	array('Norwich','Ipswich','Yes','Yes'),
	array('Norwich','The Wash','Yes','No'),
	array('Norwich','North Sea South','Yes','No'),
	array('Ipswich','North Sea South','Yes','No'),
	array('Ipswich','Thames Estuary','Yes','No'),
	array('Southampton','Portsmouth','Yes','Yes'),
	array('Southampton','Lyme Bay','Yes','No'),
	array('Southampton','English Channel','Yes','No'),
	array('Portsmouth','English Channel','Yes','No'),
	array('North Atlantic Ocean','Mid-Atlantic Ocean','Yes','No'),
	array('North Atlantic Ocean','Celtic Sea','Yes','No'),
	array('North Atlantic Ocean','Hibernian Sea','Yes','No'),
	array('North Atlantic Ocean','Norwegian Sea','Yes','No'),
	array('Mid-Atlantic Ocean','Celtic Sea','Yes','No'),
	array('Mid-Atlantic Ocean','Cornish Sea','Yes','No'),
	array('Mid-Atlantic Ocean','English Channel','Yes','No'),
	array('Celtic Sea','Cardigan Bay','Yes','No'),
	array('Celtic Sea','Bristol Channel','Yes','No'),
	array('Celtic Sea','Cornish Sea','Yes','No'),
	array('Hibernian Sea','North Channel','Yes','No'),
	array('Hibernian Sea','Norwegian Sea','Yes','No'),
	array('North Channel','Irish Sea','Yes','No'),
	array('North Channel','Solway Firth','Yes','No'),
	array('Irish Sea','Cardigan Bay','Yes','No'),
	array('Irish Sea','Morecambe Bay','Yes','No'),
	array('Irish Sea','Colwyn Bay','Yes','No'),
	array('Cardigan Bay','Bristol Channel','Yes','No'),
	array('Cardigan Bay','Colwyn Bay','Yes','No'),
	array('Bristol Channel','Cornish Sea','Yes','No'),
	array('Bristol Channel','Severn Estuary','Yes','No'),
	array('Cornish Sea','Lyme Bay','Yes','No'),
	array('Cornish Sea','English Channel','Yes','No'),
	array('Norwegian Sea','Firths of Forth and Tay','Yes','No'),
	array('Norwegian Sea','North Sea North','Yes','No'),
	array('Norwegian Sea','Dogger Bank','Yes','No'),
	array('Solway Firth','Morecambe Bay','Yes','No'),
	array('Morecambe Bay','Colwyn Bay','Yes','No'),
	array('Lyme Bay','English Channel','Yes','No'),
	array('English Channel','Straits of Dover','Yes','No'),
	array('Firths of Forth and Tay','North Sea North','Yes','No'),
	array('North Sea North','The Wash','Yes','No'),
	array('North Sea North','North Sea South','Yes','No'),
	array('North Sea North','Dogger Bank','Yes','No'),
	array('The Wash','North Sea South','Yes','No'),
	array('North Sea South','Thames Estuary','Yes','No'),
	array('North Sea South','Straits of Dover','Yes','No'),
	array('North Sea South','Dogger Bank','Yes','No'),
	array('Thames Estuary','Straits of Dover','Yes','No')
);

foreach($bordersRawData as $borderRawRow)
{
	list($from, $to, $fleets, $armies)=$borderRawRow;
	InstallTerritory::$Territories[$to]  ->addBorder(InstallTerritory::$Territories[$from],$fleets,$armies);
}
unset($bordersRawData);

InstallTerritory::runSQL($this->mapID);
InstallCache::terrJSON($this->territoriesJSONFile(),$this->mapID);
?>
