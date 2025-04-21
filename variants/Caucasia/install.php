<?php
// This is file installs the map data for the Caucasia variant
defined('IN_CODE') or die('This script can not be run by itself.');
require_once("variants/install.php");

InstallTerritory::$Territories=array();
$countries=$this->countries;
$territoryRawData=array(
	array('Sevastopol', 'Coast', 'Yes', 0, 12, 49, 6, 27),
	array('Krasnodar', 'Coast', 'Yes', 4, 188, 33, 82, 25),
	array('Stavropol', 'Coast', 'Yes', 4, 428, 76, 224, 55),
	array('Pjatigorsk', 'Coast', 'Yes', 4, 1045, 36, 431, 17),
	array('Abchazia', 'Coast', 'Yes', 0, 410, 178, 162, 117),
	array('Kutaisi', 'Coast', 'Yes', 3, 585, 360, 222, 257),
	array('Tskhinvali', 'Land', 'Yes', 3, 776, 264, 307, 188),
	array('Lagodekhi', 'Land', 'Yes', 3, 1085, 345, 394, 212),
	array('Tbilisi', 'Land', 'Yes', 3, 948, 471, 356, 310),
	array('Kabardino Balkaria', 'Land', 'No', 0, 874, 156, 331, 101),
	array('Ossetia', 'Land', 'No', 0, 1084, 137, 403, 99),
	array('Ingussia', 'Land', 'Yes', 0, 1198, 201, 455, 96),
	array('Gudermes', 'Land', 'No', 2, 1336, 83, 500, 55),
	array('Grozny', 'Land', 'Yes', 2, 1322, 197, 495, 129),
	array('Tebulosmta', 'Land', 'Yes', 2, 1260, 307, 485, 219),
	array('Vedeno', 'Land', 'Yes', 2, 1436, 407, 536, 255),
	array('Dagestan', 'Coast', 'Yes', 0, 1544, 323, 574, 193),
	array('Shaki', 'Land', 'No', 5, 1348, 491, 541, 368),
	array('Baku', 'Coast', 'Yes', 5, 1688, 595, 648, 420),
	array('Gyandzja', 'Land', 'Yes', 5, 1354, 653, 551, 457),
	array('Ali Bayramli', 'Coast', 'Yes', 5, 1496, 839, 609, 555),
	array('Kurkosa', 'Coast', 'No', 0, 1652, 1009, 614, 668),
	array('Iran', 'Land', 'Yes', 0, 1410, 1075, 520, 724),
	array('Little Caucasia', 'Land', 'No', 5, 1232, 803, 460, 514),
	array('Nagorno Karabach', 'Land', 'Yes', 0, 1361, 865, 508, 574),
	array('Ghapan', 'Land', 'No', 1, 1210, 949, 451, 627),
	array('Nakhichevan', 'Land', 'No', 0, 1126, 1015, 422, 671),
	array('Martuni', 'Land', 'Yes', 1, 1032, 885, 376, 568),
	array('Kirovakan', 'Land', 'Yes', 1, 1060, 749, 405, 520),
	array('Jerevan', 'Land', 'Yes', 1, 846, 765, 308, 499),
	array('Kumairi', 'Land', 'No', 1, 836, 631, 318, 412),
	array('Turkey', 'Coast', 'Yes', 0, 530, 719, 193, 479),
	array('Adzarskia', 'Coast', 'No', 0, 456, 421, 171, 277),
	array('North Black Sea', 'Sea', 'No', 0, 228, 189, 79, 126),
	array('South Black Sea', 'Sea', 'No', 0, 140, 371, 44, 248),
	array('North Caspian Sea', 'Sea', 'No', 0, 1830, 265, 665, 151),
	array('South Caspian Sea', 'Sea', 'No', 0, 1900, 1011, 712, 639)
);

foreach($territoryRawData as $territoryRawRow)
{
	list($name, $type, $supply, $countryID, $x, $y, $sx, $sy)=$territoryRawRow;
	new InstallTerritory($name, $type, $supply, $countryID, $x, $y, $sx, $sy);
}
unset($territoryRawData);

