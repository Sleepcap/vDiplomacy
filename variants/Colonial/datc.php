<?php
// This is the file that installs additional test cases for the Colonial variant
defined('IN_CODE') or die('This script can not be run by itself.');
require_once("variants/variantDATC.php");

TestCase::$TestCases = array();
$testCaseData = array(
	//SUEZ
	array('SUEZ.A1','TEST CASE, SUEZ PERMISSION WITH UNIT IN EGYPT',
		array(
			array(1,'Army',66,'Hold',NULL,NULL,'No','Success','Yes'),
			array(1,'Fleet',126,'Support hold',101,NULL,'No','Success','Yes'),
			array(7,'Fleet',101,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.A2','TEST CASE, NO SUEZ PERMISSION WITHOUT UNIT IN EGYPT',
		array(
			// INVALID
			//array(1,'Fleet',126,'Support hold',101,NULL,'No','Hold','No'),
			//array(7,'Fleet',101,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.A3','TEST CASE, NO SUEZ PERMISSION WITH FOREIGN UNIT IN EGYPT',
		array(
			// INVALID 
			//array(7,'Army',66,'Hold',NULL,NULL,'No','Success','Yes'),
			//array(1,'Fleet',126,'Support hold',101,NULL,'No','Hold','No'),
			//array(7,'Fleet',101,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.A4','TEST CASE, SUEZ PERMISSION EVEN VALID IF UNIT LEAVES EGYPT',
		array(
			array(1,'Army',66,'Move',25,NULL,'No','Success','Yes'),
			array(1,'Fleet',126,'Support hold',101,NULL,'No','Success','Yes'),
			array(7,'Fleet',101,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.A5','TEST CASE, SUEZ PERMISSION NOT VALID IF UNIT JUST ENTERS EGYPT',
		array(
			// INVALID 
			//array(1,'Army',25,'Move',66,NULL,'No','Success','Yes'),
			//array(1,'Fleet',126,'Support hold',101,NULL,'No','Hold','No'),
			//array(7,'Fleet',101,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.A6','TEST CASE, SUEZ PERMISSION IF EGYPTIAN UNIT IS DISLODGED',
		array(
			array(1,'Army',66,'Hold',NULL,NULL,'No','Dislodged','Yes'),
			array(1,'Fleet',126,'Support hold',101,NULL,'No','Success','Yes'),
			array(7,'Fleet',101,'Support move',66,25,'No','Success','Yes'),
			array(7,'Fleet',25,'Move',66,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.B1','TEST CASE, SUEZ CANAL NAVIGABLE WITH PERMISSION',
		array(
			array(1,'Fleet',66,'Hold',NULL,NULL,'No','Success','Yes'),
			array(1,'Fleet',126,'Support hold',101,NULL,'No','Success','Yes'),
			array(7,'Fleet',101,'Move',99,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.B2','TEST CASE, SUEZ CANAL NOT NAVIGALBE WITHOUT PERMISSION',
		array(
			array(1,'Fleet',66,'Hold',NULL,NULL,'No','Success','Yes'),
			array(1,'Fleet',126,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Fleet',101,'Move',99,NULL,'No','Hold','Yes')
		)
	),
	array('SUEZ.B3','TEST CASE, SUEZ CANAL ONLY NAVIGABLE IN ONE DIRECTION',
		array(
			array(1,'Fleet',66,'Hold',NULL,NULL,'No','Success','Yes'),
			array(1,'Fleet',126,'Support hold',99,NULL,'No','Success','Yes'),
			array(7,'Fleet',101,'Move',99,NULL,'No','Hold','Yes'),
			array(7,'Fleet',99,'Move',70,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.C1','TEST CASE, SUPPORT MOVE FOR SUEZ MOVE VALID',
		array(
			array(1,'Fleet',25,'Support move',101,99,'No','Success','Yes'),
			array(7,'Fleet',99,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.C2','TEST CASE, HEAD-TO-HEAD THROUGH SUEZ WITH SUPPORT',
		array(
			array(7,'Army',66,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Fleet',126,'Support hold',99,NULL,'No','Success','Yes'),
			array(1,'Fleet',101,'Move',99,NULL,'No','Dislodged','Yes'),
			array(1,'Fleet',70,'Support move',99,101,'No','Hold','Yes'),
			array(4,'Fleet',99,'Move',101,NULL,'No','Success','Yes'),
			array(4,'Fleet',25,'Support move',101,99,'No','Success','Yes'),
		)
	),
	array('SUEZ.D1','TEST CASE, NO SUPPORT HOLD THROUGH SUEZ CANAL',
		array(
			array(1,'Fleet',66,'Hold',NULL,NULL,'No','Success','Yes'),
			array(1,'Fleet',126,'Support hold',101,NULL,'No','Success','Yes'),
			array(7,'Fleet',101,'Support hold',99,NULL,'No','Hold','No'),
			array(7,'Fleet',99,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('SUEZ.D2','TEST CASE, NO SUPPORT MOVE THROUGH SUEZ CANAL',
		array(
			array(1,'Fleet',66,'Hold',NULL,NULL,'No','Success','Yes'),
			array(1,'Fleet',126,'Support hold',101,NULL,'No','Success','Yes'),
			array(7,'Fleet',101,'Support move',99,70,'No','Hold','No')
		)
	),
	array('SUEZ.D3','TEST CASE, NO CONVOY THROUGH SUEZ CANAL',
		array(
			array(1,'Fleet',66,'Hold',NULL,NULL,'No','Success','Yes'),
			array(1,'Fleet',126,'Support hold',101,NULL,'No','Success','Yes'),
			array(7,'Fleet',101,'Convoy',25,70,'No','Hold','No'),
			array(7,'Fleet',99,'Convoy',25,70,'No','Hold','No'),
			array(7,'Army',70,'Move',25,NULL,'Yes','Hold','No')
		)
	),
	
	// TSR
	// Note: Interrupted TSRs have to be checked with tricks for correct result
	// as the standard DATC does only know Success, Hold, Dislodged as outcome
	// (interrupted TSRs are a "Success")
	// In most cases a 'tester' unit is used behind a blockade (is bounced back,
	// if blockade successful)
	
	// Basic usage
	array('TSR.A1','TEST CASE, BASIC USAGE OF TSR',
		array(
			array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes')
		)
	),
	array('TSR.A2','TEST CASE, ONLY RUSSIA MAY USE THE TSR',
		array(
			array(2,'Army',35,'Move',30,NULL,'Yes','Hold','No')
		)
	),
	array('TSR.A3','TEST CASE, ONLY ONE UNIT MAY USE THE TSR',
		array(
			// INVALID
			//array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes'),
			//array(6,'Army',40,'Move',29,NULL,'Yes','Success','Yes')
		)
	),
	array('TSR.A4','TEST CASE, ONLY ARMIES MAY USE THE TSR',
		array(
			array(6,'Fleet',30,'Move',29,NULL,'Yes','Hold','No'),
		)
	),
	
	// Units along route
	array('TSR.B1a','TEST CASE, IGNORE HOLDING RUSSIAN UNIT ON ROUTE',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(6,'Army',29,'Support hold',36,NULL,'No','Success','Yes'),
			array(2,'Army',36,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B1b','TEST CASE, IGNORE RUSSIAN UNIT MOVING ALONG ROUTE AGAINST TSR MOVEMENT',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(6,'Army',29,'Move',39,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B1c','TEST CASE, IGNORE RUSSIAN UNIT MOVING ALONG ROUTE WITH TSR MOVEMENT',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(6,'Army',39,'Move',29,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B1d','TEST CASE, IGNORE RUSSIAN UNIT ENTERING THE ROUTE',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(6,'Army',36,'Move',29,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B1e','TEST CASE, IGNORE RUSSIAN UNIT LEAVING THE ROUTE',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(6,'Army',29,'Move',36,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B2a','TEST CASE, FOREIGN UNIT HOLDING/SUPPORTING ON ROUTE BLOCKS ROUTE AND A SUPPORT IS NOT CUT',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(2,'Army',29,'Support hold',36,NULL,'No','Success','Yes'),
			array(2,'Army',36,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B2b','TEST CASE, FOREIGN UNIT MOVING ALONG ROUTE AGAINST TSR MOVEMENT BLOCKS ROUTE WITH STANDOFF',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(2,'Army',29,'Move',39,NULL,'No','Hold','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B2c','TEST CASE, FOREIGN UNIT MOVING ALONG ROUTE WITH TSR MOVEMENT BLOCKS ROUTE',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(2,'Army',39,'Move',29,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B2d','TEST CASE, FOREIGN UNIT ENTERING THE ROUTE BLOCKS ROUTE WITH STANDOFF',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(2,'Army',36,'Move',29,NULL,'No','Hold','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B2e','TEST CASE, FOREIGN UNIT LEAVING THE ROUTE DOES NOT BLOCK THE ROUTE',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(2,'Army',29,'Move',36,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B3a','TEST CASE, A MOVE CHAIN BLOCKS THE ROUTE',
		array(
			array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes'),
			array(7,'Army',36,'Move',39,NULL,'No','Hold','Yes'),
			array(2,'Army',39,'Move',6,NULL,'No','Success','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B3b','TEST CASE, A BLOCKED MOVE CHAIN STILL BLOCKS THE ROUTE',
		array(
			array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes'),
			array(7,'Army',36,'Move',39,NULL,'No','Hold','Yes'),
			array(2,'Army',39,'Move',6,NULL,'No','Hold','Yes'),
			array(6,'Army',6,'Hold',NULL,NULL,'No','Success','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B3c','TEST CASE, A BLOCKED MOVE CHAIN WITH RETURNING RUSSIAN STILL BLOCKS THE ROUTE',
		array(
			array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes'),
			array(7,'Army',36,'Move',39,NULL,'No','Hold','Yes'),
			array(6,'Army',39,'Move',6,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Hold',NULL,NULL,'No','Success','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B3d','TEST CASE, A BLOCKED MOVE CHAIN WITH ENTERING RUSSIAN DOES NOT BLOCK THE ROUTE',
		array(
			array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes'),
			array(6,'Army',36,'Move',39,NULL,'No','Hold','Yes'),
			array(2,'Army',39,'Move',6,NULL,'No','Hold','Yes'),
			array(7,'Army',6,'Hold',NULL,NULL,'No','Success','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B4a','TEST CASE, A STANDOFF ON ROUTE DOES NOT BLOCK ROUTE',
		array(
			array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes'),
			array(7,'Army',36,'Move',39,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Move',39,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B4b','TEST CASE, A STANDOFF ON ROUTE WITH RUSSIAN DOES NOT BLOCK ROUTE',
		array(
			array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes'),
			array(6,'Army',36,'Move',39,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Move',39,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B5a','TEST CASE, A STANDOFF ON ROUTE WITH RETURNING UNIT AGAINST DIRECTION',
		array(
			array(6,'Army',28,'Move',30,NULL,'Yes','Success','Yes'),
			array(7,'Army',29,'Move',39,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Move',39,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B5b','TEST CASE, A STANDOFF ON ROUTE WITH RETURNING RUSSIAN UNIT AGAINST DIRECTION',
		array(
			array(6,'Army',28,'Move',30,NULL,'Yes','Success','Yes'),
			array(6,'Army',29,'Move',39,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Move',39,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B6a','TEST CASE, A STANDOFF ON ROUTE WITH RETURNING UNIT WITH DIRECTION',
		array(
			array(6,'Army',28,'Move',30,NULL,'Yes','Success','Yes'),
			array(7,'Army',36,'Move',29,NULL,'No','Hold','Yes'),
			array(2,'Army',39,'Move',29,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B6b','TEST CASE, A STANDOFF ON ROUTE WITH RETURNING RUSSIAN UNIT WITH DIRECTION',
		array(
			array(6,'Army',28,'Move',30,NULL,'Yes','Success','Yes'),
			array(7,'Army',36,'Move',29,NULL,'No','Hold','Yes'),
			array(1,'Army',39,'Move',29,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B7a','TEST CASE, A STANDOFF ON ROUTE WITH BOTH UNITS ON ROUTE',
		array(
			array(6,'Army',28,'Move',30,NULL,'Yes','Success','Yes'),
			array(7,'Army',29,'Move',39,NULL,'No','Hold','Yes'),
			array(2,'Army',40,'Move',39,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B7b','TEST CASE, A STANDOFF ON ROUTE WITH BOTH RUSSIAN UNITS ON ROUTE',
		array(
			array(6,'Army',28,'Move',30,NULL,'Yes','Success','Yes'),
			array(6,'Army',29,'Move',39,NULL,'No','Hold','Yes'),
			array(6,'Army',40,'Move',39,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B8a','TEST CASE, A BOUNCE BY A HOLDING RUSSIAN UNIT DOES NOT BLOCK THE ROUTE',
		array(
			array(6,'Army',40,'Move',28,NULL,'Yes','Success','Yes'),
			array(6,'Army',29,'Hold',NULL,NULL,'No','Success','Yes'),
			array(1,'Army',36,'Move',29,NULL,'No','Hold','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B8b','TEST CASE, A DISLODGED HOLDING RUSSIAN UNIT RESULTS IN BLOCKADE',
		array(
			array(6,'Army',40,'Move',28,NULL,'Yes','Success','Yes'),
			array(6,'Army',29,'Hold',NULL,NULL,'No','Dislodged','Yes'),
			array(1,'Army',36,'Move',29,NULL,'No','Success','Yes'),
			array(1,'Army',34,'Support move',29,36,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B9a','TEST CASE, A TEMPORARY LEFT TERRITORY DOES NOT BLOCK THE ROUTE',
		array(
			array(6,'Army',40,'Move',28,NULL,'Yes','Success','Yes'),
			array(2,'Army',29,'Move',36,NULL,'No','Hold','Yes'),
			array(1,'Army',36,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B9b','TEST CASE, MULTIPLE TEMPORARY LEFT TERRITORY DO NOT BLOCK THE ROUTE',
		array(
			array(6,'Army',40,'Move',28,NULL,'Yes','Success','Yes'),
			array(2,'Army',29,'Move',36,NULL,'No','Hold','Yes'),
			array(1,'Army',39,'Move',36,NULL,'No','Hold','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.B10','TEST CASE, A DISLODGED UNIT STILL BLOCKS THE ROUTE',
		array(
			array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes'),
			array(6,'Army',36,'Support move',39,6,'No','Success','Yes'),
			array(6,'Army',6,'Move',39,NULL,'No','Success','Yes'),
			array(2,'Army',39,'Hold',NULL,NULL,'No','Dislodged','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B11','TEST CASE, JUST TRYING TO STANDOFF DOES NOT UNBLOCK ROUTE',
		array(
			array(6,'Army',35,'Move',30,NULL,'Yes','Success','Yes'),
			array(6,'Army',36,'Move',39,6,'No','Hold','Yes'),
			array(2,'Army',6,'Move',39,NULL,'No','Success','Yes'),
			array(2,'Army',51,'Support move',39,6,'No','Success','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.B12','TEST CASE, A SUPPORT CANNOT CIRCUMVENT BLOCKADES',
		array(
			array(6,'Army',30,'Move',28,NULL,'Yes','Success','Yes'),
			array(2,'Army',29,'Support hold',36,NULL,'No','Success','Yes'),
			array(2,'Army',36,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes'),
			array(6,'Army',34,'Support move',28,30,'Yes','Success','Yes')
		)
	),

	// FINAL TERRITORY (INCL SUPPORT)
	array('TSR.C1','TEST CASE, NO ATTACK IN TARGET TERRITORY, NO CUT OF SUPPORT',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(2,'Army',40,'Support hold',6,NULL,'No','Success','Yes'),
			array(2,'Army',6,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('TSR.C2','TEST CASE, NO DISLODGEMENT IN TARGET TERRITORY',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(5,'Army',30,'Support move',40,29,'No','Success','Yes'),
			array(2,'Army',40,'Support hold',6,NULL,'No','Success','Yes'),
			array(2,'Army',6,'Hold',NULL,NULL,'No','Success','Yes'),
		)
	),
	array('TSR.C3a','TEST CASE, NO DISLODGMENT EVEN IF TEMPORARY LEFT',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(5,'Army',30,'Support move',40,29,'No','Success','Yes'),
			array(2,'Army',40,'Move',6,NULL,'No','Hold','Yes'),
			array(6,'Army',6,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('TSR.C3b','TEST CASE, NO DISLODGMENT EVEN IF TEMPORARY LEFT (SPECIAL CASE: MOVE AGAINST SUPPORTER)',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(2,'Army',40,'Move',6,NULL,'No','Hold','Yes'),
			array(6,'Army',6,'Support move',40,29,'No','Success','Yes')
		)
	),
	array('TSR.C4','TEST CASE, SUCCESS IF LEFT',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(2,'Army',40,'Move',6,NULL,'No','Success','Yes'),
			array(6,'Army',36,'Move',39,NULL,'No','Success','Yes')
		)
	),
	array('TSR.C5','TEST CASE, STANDOFF IN TARGET TERRITORY',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Hold','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Hold','Yes'),
			array(6,'Army',36,'Move',39,NULL,'No','Success','Yes')
		)
	),
	array('TSR.C6','TEST CASE, BOUNCE AGAINST SUPPORTED MOVE',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Success','Yes'),
			array(5,'Army',46,'Support move',40,30,'No','Success','Yes')
		)
	),
	array('TSR.C7','TEST CASE, BOUNCE WITH SUPPORTED MOVE',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Support move',40,29,'No','Success','Yes')
		)
	),
	array('TSR.C8','TEST CASE, STANDOFF DUE TO SUPPORTS',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Hold','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Support move',40,29,'No','Success','Yes'),
			array(5,'Army',46,'Support move',40,30,'No','Success','Yes'),
			array(6,'Army',36,'Move',39,NULL,'No','Success','Yes')
		)
	),
	array('TSR.C9a','TEST CASE, DEFEND WITH SUPPORTED TSR',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Support move',40,29,'No','Success','Yes'),
			array(5,'Army',46,'Support move',40,30,'No','Success','Yes'),
			array(1,'Army',40,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('TSR.C9b','TEST CASE, DEFEND WITH SUPPORTED TSR (SPECIAL CASE: DEFENDED TRY TO CUT THEIR DEFENSE)',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Support move',40,29,'No','Success','Yes'),
			array(5,'Army',46,'Support move',40,30,'No','Success','Yes'),
			array(1,'Army',40,'Move',6,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.C9c','TEST CASE, DEFEND WITH SUPPORTED TSR (SPECIAL CASE: DEFENDED BLOCK THEIR DEFENSE)',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Hold','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Success','Yes'),
			array(2,'Army',6,'Support move',40,29,'No','Success','Yes'),
			array(5,'Army',46,'Support move',40,30,'No','Success','Yes'),
			array(1,'Army',40,'Move',39,NULL,'No','Dislodged','Yes')
		)
	),
	array('TSR.C10','TEST CASE, TRIPLE STANDOFF',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Hold','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Hold','Yes'),
			array(5,'Army',46,'Move',40,NULL,'No','Hold','Yes'),
			array(6,'Army',36,'Move',39,NULL,'No','Success','Yes')
		)
	),
	array('TSR.C11a','TEST CASE, TRIPLE STANDOFF WITH SUPPORT FOR TSR',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Success','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Hold','Yes'),
			array(5,'Army',46,'Move',40,NULL,'No','Hold','Yes'),
			array(6,'Army',39,'Support move',40,29,'No','Success','Yes')
		)
	),
	array('TSR.C11b','TEST CASE, TRIPLE STANDOFF WITH SUPPORT FOR TSR AND OTHER',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Hold','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Army',46,'Move',40,NULL,'No','Hold','Yes'),
			array(6,'Army',39,'Support move',40,29,'No','Success','Yes'),
			array(2,'Army',6,'Support move',40,46,'No','Success','Yes'),
		)
	),
	array('TSR.C11c','TEST CASE, TRIPLE STANDOFF WITH SUPPORT OTHERS',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Hold','Yes'),
			array(5,'Army',30,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Army',46,'Move',40,NULL,'No','Hold','Yes'),
			array(6,'Army',39,'Support move',40,30,'No','Success','Yes'),
			array(2,'Army',6,'Support move',40,46,'No','Success','Yes'),
		)
	),
	array('TSR.C12a','TEST CASE, NO STANDOFF WITH FOREIGN UNIT MOVING ALONG TSR',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Hold','Yes'),
			array(5,'Army',39,'Move',40,NULL,'No','Success','Yes')
		)
	),
	array('TSR.C12b','TEST CASE, STANDOFF WITH RUSSIAN UNIT MOVING ALONG TSR',
		array(
			array(6,'Army',29,'Move',40,NULL,'Yes','Hold','Yes'),
			array(6,'Army',39,'Move',40,NULL,'No','Hold','Yes')
		)
	),
	
	// RETURNING TSR TERRITORIES
	array('TSR.D1','TEST CASE, TSR BLOCKED ON ROUTE STOPS BEFORE BLOCKADE',
		array(
			array(6,'Army',28,'Move',39,NULL,'Yes','Success','Yes'),
			array(2,'Army',29,'Hold',NULL,NULL,'No','Success','Yes'),
			array(2,'Army',6,'Move',39,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.D2','TEST CASE, TSR BLOCKED IN TARGET STOPS BEFORE TARGET',
		array(
			array(6,'Army',28,'Move',29,NULL,'Yes','Success','Yes'),
			array(2,'Army',29,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.D3','TEST CASE, TSR IMMEDIATELY BLOCKED HOLDS',
		array(
			array(6,'Army',28,'Move',39,NULL,'Yes','Hold','Yes'),
			array(1,'Army',35,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	array('TSR.D4a','TEST CASE, A HOLDING RUSSIAN UNIT BLOCKS RETURNING TSR',
		array(
			array(6,'Army',28,'Move',39,NULL,'Yes','Success','Yes'),
			array(6,'Army',29,'Hold',NULL,NULL,'No','Success','Yes'),
			array(2,'Army',39,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.D4b','TEST CASE, A MOVING WITH RUSSIAN UNIT BLOCKS RETURNING TSR',
		array(
			array(6,'Army',28,'Move',39,NULL,'Yes','Success','Yes'),
			array(6,'Army',35,'Move',29,NULL,'No','Success','Yes'),
			array(2,'Army',39,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.D4c','TEST CASE, A MOVING AGAINST RUSSIAN DOES NOT BLOCK RETURNING TSR',
		array(
			array(6,'Army',28,'Move',39,NULL,'Yes','Success','Yes'),
			array(6,'Army',29,'Move',35,NULL,'No','Success','Yes'),
			array(2,'Army',39,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.D4d','TEST CASE, AN ENTERING RUSSIAN UNIT BLOCKS RETURNING TSR',
		array(
			array(6,'Army',28,'Move',39,NULL,'Yes','Success','Yes'),
			array(6,'Army',36,'Move',29,NULL,'No','Success','Yes'),
			array(2,'Army',39,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.D5','TEST CASE, A FOREIGN UNIT OF TEMPORARY LEFT TERRITORY BLOCKS RETURNING TSR',
		array(
			array(6,'Army',28,'Move',39,NULL,'Yes','Success','Yes'),
			array(2,'Army',29,'Move',36,NULL,'No','Hold','Yes'),
			array(2,'Army',36,'Move',39,NULL,'No','Hold','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
	array('TSR.D6','TEST CASE, A STANDOFF BLOCKS RETURNING TSR',
		array(
			array(6,'Army',35,'Move',39,NULL,'Yes','Hold','Yes'),
			array(2,'Army',39,'Hold',NULL,NULL,'No','Success','Yes'),
			array(1,'Army',36,'Move',29,NULL,'No','Hold','Yes'),
			array(1,'Army',34,'Move',29,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.D7','TEST CASE, MULTIPLE BLOCKS',
		array(
			array(6,'Army',28,'Move',30,NULL,'Yes','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Hold','Yes'),
			array(6,'Army',46,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Move',40,NULL,'No','Hold','Yes'),
			array(6,'Army',39,'Support hold',29,NULL,'No','Success','Yes'),
			array(6,'Army',29,'Support hold',39,NULL,'No','Success','Yes'),
			array(1,'Army',35,'Move',34,NULL,'No','Hold','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes'),
			array(7,'Army',34,'Hold',NULL,NULL,'No','Success','Yes')
		)
	),
	
	// HEAD TO HEAD (INCL SUPPORT)
	array('TSR.E1','TEST CASE, A HEAD-TO-HEAD WITH RUSSIAN UNIT NOT BLOCKS ROUTE',
		array(
			array(6,'Army',29,'Move',30,NULL,'Yes','Success','Yes'),
			array(6,'Army',39,'Move',29,NULL,'No','Success','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.E2','TEST CASE, A HEAD-TO-HEAD WITH RUSSIAN UNIT CAN SWAP UNITS',
		array(
			array(6,'Army',29,'Move',39,NULL,'Yes','Success','Yes'),
			array(6,'Army',39,'Move',29,NULL,'No','Success','Yes')
		)
	),
	array('TSR.E2a','TEST CASE, BLOCKED SWAPPING',
		array(
			array(6,'Army',29,'Move',39,NULL,'Yes','Hold','Yes'),
			array(6,'Army',39,'Move',29,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Move',39,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.E3','TEST CASE, A HEAD-TO-HEAD WITH FOREIGN UNIT DOES BLOCK ROUTE',
		array(
			array(6,'Army',29,'Move',30,NULL,'Yes','Hold','Yes'),
			array(2,'Army',39,'Move',29,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.E4','TEST CASE, A HEAD-TO-HEAD WITH FOREIGN UNIT CANNOT SWAP UNITS',
		array(
			array(6,'Army',29,'Move',39,NULL,'Yes','Hold','Yes'),
			array(2,'Army',39,'Move',29,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.E5','TEST CASE, A SUPPORTED HEAD-TO-HEAD WITH FOREIGN UNIT DOES BLOCK ROUTE',
		array(
			array(6,'Army',29,'Move',30,NULL,'Yes','Hold','Yes'),
			array(6,'Army',46,'Support move',30,29,'No','Success','Yes'),
			array(2,'Army',39,'Move',29,NULL,'No','Hold','Yes'),
			array(5,'Fleet',103,'Move',30,NULL,'No','Success','Yes')
		)
	),
	array('TSR.E6','TEST CASE, A SUPPORTED HEAD-TO-HEAD WITH FOREIGN UNIT MAY NOT DISLODGE',
		array(
			array(6,'Army',29,'Move',39,NULL,'Yes','Hold','Yes'),
			array(6,'Army',36,'Support move',39,29,'No','Success','Yes'),
			array(2,'Army',39,'Move',29,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.E7','TEST CASE, A SUPPORTED HEAD-TO-HEAD WITH FOREIGN UNIT MAY NOT DEFEND THE FOREIGN UNIT',
		array(
			array(6,'Army',29,'Move',39,NULL,'Yes','Hold','Yes'),
			array(6,'Army',36,'Support move',39,29,'No','Success','Yes'),
			array(2,'Army',39,'Move',29,NULL,'No','Dislodged','Yes'),		
			array(5,'Army',40,'Move',39,NULL,'No','Success','Yes'),
			array(5,'Army',6,'Support move',39,40,'No','Success','Yes')
		)
	),
	array('TSR.E8','TEST CASE, A HEAD-TO-HEAD WITH SUPPORTED FOREIGN UNIT DOES RESULT IN DISLODGEMENT',
		array(
			array(6,'Army',29,'Move',39,NULL,'Yes','Dislodged','Yes'),
			array(2,'Army',36,'Support move',29,39,'No','Success','Yes'),
			array(2,'Army',39,'Move',29,NULL,'No','Success','Yes')
		)
	),
	array('TSR.E9','TEST CASE, A SUPPORTED HEAD-TO-HEAD WITH SUPPORTED FOREIGN UNIT DOES RESULT IN DISLODGEMENT',
		array(
			array(6,'Army',29,'Move',39,NULL,'Yes','Dislodged','Yes'),
			array(6,'Army',51,'Support move',39,29,'No','Success','Yes'),
			array(2,'Army',36,'Support move',29,39,'No','Success','Yes'),
			array(2,'Army',39,'Move',29,NULL,'No','Success','Yes'),
			array(5,'Army',40,'Move',39,NULL,'No','Success','Yes')
		)
	),
	
	// DISLODGEMENT CASES OF UNSUCCESSFULL TSRS
	array('TSR.F1','TEST CASE, A BLOCKED AND RETURNING UNIT HOLDS TERRITORY',
		array(
			array(6,'Army',28,'Move',39,NULL,'Yes','Hold','Yes'),
			array(1,'Army',35,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Hold','Yes')
		)
	),
	array('TSR.F2','TEST CASE, A BLOCKED AND RETURNING UNIT CAN BE DISLODGED',
		array(
			array(6,'Army',28,'Move',39,NULL,'Yes','Dislodged','Yes'),
			array(1,'Army',35,'Hold',NULL,NULL,'No','Success','Yes'),
			array(7,'Army',34,'Support move',28,27,'No','Success','Yes'),
			array(7,'Army',27,'Move',28,NULL,'No','Success','Yes')
		)
	),
);

foreach($testCaseData as $testCase)
{
	list($name, $description, $orders) = $testCase;
	new TestCase($name, $description, $orders, $this->id);
}

unset($testCaseData);

TestCase::runSQL($this->id);
