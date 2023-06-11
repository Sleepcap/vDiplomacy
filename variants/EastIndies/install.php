<?php
// This is file installs the map data for the EastIndies variant
defined('IN_CODE') or die('This script can not be run by itself.');
require_once("variants/install.php");

InstallTerritory::$Territories=array();
$countries=$this->countries;
$territoryRawData=array(
	array('Arabia', 'Coast', 'No', 0, 47, 741, 47, 741),
	array('Rub Al Khali', 'Land', 'No', 0, 122, 829, 122, 829),
	array('Oman', 'Coast', 'Yes', 0, 228, 793, 228, 793),
	array('Hadramaut', 'Coast', 'No', 0, 133, 920, 133, 920),
	array('Yemen', 'Coast', 'Yes', 0, 40, 978, 40, 978),
	array('Seylac', 'Coast', 'Yes', 0, 35, 1177, 35, 1177),
	array('Qom', 'Coast', 'No', 8, 26, 272, 26, 272),
	array('Elburz', 'Land', 'No', 8, 74, 150, 74, 150),
	array('Dasht-I-Kavir', 'Land', 'No', 8, 120, 243, 120, 243),
	array('Isfahan', 'Coast', 'Yes', 8, 80, 464, 80, 464),
	array('Hormuz', 'Coast', 'Yes', 8, 269, 614, 269, 614),
	array('Shiraz', 'Land', 'No', 8, 295, 487, 295, 487),
	array('Yezd', 'Land', 'No', 8, 234, 341, 234, 341),
	array('Meshed', 'Land', 'Yes', 8, 205, 178, 205, 178),
	array('Kara Kum', 'Land', 'No', 0, 188, 31, 188, 31),
	array('Bukhara', 'Land', 'No', 0, 306, 68, 306, 68),
	array('Samarkand', 'Land', 'Yes', 0, 443, 36, 443, 36),
	array('Balkh', 'Land', 'Yes', 7, 417, 157, 417, 157),
	array('Herat', 'Land', 'Yes', 0, 337, 272, 337, 272),
	array('Kandahar', 'Land', 'No', 7, 366, 368, 366, 368),
	array('Peshawar', 'Coast', 'No', 0, 465, 393, 465, 393),
	array('Sind', 'Coast', 'Yes', 0, 390, 625, 390, 625),
	array('Gujarat', 'Coast', 'Yes', 0, 470, 766, 470, 766),
	array('Jaisalmer', 'Coast', 'Yes', 9, 495, 575, 495, 575),
	array('Bikaner', 'Coast', 'No', 9, 536, 461, 536, 461),
	array('Multan', 'Coast', 'Yes', 9, 585, 371, 585, 371),
	array('Lahore', 'Coast', 'No', 0, 629, 294, 629, 294),
	array('Kabul', 'Coast', 'Yes', 7, 549, 224, 549, 224),
	array('Badakhshan', 'Land', 'Yes', 7, 575, 114, 575, 114),
	array('Ferghana', 'Land', 'No', 7, 673, 61, 673, 61),
	array('Taklamakan', 'Land', 'No', 0, 1004, 71, 1004, 71),
	array('Kashmir', 'Land', 'Yes', 0, 699, 220, 699, 220),
	array('Tibet', 'Land', 'No', 0, 1029, 329, 1029, 329),
	array('Sutiya', 'Coast', 'No', 0, 1020, 506, 1020, 506),
	array('Nepal', 'Land', 'No', 0, 917, 458, 917, 458),
	array('Muzaffarpur', 'Coast', 'Yes', 3, 892, 574, 892, 574),
	array('Awadh', 'Coast', 'Yes', 3, 764, 433, 764, 433),
	array('Agra', 'Coast', 'Yes', 3, 692, 409, 692, 409),
	array('Jaipur', 'Land', 'No', 0, 633, 462, 633, 462),
	array('Malwa', 'Land', 'Yes', 0, 650, 626, 650, 626),
	array('Mewar', 'Land', 'No', 9, 615, 543, 615, 543),
	array('Jodhpur', 'Land', 'Yes', 9, 551, 608, 551, 608),
	array('Ahmadabad', 'Coast', 'No', 0, 552, 680, 552, 680),
	array('Khandesh', 'Land', 'Yes', 0, 583, 745, 583, 745),
	array('Ahmadnagar', 'Coast', 'Yes', 1, 560, 825, 560, 825),
	array('Bijapur', 'Land', 'Yes', 1, 625, 854, 625, 854),
	array('Goa', 'Coast', 'Yes', 1, 572, 909, 572, 909),
	array('Honavar', 'Coast', 'No', 14, 618, 948, 618, 948),
	array('Calicut', 'Coast', 'Yes', 14, 619, 1027, 619, 1027),
	array('Tinnevelly', 'Coast', 'No', 14, 647, 1130, 647, 1130),
	array('Pulicat', 'Coast', 'Yes', 14, 694, 1037, 694, 1037),
	array('Jaffna', 'Coast', 'No', 0, 719, 1151, 719, 1151),
	array('Kandy', 'Coast', 'Yes', 0, 721, 1245, 721, 1245),
	array('Bangalore', 'Coast', 'Yes', 14, 729, 928, 729, 928),
	array('Orissa', 'Coast', 'Yes', 0, 797, 872, 797, 872),
	array('Warangal', 'Land', 'No', 4, 734, 839, 734, 839),
	array('Bidar', 'Land', 'Yes', 0, 674, 780, 674, 780),
	array('Raipur', 'Land', 'Yes', 4, 770, 751, 770, 751),
	array('Berar', 'Land', 'No', 4, 703, 685, 703, 685),
	array('Benares', 'Coast', 'No', 3, 738, 570, 738, 570),
	array('Jabalpur', 'Coast', 'Yes', 4, 769, 642, 769, 642),
	array('Sambalpur', 'Coast', 'Yes', 4, 838, 717, 838, 717),
	array('Bengal', 'Coast', 'No', 0, 949, 710, 949, 710),
	array('Assam', 'Coast', 'No', 0, 1015, 632, 1015, 632),
	array('Arakan', 'Coast', 'Yes', 0, 1053, 823, 1053, 823),
	array('Ava', 'Coast', 'Yes', 0, 1103, 712, 1103, 712),
	array('Shan', 'Coast', 'No', 0, 1186, 711, 1186, 711),
	array('Hanoi', 'Land', 'Yes', 13, 1305, 751, 1305, 751),
	array('Haiphong', 'Coast', 'Yes', 13, 1348, 797, 1348, 797),
	array('Faifo', 'Coast', 'Yes', 13, 1386, 924, 1386, 924),
	array('Champa', 'Coast', 'No', 13, 1414, 1057, 1414, 1057),
	array('Oc Eo', 'Coast', 'Yes', 0, 1372, 1050, 1373, 1053),
	array('Champassak', 'Coast', 'No', 0, 1340, 911, 1340, 911),
	array('Wiangjun', 'Coast', 'No', 0, 1293, 888, 1293, 888),
	array('Khmer', 'Coast', 'No', 0, 1303, 1067, 1303, 1067),
	array('Roi Et', 'Land', 'Yes', 10, 1248, 922, 1248, 922),
	array('Lan Xang', 'Land', 'Yes', 0, 1261, 822, 1261, 822),
	array('Chiangmai', 'Coast', 'No', 0, 1191, 847, 1191, 847),
	array('Pegu', 'Coast', 'No', 0, 1145, 902, 1145, 902),
	array('Ayutthaya', 'Coast', 'Yes', 10, 1231, 1013, 1231, 1013),
	array('Dawei', 'Coast', 'Yes', 10, 1173, 1057, 1173, 1057),
	array('Dawei (West Coast)', 'Coast', 'No', 0, 1163, 1048, 1163, 1048),
	array('Dawei (East Coast)', 'Coast', 'No', 0, 1185, 1055, 1185, 1055),
	array('Chaiya', 'Coast', 'No', 10, 1181, 1184, 1181, 1184),
	array('Chaiya (West Coast)', 'Coast', 'No', 0, 1175, 1190, 1175, 1190),
	array('Chaiya (East Coast)', 'Coast', 'No', 0, 1194, 1189, 1194, 1189),
	array('Kelantan', 'Coast', 'No', 6, 1235, 1269, 1235, 1269),
	array('Pahang', 'Coast', 'Yes', 6, 1285, 1378, 1285, 1378),
	array('Malacca', 'Coast', 'Yes', 6, 1240, 1353, 1240, 1353),
	array('Riau', 'Coast', 'Yes', 6, 1231, 1414, 1231, 1414),
	array('Pedir', 'Coast', 'No', 0, 1170, 1344, 1170, 1344),
	array('Aceh', 'Coast', 'Yes', 0, 1105, 1302, 1105, 1302),
	array('Minangkabau', 'Coast', 'No', 0, 1218, 1487, 1218, 1487),
	array('Jambi', 'Coast', 'No', 0, 1290, 1503, 1290, 1503),
	array('Palembang', 'Coast', 'Yes', 0, 1311, 1588, 1311, 1588),
	array('Pajajaran', 'Coast', 'Yes', 5, 1366, 1634, 1366, 1634),
	array('Javadvipa', 'Coast', 'Yes', 5, 1458, 1675, 1458, 1675),
	array('Javadvipa (North Coast)', 'Coast', 'No', 5, 1456, 1660, 1456, 1660),
	array('Javadvipa (South Coast)', 'Coast', 'No', 5, 1460, 1682, 1460, 1682),
	array('Trowulan', 'Coast', 'Yes', 5, 1496, 1664, 1496, 1664),
	array('Lumajang', 'Coast', 'No', 5, 1533, 1697, 1533, 1697),
	array('Wehali', 'Coast', 'Yes', 0, 1809, 1743, 1809, 1743),
	array('Seram', 'Coast', 'Yes', 11, 1924, 1538, 1924, 1538),
	array('Buru', 'Coast', 'Yes', 11, 1866, 1549, 1866, 1549),
	array('Halmahera', 'Coast', 'Yes', 11, 1893, 1388, 1893, 1388),
	array('Luwuk', 'Coast', 'No', 0, 1750, 1479, 1750, 1479),
	array('Minahassa', 'Coast', 'Yes', 0, 1730, 1410, 1730, 1410),
	array('Makassar', 'Coast', 'Yes', 0, 1685, 1537, 1685, 1537),
	array('Buton', 'Coast', 'No', 0, 1740, 1544, 1740, 1544),
	array('Kutai', 'Coast', 'No', 0, 1619, 1385, 1619, 1385),
	array('Tunku', 'Coast', 'Yes', 2, 1643, 1263, 1643, 1263),
	array('Negara Daha', 'Land', 'Yes', 0, 1545, 1437, 1545, 1437),
	array('Sampit', 'Coast', 'Yes', 0, 1499, 1534, 1499, 1534),
	array('Sukadana', 'Coast', 'No', 0, 1479, 1416, 1479, 1416),
	array('Sukadana (North Coast)', 'Coast', 'No', 0, 1478, 1401, 1478, 1401),
	array('Sukadana (West Coast)', 'Coast', 'No', 0, 1460, 1483, 1460, 1483),
	array('Sambas', 'Coast', 'Yes', 0, 1431, 1392, 1431, 1392),
	array('Palawan', 'Coast', 'Yes', 2, 1662, 1135, 1662, 1135),
	array('Brunei', 'Coast', 'Yes', 2, 1574, 1300, 1574, 1300),
	array('Zamboanga', 'Coast', 'Yes', 0, 1778, 1189, 1778, 1189),
	array('Mindanao', 'Coast', 'No', 0, 1833, 1243, 1833, 1243),
	array('Butuan', 'Coast', 'Yes', 0, 1844, 1153, 1844, 1153),
	array('Bikol', 'Coast', 'No', 12, 1799, 1036, 1799, 1036),
	array('Namayan', 'Coast', 'Yes', 12, 1727, 996, 1727, 996),
	array('Namayan (East Coast)', 'Coast', 'No', 12, 1736, 967, 1736, 967),
	array('Namayan (South Coast)', 'Coast', 'No', 12, 1727, 999, 1727, 999),
	array('Tondo', 'Coast', 'Yes', 12, 1698, 936, 1698, 936),
	array('Kasiguran', 'Coast', 'Yes', 12, 1737, 927, 1737, 927),
	array('Luson', 'Coast', 'No', 0, 1730, 880, 1730, 880),
	array('Taiwan', 'Coast', 'Yes', 0, 1709, 702, 1709, 702),
	array('East China Sea', 'Sea', 'No', 0, 1871, 658, 1871, 658),
	array('Strait of Taiwan', 'Sea', 'No', 0, 1670, 675, 1670, 675),
	array('South China Sea', 'Sea', 'No', 0, 1559, 843, 1559, 843),
	array('Gulf of Dai Viet', 'Sea', 'No', 0, 1403, 831, 1403, 831),
	array('Mait Sea', 'Sea', 'No', 0, 1620, 987, 1620, 987),
	array('Sibuyan Sea', 'Sea', 'No', 0, 1743, 1072, 1743, 1072),
	array('Visayas Sea', 'Sea', 'No', 0, 1788, 1098, 1788, 1098),
	array('Luson Sea', 'Sea', 'No', 0, 1856, 1028, 1856, 1028),
	array('Eastern Ocean', 'Sea', 'No', 0, 1961, 1164, 1961, 1164),
	array('Sulawesi Sea', 'Sea', 'No', 0, 1755, 1311, 1755, 1311),
	array('Sulu Sea', 'Sea', 'No', 0, 1686, 1203, 1686, 1203),
	array('Champa Sea', 'Sea', 'No', 0, 1497, 1188, 1497, 1188),
	array('Karimata Sea', 'Sea', 'No', 0, 1350, 1311, 1350, 1311),
	array('Gulf of Siam', 'Sea', 'No', 0, 1241, 1149, 1241, 1149),
	array('Java Sea', 'Sea', 'No', 0, 1424, 1585, 1424, 1585),
	array('Makassar Strait', 'Sea', 'No', 0, 1625, 1639, 1625, 1639),
	array('Banda Sea', 'Sea', 'No', 0, 1808, 1591, 1808, 1591),
	array('Arafura Sea', 'Sea', 'No', 0, 1943, 1678, 1943, 1678),
	array('Moluccan Sea', 'Sea', 'No', 0, 1901, 1492, 1901, 1492),
	array('Gulf of Tomini', 'Sea', 'No', 0, 1841, 1432, 1841, 1432),
	array('Timor Sea', 'Sea', 'No', 0, 1592, 1771, 1592, 1771),
	array('Southern Ocean', 'Sea', 'No', 0, 891, 1645, 891, 1645),
	array('Mentawai Sea', 'Sea', 'No', 0, 1010, 1395, 1010, 1395),
	array('Straits of Malacca', 'Sea', 'No', 0, 1176, 1279, 1176, 1279),
	array('Andaman Sea', 'Sea', 'No', 0, 993, 1099, 993, 1099),
	array('Bay of Bengal', 'Sea', 'No', 0, 894, 937, 894, 937),
	array('Maldive Sea', 'Sea', 'No', 0, 296, 1498, 296, 1498),
	array('Laccadive Sea', 'Sea', 'No', 0, 371, 1110, 371, 1110),
	array('Gulf of Aden', 'Sea', 'No', 0, 125, 1054, 125, 1054),
	array('Arabian Sea', 'Sea', 'No', 0, 360, 846, 360, 846),
	array('Persian Gulf', 'Sea', 'No', 0, 75, 634, 75, 634)
);