$bordersRawData=array(
	array('Sevastopol','North Black Sea','Yes','No'),
	array('Sevastopol','South Black Sea','Yes','No'),
	array('Krasnodar','Stavropol','Yes','Yes'),
	array('Krasnodar','North Black Sea','Yes','No'),
	array('Stavropol','Pjatigorsk','No','Yes'),
	array('Stavropol','Abchazia','Yes','Yes'),
	array('Stavropol','Tskhinvali','No','Yes'),
	array('Stavropol','Kabardino Balkaria','No','Yes'),
	array('Stavropol','North Black Sea','Yes','No'),
	array('Pjatigorsk','Kabardino Balkaria','No','Yes'),
	array('Pjatigorsk','Ossetia','No','Yes'),
	array('Pjatigorsk','Ingussia','No','Yes'),
	array('Pjatigorsk','Gudermes','No','Yes'),
	array('Pjatigorsk','Dagestan','Yes','Yes'),
	array('Pjatigorsk','North Caspian Sea','Yes','No'),
	array('Abchazia','Kutaisi','Yes','Yes'),
	array('Abchazia','Tskhinvali','No','Yes'),
	array('Abchazia','North Black Sea','Yes','No'),
	array('Kutaisi','Tskhinvali','No','Yes'),
	array('Kutaisi','Tbilisi','No','Yes'),
	array('Kutaisi','Kumairi','No','Yes'),
	array('Kutaisi','Turkey','No','Yes'),
	array('Kutaisi','Adzarskia','Yes','Yes'),
	array('Kutaisi','North Black Sea','Yes','No'),
	array('Kutaisi','South Black Sea','Yes','No'),
	array('Tskhinvali','Lagodekhi','No','Yes'),
	array('Tskhinvali','Tbilisi','No','Yes'),
	array('Tskhinvali','Kabardino Balkaria','No','Yes'),
	array('Tskhinvali','Ossetia','No','Yes'),
	array('Lagodekhi','Tbilisi','No','Yes'),
	array('Lagodekhi','Ossetia','No','Yes'),
	array('Lagodekhi','Ingussia','No','Yes'),
	array('Lagodekhi','Tebulosmta','No','Yes'),
	array('Lagodekhi','Shaki','No','Yes'),
	array('Lagodekhi','Gyandzja','No','Yes'),
	array('Tbilisi','Gyandzja','No','Yes'),
	array('Tbilisi','Little Caucasia','No','Yes'),
	array('Tbilisi','Kirovakan','No','Yes'),
	array('Tbilisi','Kumairi','No','Yes'),
	array('Kabardino Balkaria','Ossetia','No','Yes'),
	array('Ossetia','Ingussia','No','Yes'),
	array('Ingussia','Gudermes','No','Yes'),
	array('Ingussia','Grozny','No','Yes'),
	array('Ingussia','Tebulosmta','No','Yes'),
	array('Gudermes','Grozny','No','Yes'),
	array('Gudermes','Dagestan','No','Yes'),
	array('Grozny','Tebulosmta','No','Yes'),
	array('Grozny','Vedeno','No','Yes'),
	array('Grozny','Dagestan','No','Yes'),
	array('Tebulosmta','Vedeno','No','Yes'),
	array('Tebulosmta','Shaki','No','Yes'),
	array('Vedeno','Dagestan','No','Yes'),
	array('Vedeno','Shaki','No','Yes'),
	array('Dagestan','Shaki','No','Yes'),
	array('Dagestan','Baku','Yes','Yes'),
	array('Dagestan','North Caspian Sea','Yes','No'),
	array('Shaki','Baku','No','Yes'),
	array('Shaki','Gyandzja','No','Yes'),
	array('Baku','Gyandzja','No','Yes'),
	array('Baku','Ali Bayramli','Yes','Yes'),
	array('Baku','North Caspian Sea','Yes','No'),
	array('Baku','South Caspian Sea','Yes','No'),
	array('Gyandzja','Ali Bayramli','No','Yes'),
	array('Gyandzja','Little Caucasia','No','Yes'),
	array('Ali Bayramli','Kurkosa','Yes','Yes'),
	array('Ali Bayramli','Iran','No','Yes'),
	array('Ali Bayramli','Little Caucasia','No','Yes'),
	array('Ali Bayramli','Nagorno Karabach','No','Yes'),
	array('Ali Bayramli','South Caspian Sea','Yes','No'),
	array('Kurkosa','Iran','No','Yes'),
	array('Kurkosa','South Caspian Sea','Yes','No'),
	array('Iran','Little Caucasia','No','Yes'),
	array('Iran','Ghapan','No','Yes'),
	array('Iran','Nakhichevan','No','Yes'),
	array('Iran','Martuni','No','Yes'),
	array('Iran','Turkey','No','Yes'),
	array('Little Caucasia','Nagorno Karabach','No','Yes'),
	array('Little Caucasia','Ghapan','No','Yes'),
	array('Little Caucasia','Kirovakan','No','Yes'),
	array('Ghapan','Nakhichevan','No','Yes'),
	array('Ghapan','Martuni','No','Yes'),
	array('Ghapan','Kirovakan','No','Yes'),
	array('Nakhichevan','Martuni','No','Yes'),
	array('Martuni','Kirovakan','No','Yes'),
	array('Martuni','Jerevan','No','Yes'),
	array('Martuni','Turkey','No','Yes'),
	array('Kirovakan','Jerevan','No','Yes'),
	array('Kirovakan','Kumairi','No','Yes'),
	array('Jerevan','Kumairi','No','Yes'),
	array('Jerevan','Turkey','No','Yes'),
	array('Kumairi','Turkey','No','Yes'),
	array('Turkey','Adzarskia','Yes','Yes'),
	array('Turkey','South Black Sea','Yes','No'),
	array('Adzarskia','South Black Sea','Yes','No'),
	array('North Black Sea','South Black Sea','Yes','No'),
	array('North Caspian Sea','South Caspian Sea','Yes','No')
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