foreach($territoryRawData as $territoryRawRow)
{
	list($name, $type, $supply, $countryID, $x, $y, $sx, $sy)=$territoryRawRow;
	new InstallTerritory($name, $type, $supply, $countryID, $x, $y, $sx, $sy);
}
unset($territoryRawData);

$bordersRawData=array(
	array('Arabia','Rub Al Khali','No','Yes'),
	array('Arabia','Oman','Yes','Yes'),
	array('Arabia','Yemen','No','Yes'),
	array('Arabia','Persian Gulf','Yes','No'),
	array('Rub Al Khali','Oman','No','Yes'),
	array('Rub Al Khali','Hadramaut','No','Yes'),
	array('Rub Al Khali','Yemen','No','Yes'),
	array('Oman','Hadramaut','Yes','Yes'),
	array('Oman','Arabian Sea','Yes','No'),
	array('Oman','Persian Gulf','Yes','No'),
	array('Hadramaut','Yemen','Yes','Yes'),
	array('Hadramaut','Gulf of Aden','Yes','No'),
	array('Hadramaut','Arabian Sea','Yes','No'),
	array('Yemen','Gulf of Aden','Yes','No'),
	array('Seylac','Maldive Sea','Yes','No'),
	array('Seylac','Laccadive Sea','Yes','No'),
	array('Seylac','Gulf of Aden','Yes','No'),
	array('Qom','Elburz','No','Yes'),
	array('Qom','Dasht-I-Kavir','No','Yes'),
	array('Qom','Isfahan','Yes','Yes'),
	array('Qom','Persian Gulf','Yes','No'),
	array('Elburz','Dasht-I-Kavir','No','Yes'),
	array('Elburz','Meshed','No','Yes'),
	array('Elburz','Kara Kum','No','Yes'),
	array('Dasht-I-Kavir','Isfahan','No','Yes'),
	array('Dasht-I-Kavir','Yezd','No','Yes'),
	array('Dasht-I-Kavir','Meshed','No','Yes'),
	array('Isfahan','Hormuz','Yes','Yes'),
	array('Isfahan','Shiraz','No','Yes'),
	array('Isfahan','Yezd','No','Yes'),
	array('Isfahan','Persian Gulf','Yes','No'),
	array('Hormuz','Shiraz','No','Yes'),
	array('Hormuz','Sind','Yes','Yes'),
	array('Hormuz','Arabian Sea','Yes','No'),
	array('Hormuz','Persian Gulf','Yes','No'),
	array('Shiraz','Yezd','No','Yes'),
	array('Shiraz','Kandahar','No','Yes'),
	array('Shiraz','Peshawar','No','Yes'),
	array('Shiraz','Sind','No','Yes'),
	array('Yezd','Meshed','No','Yes'),
	array('Yezd','Herat','No','Yes'),
	array('Yezd','Kandahar','No','Yes'),
	array('Meshed','Kara Kum','No','Yes'),
	array('Meshed','Bukhara','No','Yes'),
	array('Meshed','Herat','No','Yes'),
	array('Kara Kum','Bukhara','No','Yes'),
	array('Bukhara','Samarkand','No','Yes'),
	array('Bukhara','Balkh','No','Yes'),
	array('Bukhara','Herat','No','Yes'),
	array('Samarkand','Balkh','No','Yes'),
	array('Samarkand','Badakhshan','No','Yes'),
	array('Samarkand','Ferghana','No','Yes'),
	array('Balkh','Herat','No','Yes'),
	array('Balkh','Kandahar','No','Yes'),
	array('Balkh','Kabul','No','Yes'),
	array('Balkh','Badakhshan','No','Yes'),
	array('Herat','Kandahar','No','Yes'),
	array('Kandahar','Peshawar','No','Yes'),
	array('Kandahar','Kabul','No','Yes'),
	array('Peshawar','Sind','Yes','Yes'),
	array('Peshawar','Jaisalmer','Yes','Yes'),
	array('Peshawar','Bikaner','Yes','Yes'),
	array('Peshawar','Multan','Yes','Yes'),
	array('Peshawar','Lahore','Yes','Yes'),
	array('Peshawar','Kabul','Yes','Yes'),
	array('Sind','Gujarat','Yes','Yes'),
	array('Sind','Jaisalmer','Yes','Yes'),
	array('Sind','Arabian Sea','Yes','No'),
	array('Gujarat','Jaisalmer','Yes','Yes'),
	array('Gujarat','Jodhpur','No','Yes'),
	array('Gujarat','Ahmadabad','Yes','Yes'),
	array('Gujarat','Arabian Sea','Yes','No'),
	array('Jaisalmer','Bikaner','Yes','Yes'),
	array('Jaisalmer','Jodhpur','No','Yes'),
	array('Bikaner','Multan','Yes','Yes'),
	array('Bikaner','Jaipur','No','Yes'),
	array('Bikaner','Mewar','No','Yes'),
	array('Bikaner','Jodhpur','No','Yes'),
	array('Multan','Lahore','Yes','Yes'),
	array('Multan','Jaipur','No','Yes'),
	array('Lahore','Kabul','Yes','Yes'),
	array('Lahore','Kashmir','No','Yes'),
	array('Lahore','Agra','No','Yes'),
	array('Lahore','Jaipur','No','Yes'),
	array('Kabul','Badakhshan','No','Yes'),
	array('Kabul','Kashmir','No','Yes'),
	array('Badakhshan','Ferghana','No','Yes'),
	array('Badakhshan','Kashmir','No','Yes'),
	array('Ferghana','Taklamakan','No','Yes'),
	array('Ferghana','Kashmir','No','Yes'),
	array('Taklamakan','Kashmir','No','Yes'),
	array('Taklamakan','Tibet','No','Yes'),
	array('Kashmir','Tibet','No','Yes'),
	array('Kashmir','Awadh','No','Yes'),
	array('Kashmir','Agra','No','Yes'),
	array('Tibet','Sutiya','No','Yes'),
	array('Tibet','Nepal','No','Yes'),
	array('Tibet','Awadh','No','Yes'),
	array('Tibet','Shan','No','Yes'),
	array('Sutiya','Nepal','No','Yes'),
	array('Sutiya','Muzaffarpur','Yes','Yes'),
	array('Sutiya','Assam','Yes','Yes'),
	array('Sutiya','Shan','No','Yes'),
	array('Nepal','Muzaffarpur','No','Yes'),
	array('Nepal','Awadh','No','Yes'),
	array('Muzaffarpur','Awadh','Yes','Yes'),
	array('Muzaffarpur','Agra','Yes','Yes'),
	array('Muzaffarpur','Benares','Yes','Yes'),
	array('Muzaffarpur','Bengal','Yes','Yes'),
	array('Muzaffarpur','Assam','Yes','Yes'),
	array('Awadh','Agra','Yes','Yes'),
	array('Agra','Jaipur','No','Yes'),
	array('Agra','Benares','Yes','Yes'),
	array('Jaipur','Malwa','No','Yes'),
	array('Jaipur','Mewar','No','Yes'),
	array('Jaipur','Benares','No','Yes'),
	array('Malwa','Mewar','No','Yes'),
	array('Malwa','Ahmadabad','No','Yes'),
	array('Malwa','Bidar','No','Yes'),
	array('Malwa','Berar','No','Yes'),
	array('Malwa','Benares','No','Yes'),
	array('Mewar','Jodhpur','No','Yes'),
	array('Mewar','Ahmadabad','No','Yes'),
	array('Jodhpur','Ahmadabad','No','Yes'),
	array('Ahmadabad','Khandesh','No','Yes'),
	array('Ahmadabad','Ahmadnagar','Yes','Yes'),
	array('Ahmadabad','Bidar','No','Yes'),
	array('Ahmadabad','Arabian Sea','Yes','No'),
	array('Khandesh','Ahmadnagar','No','Yes'),
	array('Khandesh','Bijapur','No','Yes'),
	array('Khandesh','Bidar','No','Yes'),
	array('Ahmadnagar','Bijapur','No','Yes'),
	array('Ahmadnagar','Goa','Yes','Yes'),
	array('Ahmadnagar','Arabian Sea','Yes','No'),
	array('Bijapur','Goa','No','Yes'),
	array('Bijapur','Honavar','No','Yes'),
	array('Bijapur','Bidar','No','Yes'),
	array('Goa','Honavar','Yes','Yes'),
	array('Goa','Laccadive Sea','Yes','No'),
	array('Goa','Arabian Sea','Yes','No'),
	array('Honavar','Calicut','Yes','Yes'),
	array('Honavar','Bangalore','No','Yes'),
	array('Honavar','Bidar','No','Yes'),
	array('Honavar','Laccadive Sea','Yes','No'),
	array('Calicut','Tinnevelly','Yes','Yes'),
	array('Calicut','Pulicat','No','Yes'),
	array('Calicut','Bangalore','No','Yes'),
	array('Calicut','Laccadive Sea','Yes','No'),
	array('Tinnevelly','Pulicat','Yes','Yes'),
	array('Tinnevelly','Maldive Sea','Yes','No'),
	array('Tinnevelly','Laccadive Sea','Yes','No'),
	array('Pulicat','Bangalore','Yes','Yes'),
	array('Pulicat','Andaman Sea','Yes','No'),
	array('Pulicat','Bay of Bengal','Yes','No'),
	array('Pulicat','Maldive Sea','Yes','No'),
	array('Jaffna','Kandy','Yes','Yes'),
	array('Jaffna','Andaman Sea','Yes','No'),
	array('Jaffna','Maldive Sea','Yes','No'),
	array('Kandy','Southern Ocean','Yes','No'),
	array('Kandy','Mentawai Sea','Yes','No'),
	array('Kandy','Andaman Sea','Yes','No'),
	array('Kandy','Maldive Sea','Yes','No'),
	array('Bangalore','Orissa','Yes','Yes'),
	array('Bangalore','Warangal','No','Yes'),
	array('Bangalore','Bidar','No','Yes'),
	array('Bangalore','Bay of Bengal','Yes','No'),
	array('Orissa','Warangal','No','Yes'),
	array('Orissa','Sambalpur','Yes','Yes'),
	array('Orissa','Bengal','Yes','Yes'),
	array('Orissa','Bay of Bengal','Yes','No'),
	array('Warangal','Bidar','No','Yes'),
	array('Warangal','Raipur','No','Yes'),
	array('Warangal','Sambalpur','No','Yes'),
	array('Bidar','Raipur','No','Yes'),
	array('Bidar','Berar','No','Yes'),
	array('Raipur','Berar','No','Yes'),
	array('Raipur','Jabalpur','No','Yes'),
	array('Raipur','Sambalpur','No','Yes'),
	array('Berar','Benares','No','Yes'),
	array('Berar','Jabalpur','No','Yes'),
	array('Benares','Jabalpur','Yes','Yes'),
	array('Benares','Bengal','Yes','Yes'),
	array('Jabalpur','Sambalpur','Yes','Yes'),
	array('Jabalpur','Bengal','Yes','Yes'),
	array('Sambalpur','Bengal','Yes','Yes'),
	array('Bengal','Assam','Yes','Yes'),
	array('Bengal','Arakan','Yes','Yes'),
	array('Bengal','Bay of Bengal','Yes','No'),
	array('Assam','Arakan','No','Yes'),
	array('Assam','Ava','No','Yes'),
	array('Assam','Shan','No','Yes'),
	array('Arakan','Ava','Yes','Yes'),
	array('Arakan','Chiangmai','Yes','Yes'),
	array('Arakan','Pegu','Yes','Yes'),
	array('Arakan','Andaman Sea','Yes','No'),
	array('Arakan','Bay of Bengal','Yes','No'),
	array('Ava','Shan','Yes','Yes'),
	array('Ava','Chiangmai','Yes','Yes'),
	array('Shan','Hanoi','No','Yes'),
	array('Shan','Lan Xang','No','Yes'),
	array('Shan','Chiangmai','Yes','Yes'),
	array('Hanoi','Haiphong','No','Yes'),
	array('Hanoi','Faifo','No','Yes'),
	array('Hanoi','Champassak','No','Yes'),
	array('Hanoi','Lan Xang','No','Yes'),
	array('Haiphong','Faifo','Yes','Yes'),
	array('Haiphong','Gulf of Dai Viet','Yes','No'),
	array('Faifo','Champa','Yes','Yes'),
	array('Faifo','Oc Eo','No','Yes'),
	array('Faifo','Champassak','No','Yes'),
	array('Faifo','South China Sea','Yes','No'),
	array('Faifo','Gulf of Dai Viet','Yes','No'),
	array('Champa','Oc Eo','Yes','Yes'),
	array('Champa','South China Sea','Yes','No'),
	array('Champa','Champa Sea','Yes','No'),
	array('Oc Eo','Champassak','Yes','Yes'),
	array('Oc Eo','Wiangjun','Yes','Yes'),
	array('Oc Eo','Khmer','Yes','Yes'),
	array('Oc Eo','Champa Sea','Yes','No'),
	array('Oc Eo','Karimata Sea','Yes','No'),
	array('Oc Eo','Gulf of Siam','Yes','No'),
	array('Champassak','Wiangjun','Yes','Yes'),
	array('Champassak','Lan Xang','No','Yes'),
	array('Wiangjun','Khmer','Yes','Yes'),
	array('Wiangjun','Roi Et','No','Yes'),
	array('Wiangjun','Lan Xang','No','Yes'),
	array('Khmer','Roi Et','No','Yes'),
	array('Khmer','Ayutthaya','Yes','Yes'),
	array('Khmer','Gulf of Siam','Yes','No'),
	array('Roi Et','Lan Xang','No','Yes'),
	array('Roi Et','Chiangmai','No','Yes'),
	array('Roi Et','Pegu','No','Yes'),
	array('Roi Et','Ayutthaya','No','Yes'),
	array('Lan Xang','Chiangmai','No','Yes'),
	array('Chiangmai','Pegu','Yes','Yes'),
	array('Pegu','Ayutthaya','No','Yes'),
	array('Pegu','Dawei','No','Yes'),
	array('Pegu','Dawei (West Coast)','Yes','No'),
	array('Pegu','Andaman Sea','Yes','No'),
	array('Ayutthaya','Dawei','No','Yes'),
	array('Ayutthaya','Dawei (East Coast)','Yes','No'),
	array('Ayutthaya','Gulf of Siam','Yes','No'),
	array('Dawei','Chaiya','No','Yes'),
	array('Dawei (West Coast)','Chaiya (West Coast)','Yes','No'),
	array('Dawei (West Coast)','Andaman Sea','Yes','No'),
	array('Dawei (East Coast)','Chaiya (East Coast)','Yes','No'),
	array('Dawei (East Coast)','Gulf of Siam','Yes','No'),
	array('Chaiya','Kelantan','No','Yes'),
	array('Chaiya','Malacca','No','Yes'),
	array('Chaiya (West Coast)','Malacca','Yes','No'),
	array('Chaiya (West Coast)','Straits of Malacca','Yes','No'),
	array('Chaiya (West Coast)','Andaman Sea','Yes','No'),
	array('Chaiya (East Coast)','Kelantan','Yes','No'),
	array('Chaiya (East Coast)','Gulf of Siam','Yes','No'),
	array('Kelantan','Pahang','Yes','Yes'),
	array('Kelantan','Malacca','No','Yes'),
	array('Kelantan','Karimata Sea','Yes','No'),
	array('Kelantan','Gulf of Siam','Yes','No'),
	array('Pahang','Malacca','Yes','Yes'),
	array('Pahang','Karimata Sea','Yes','No'),
	array('Pahang','Straits of Malacca','Yes','No'),
	array('Malacca','Straits of Malacca','Yes','No'),
	array('Riau','Pedir','Yes','Yes'),
	array('Riau','Minangkabau','No','Yes'),
	array('Riau','Jambi','Yes','Yes'),
	array('Riau','Karimata Sea','Yes','No'),
	array('Riau','Straits of Malacca','Yes','No'),
	array('Pedir','Aceh','Yes','Yes'),
	array('Pedir','Minangkabau','No','Yes'),
	array('Pedir','Straits of Malacca','Yes','No'),
	array('Aceh','Minangkabau','Yes','Yes'),
	array('Aceh','Mentawai Sea','Yes','No'),
	array('Aceh','Straits of Malacca','Yes','No'),
	array('Aceh','Andaman Sea','Yes','No'),
	array('Minangkabau','Jambi','No','Yes'),
	array('Minangkabau','Palembang','Yes','Yes'),
	array('Minangkabau','Mentawai Sea','Yes','No'),
	array('Jambi','Palembang','Yes','Yes'),
	array('Jambi','Karimata Sea','Yes','No'),
	array('Palembang','Karimata Sea','Yes','No'),
	array('Palembang','Java Sea','Yes','No'),
	array('Palembang','Mentawai Sea','Yes','No'),
	array('Pajajaran','Javadvipa','No','Yes'),
	array('Pajajaran','Javadvipa (North Coast)','Yes','No'),
	array('Pajajaran','Javadvipa (South Coast)','Yes','No'),
	array('Pajajaran','Java Sea','Yes','No'),
	array('Pajajaran','Southern Ocean','Yes','No'),
	array('Pajajaran','Mentawai Sea','Yes','No'),
	array('Javadvipa','Trowulan','No','Yes'),
	array('Javadvipa','Lumajang','No','Yes'),
	array('Javadvipa (North Coast)','Trowulan','Yes','No'),
	array('Javadvipa (North Coast)','Java Sea','Yes','No'),
	array('Javadvipa (South Coast)','Lumajang','Yes','No'),
	array('Javadvipa (South Coast)','Timor Sea','Yes','No'),
	array('Javadvipa (South Coast)','Southern Ocean','Yes','No'),
	array('Javadvipa (South Coast)','Mentawai Sea','Yes','No'),
	array('Trowulan','Lumajang','Yes','Yes'),
	array('Trowulan','Java Sea','Yes','No'),
	array('Trowulan','Makassar Strait','Yes','No'),
	array('Lumajang','Makassar Strait','Yes','No'),
	array('Lumajang','Timor Sea','Yes','No'),
	array('Wehali','Makassar Strait','Yes','No'),
	array('Wehali','Banda Sea','Yes','No'),
	array('Wehali','Arafura Sea','Yes','No'),
	array('Wehali','Timor Sea','Yes','No'),
	array('Seram','Eastern Ocean','Yes','No'),
	array('Seram','Arafura Sea','Yes','No'),
	array('Seram','Moluccan Sea','Yes','No'),
	array('Buru','Banda Sea','Yes','No'),
	array('Buru','Arafura Sea','Yes','No'),
	array('Buru','Moluccan Sea','Yes','No'),
	array('Halmahera','Eastern Ocean','Yes','No'),
	array('Halmahera','Moluccan Sea','Yes','No'),
	array('Halmahera','Gulf of Tomini','Yes','No'),
	array('Luwuk','Minahassa','Yes','Yes'),
	array('Luwuk','Makassar','No','Yes'),
	array('Luwuk','Buton','Yes','Yes'),
	array('Luwuk','Banda Sea','Yes','No'),
	array('Luwuk','Gulf of Tomini','Yes','No'),
	array('Minahassa','Makassar','Yes','Yes'),
	array('Minahassa','Sulawesi Sea','Yes','No'),
	array('Minahassa','Makassar Strait','Yes','No'),
	array('Minahassa','Gulf of Tomini','Yes','No'),
	array('Makassar','Buton','Yes','Yes'),
	array('Makassar','Makassar Strait','Yes','No'),
	array('Makassar','Banda Sea','Yes','No'),
	array('Buton','Banda Sea','Yes','No'),
	array('Kutai','Tunku','Yes','Yes'),
	array('Kutai','Negara Daha','No','Yes'),
	array('Kutai','Sampit','Yes','Yes'),
	array('Kutai','Brunei','No','Yes'),
	array('Kutai','Sulawesi Sea','Yes','No'),
	array('Kutai','Makassar Strait','Yes','No'),
	array('Tunku','Brunei','Yes','Yes'),
	array('Tunku','Sulawesi Sea','Yes','No'),
	array('Tunku','Sulu Sea','Yes','No'),
	array('Negara Daha','Sampit','No','Yes'),
	array('Negara Daha','Sukadana','No','Yes'),
	array('Negara Daha','Brunei','No','Yes'),
	array('Sampit','Sukadana','No','Yes'),
	array('Sampit','Sukadana (West Coast)','Yes','No'),
	array('Sampit','Java Sea','Yes','No'),
	array('Sampit','Makassar Strait','Yes','No'),
	array('Sukadana','Sambas','No','Yes'),
	array('Sukadana','Brunei','No','Yes'),
	array('Sukadana (North Coast)','Sambas','Yes','No'),
	array('Sukadana (North Coast)','Brunei','Yes','No'),
	array('Sukadana (North Coast)','Champa Sea','Yes','No'),
	array('Sukadana (West Coast)','Sambas','Yes','No'),
	array('Sukadana (West Coast)','Java Sea','Yes','No'),
	array('Sambas','Champa Sea','Yes','No'),
	array('Sambas','Karimata Sea','Yes','No'),
	array('Sambas','Java Sea','Yes','No'),
	array('Palawan','Mait Sea','Yes','No'),
	array('Palawan','Sulu Sea','Yes','No'),
	array('Palawan','Champa Sea','Yes','No'),
	array('Brunei','Sulu Sea','Yes','No'),
	array('Brunei','Champa Sea','Yes','No'),
	array('Zamboanga','Mindanao','Yes','Yes'),
	array('Zamboanga','Butuan','Yes','Yes'),
	array('Zamboanga','Visayas Sea','Yes','No'),
	array('Zamboanga','Sulawesi Sea','Yes','No'),
	array('Zamboanga','Sulu Sea','Yes','No'),
	array('Mindanao','Butuan','Yes','Yes'),
	array('Mindanao','Eastern Ocean','Yes','No'),
	array('Mindanao','Sulawesi Sea','Yes','No'),
	array('Butuan','Visayas Sea','Yes','No'),
	array('Butuan','Luson Sea','Yes','No'),
	array('Butuan','Eastern Ocean','Yes','No'),
	array('Bikol','Namayan','No','Yes'),
	array('Bikol','Namayan (East Coast)','Yes','No'),
	array('Bikol','Namayan (South Coast)','Yes','No'),
	array('Bikol','Visayas Sea','Yes','No'),
	array('Bikol','Luson Sea','Yes','No'),
	array('Namayan','Tondo','No','Yes'),
	array('Namayan','Kasiguran','No','Yes'),
	array('Namayan (East Coast)','Kasiguran','Yes','No'),
	array('Namayan (East Coast)','Luson Sea','Yes','No'),
	array('Namayan (South Coast)','Tondo','Yes','No'),
	array('Namayan (South Coast)','Sibuyan Sea','Yes','No'),
	array('Namayan (South Coast)','Visayas Sea','Yes','No'),
	array('Tondo','Kasiguran','No','Yes'),
	array('Tondo','Luson','Yes','Yes'),
	array('Tondo','Mait Sea','Yes','No'),
	array('Tondo','Sibuyan Sea','Yes','No'),
	array('Kasiguran','Luson','Yes','Yes'),
	array('Kasiguran','East China Sea','Yes','No'),
	array('Kasiguran','Luson Sea','Yes','No'),
	array('Luson','East China Sea','Yes','No'),
	array('Luson','South China Sea','Yes','No'),
	array('Luson','Mait Sea','Yes','No'),
	array('Taiwan','East China Sea','Yes','No'),
	array('Taiwan','Strait of Taiwan','Yes','No'),
	array('Taiwan','South China Sea','Yes','No'),
	array('East China Sea','Strait of Taiwan','Yes','No'),
	array('East China Sea','South China Sea','Yes','No'),
	array('East China Sea','Luson Sea','Yes','No'),
	array('East China Sea','Eastern Ocean','Yes','No'),
	array('Strait of Taiwan','South China Sea','Yes','No'),
	array('South China Sea','Gulf of Dai Viet','Yes','No'),
	array('South China Sea','Mait Sea','Yes','No'),
	array('South China Sea','Champa Sea','Yes','No'),
	array('Mait Sea','Sibuyan Sea','Yes','No'),
	array('Mait Sea','Sulu Sea','Yes','No'),
	array('Mait Sea','Champa Sea','Yes','No'),
	array('Sibuyan Sea','Visayas Sea','Yes','No'),
	array('Sibuyan Sea','Sulu Sea','Yes','No'),
	array('Visayas Sea','Luson Sea','Yes','No'),
	array('Visayas Sea','Sulu Sea','Yes','No'),
	array('Luson Sea','Eastern Ocean','Yes','No'),
	array('Eastern Ocean','Sulawesi Sea','Yes','No'),
	array('Eastern Ocean','Arafura Sea','Yes','No'),
	array('Eastern Ocean','Moluccan Sea','Yes','No'),
	array('Eastern Ocean','Gulf of Tomini','Yes','No'),
	array('Sulawesi Sea','Sulu Sea','Yes','No'),
	array('Sulawesi Sea','Makassar Strait','Yes','No'),
	array('Sulawesi Sea','Gulf of Tomini','Yes','No'),
	array('Sulu Sea','Champa Sea','Yes','No'),
	array('Champa Sea','Karimata Sea','Yes','No'),
	array('Karimata Sea','Gulf of Siam','Yes','No'),
	array('Karimata Sea','Java Sea','Yes','No'),
	array('Karimata Sea','Straits of Malacca','Yes','No'),
	array('Java Sea','Makassar Strait','Yes','No'),
	array('Java Sea','Mentawai Sea','Yes','No'),
	array('Makassar Strait','Banda Sea','Yes','No'),
	array('Makassar Strait','Timor Sea','Yes','No'),
	array('Banda Sea','Arafura Sea','Yes','No'),
	array('Banda Sea','Moluccan Sea','Yes','No'),
	array('Banda Sea','Gulf of Tomini','Yes','No'),
	array('Arafura Sea','Moluccan Sea','Yes','No'),
	array('Arafura Sea','Timor Sea','Yes','No'),
	array('Moluccan Sea','Gulf of Tomini','Yes','No'),
	array('Timor Sea','Southern Ocean','Yes','No'),
	array('Southern Ocean','Mentawai Sea','Yes','No'),
	array('Southern Ocean','Maldive Sea','Yes','No'),
	array('Mentawai Sea','Andaman Sea','Yes','No'),
	array('Straits of Malacca','Andaman Sea','Yes','No'),
	array('Andaman Sea','Bay of Bengal','Yes','No'),
	array('Andaman Sea','Maldive Sea','Yes','No'),
	array('Maldive Sea','Laccadive Sea','Yes','No'),
	array('Laccadive Sea','Gulf of Aden','Yes','No'),
	array('Laccadive Sea','Arabian Sea','Yes','No'),
	array('Gulf of Aden','Arabian Sea','Yes','No'),
	array('Arabian Sea','Persian Gulf','Yes','No')
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
