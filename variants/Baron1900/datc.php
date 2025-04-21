<?php
// This is the file that installs additional test cases for the 1900 variant
defined('IN_CODE') or die('This script can not be run by itself.');
require_once("variants/variantDATC.php");

TestCase::$TestCases = array();
$testCaseData = array(
	array('9.A','TEST CASE, CONVOY TO SELF ILLEGAL',
		array(
			array(2,'Fleet',25,'Convoy',25,41,'No','Hold','No'),
			array(6,'Fleet',40,'Hold',NULL,NULL,'No','Hold','Yes'),
			array(6,'Army',41,'Move',25,NULL,'Yes','Hold','Yes')
		)
	),
	array('9.B','TEST CASE, CONVOYING TO OWN AREA WITH A LOOP',
		array()
	),
	array('9.C','TEST CASE, CONVOY DISRUPTED BY ARMY',
		array(
			array(4,'Army',3,'Move',50,NULL,'Yes','Hold','Yes'),
			array(4,'Fleet',71,'Convoy',50,3,'No','Hold','Yes'),
			array(4,'Fleet',25,'Convoy',50,3,'No','Dislodged','Yes'),
			array(4,'Fleet',40,'Convoy',50,3,'No','Hold','Yes'),
			array(6,'Army',58,'Move',25,NULL,'No','Success','Yes'),
			array(6,'Army',41,'Support move',25,58,'No','Hold','Yes')
		)
	),
	array('9.D','TEST CASE, CONVOY DISRUPTED BY CONVOYING ARMY',
		array(
			array(4,'Army',58,'Move',41,NULL,'Yes','Hold','Yes'),
			array(4,'Fleet',25,'Convoy',41,58,'No','Dislodged','Yes'),
			array(6,'Army',3,'Move',25,NULL,'Yes','Success','Yes'),
			array(6,'Fleet',71,'Convoy',25,3,'No','Hold','Yes'),
			array(6,'Fleet',40,'Support move',25,3,'No','Hold','Yes')
		)
	),
	array('9.E','TEST CASE, TWO DISRUPTED CONVOYS PARADOX',
		array()
	),
	array('9.F','TEST CASE, DISRUPTED CONVOY SUPPORT PARADOX WITH NO RESOLUTION',
		array(
			array(4,'Army',41,'Move',58,NULL,'Yes','Hold','Yes'),
			array(4,'Fleet',25,'Convoy',58,41,'No','Hold','Yes'),
			array(4,'Army',50,'Support move',58,41,'No','Hold','Yes'),
			array(6,'Army',3,'Move',25,NULL,'Yes','Hold','Yes'),
			array(6,'Fleet',71,'Convoy',25,3,'No','Dislodged','Yes'),
			array(6,'Fleet',40,'Support move',25,3,'No','Hold','Yes'),
			array(2,'Fleet',59,'Support move',71,28,'No','Hold','Yes'),
			array(2,'Fleet',28,'Move',71,NULL,'No','Success','Yes')
		)
	),
	array('9.G','TEST CASE, DISRUPTED CONVOY SUPPORT PARADOX WITH TWO RESOLUTIONS',
		array(
			array(4,'Army',41,'Move',58,NULL,'Yes','Hold','Yes'),
			array(4,'Fleet',25,'Convoy',58,41,'No','Hold','Yes'),
			array(4,'Army',50,'Support move',58,41,'No','Hold','Yes'),
			array(4,'Fleet',28,'Move',71,NULL,'No','Hold','Yes'),
			array(4,'Fleet',67,'Support move',71,28,'No','Hold','Yes'),
			array(6,'Army',3,'Move',25,NULL,'Yes','Hold','Yes'),
			array(6,'Fleet',71,'Convoy',25,3,'No','Hold','Yes'),
			array(6,'Fleet',59,'Support hold',71,NULL,'No','Hold','Yes'),
			array(6,'Fleet',40,'Support move',25,3,'No','Hold','Yes')
		)
	),
	array('X.A0','TEST CASE, SUPPORT CAN NOT BE CUT BY SIMPLE HALF-STRENGTH BOUNCE',
		array(
			array(5,'Fleet',40,'Move',20,NULL,'No','Hold','Yes'),
			array(5,'Fleet',19,'Hold',NULL,NULL,'No','Dislodged','Yes'),
			array(7,'Fleet',20,'Support move',19,32,'No','Hold','Yes'),
			array(7,'Fleet',32,'Move',19,NULL,'No','Success','Yes')
		)
	),
	array('X.A1','TEST CASE, SUPPORT CAN NOT BE CUT BY SUPPORTED HALF-STRENGTH BOUNCE',
		array(
			array(5,'Fleet',40,'Move',20,NULL,'No','Hold','Yes'),
			array(5,'Army',29,'Support move',20,40,'No','Hold','Yes'),
			array(2,'Army',20,'Support move',47,6,'No','Hold','Yes'),
			array(2,'Fleet',19,'Support hold',20,NULL,'No','Hold','Yes'),
			array(2,'Army',6,'Move',47,NULL,'No','Success','Yes'),
			array(7,'Army',47,'Hold',NULL,NULL,'No','Dislodged','Yes')
		)
	),
	array('X.B','TEST CASE, SUPPORT CAN BE CUT BY DISLODGING MOVE OVER HALF-STRENGTH BORDER',
		array(
			array(5,'Fleet',40,'Move',20,NULL,'No','Success','Yes'),
			array(5,'Army',29,'Support move',20,40,'No','Hold','Yes'),
			array(2,'Army',20,'Support move',47,6,'No','Dislodged','Yes'),
			array(2,'Army',6,'Move',47,NULL,'No','Hold','Yes'),
			array(7,'Army',47,'Hold',NULL,NULL,'No','Hold','Yes')
		)
	),
	array('X.C','TEST CASE, MOVE OVER HALF-STRENGTH BORDER CAN NOT BOUNCE WITH NORMAL MOVE',
		array(
			array(5,'Fleet',40,'Move',20,NULL,'No','Hold','Yes'),
			array(7,'Army',47,'Move',20,NULL,'No','Success','Yes')
		)
	),
	array('X.D0','TEST CASE, TWO MOVES OVER HALF-STRENGTH BORDER CAN BOUNCE',
		array(
			array(2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Fleet',29,'Move',40,NULL,'No','Hold','Yes')
		)
	),
	array('X.D1','TEST CASE, TWO MOVES OVER HALF-STRENGTH BORDER AND SUPPORTED MOVE SUCCEEDS',
		array(
			array(2,'Fleet',20,'Move',40,NULL,'No','Success','Yes'),
			array(2,'Fleet',29,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Fleet',41,'Support move',40,20,'No','Hold','Yes')
		)
	),
	array('X.D2','TEST CASE, TWO SUPPORTED MOVES OVER HALF-STRENGTH BORDER CAN BOUNCE',
		array(
			array(2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Fleet',50,'Support move',40,29,'No','Hold','Yes'),
			array(2,'Fleet',29,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Fleet',41,'Support move',40,20,'No','Hold','Yes')
		)
	),
	array('X.E0','SUPPORT TO MOVE CAN NOT BE GIVEN OVER HALF-STRENGTH BORDER',
		array(
			array(2,'Fleet',40,'Support move',20,29,'No','Hold','No'),
			array(2,'Army',29,'Move',20,NULL,'No','Hold','Yes'),
			array(7,'Army',20,'Hold',NULL,NULL,'No','Hold','Yes')
		)
	),
	array('X.E1','SUPPORT TO HOLD CAN NOT BE GIVEN OVER HALF-STRENGTH BORDER',
		array(
			array(2,'Army',29,'Move',20,NULL,'No','Success','Yes'),
			array(2,'Army',47,'Support move',20,29,'No','Hold','Yes'),
			array(7,'Army',20,'Hold',NULL,NULL,'No','Dislodged','Yes'),
			array(7,'Fleet',40,'Support hold',20,NULL,'No','Hold','No')
		)
	),
	array('X.F','TEST CASE, SUPPORT PARADOX',
		array()
	),
	array('X.G','TEST CASE, ALMOST PARADOX',
		array()
	),
	array('X.H','TEST CASE, CIRCULAR MOVEMENT WITH HALF-STRENGTH BORDER',
		array(
			array(2,'Army',29,'Move',20,NULL,'No','Success','Yes'),
			array(2,'Fleet',20,'Move',40,NULL,'No','Success','Yes'),
			array(3,'Fleet',40,'Move',29,NULL,'No','Success','Yes')
		)
	),
	array('X.I','TEST CASE, CIRCULAR MOVEMENT WITH BOUNCE',
		array(
			array(2,'Army',29,'Move',20,NULL,'No','Hold','Yes'),
			array(2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
			array(3,'Fleet',40,'Move',29,NULL,'No','Hold','Yes'),
			array(7,'Army',47,'Move',20,NULL,'No','Hold','Yes')
		)
	),
	array('X.J0','TEST CASE, CIRCULAR MOVEMENT CAN NOT BE DISRUPTED BY HALF-STRENGTH BORDER',
		array(
			array(2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
			array(3,'Fleet',50,'Move',40,NULL,'No','Success','Yes'),
			array(3,'Fleet',40,'Move',61,NULL,'No','Success','Yes'),
			array(3,'Fleet',61,'Move',50,NULL,'No','Success','Yes')
		)
	),
	array('X.J1','TEST CASE, CIRCULAR MOVEMENT CAN BE DISRUPTED BY HALF-STRENGTH WITH SUPPORT',
		array(
			array(2,'Fleet',20,'Move',40,NULL,'No','Success','Yes'),
			array(2,'Fleet',25,'Support move',40,20,'No','Hold','Yes'),
			array(3,'Fleet',50,'Move',40,NULL,'No','Hold','Yes'),
			array(3,'Fleet',40,'Move',61,NULL,'No','Dislodged','Yes'),
			array(3,'Fleet',61,'Move',50,NULL,'No','Hold','Yes')
		)
	),
	array('X.J2','TEST CASE, CIRCULAR MOVEMENT WITH SUPPORT TRUMPS HALF-STRENGTH WITH SUPPORT',
		array(
			array(2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Fleet',25,'Support move',40,20,'No','Hold','Yes'),
			array(3,'Fleet',50,'Move',40,NULL,'No','Success','Yes'),
			array(3,'Fleet',40,'Move',61,NULL,'No','Success','Yes'),
			array(3,'Fleet',61,'Move',50,NULL,'No','Success','Yes'),
			array(3,'Fleet',75,'Support move',40,50,'No','Hold','Yes')
		)
	),
	array('X.K','TEST CASE, FULL-STRENGTH RETREAT IS HIGHER PRIORITY THAN HALF-STRENGTH RETREAT',
		array()
	),
	array('X.L','TEST CASE, HALF-STRENGTH BORDER IS PROPERTY OF BORDER NOT OF SECTOR',
		array(
			array(2,'Army',17,'Move',20,NULL,'No','Hold','Yes'),
			array(7,'Army',29,'Move',20,NULL,'No','Hold','Yes')
		)
	),
	array('X.M','TEST CASE, USING CONVOY INSTEAD OF HALF-STRENGTH BORDER',
		array()
	),
	array('X.N','TEST CASE, USING CONVOY INSTEAD OF HALF-STRENGTH BORDER CUTS SUPPORT',
		array()
	),
	array('X.O','TEST CASE, SUPPORT ON ATTACK ON OWN FLEET OVER HALF-STRENGTH BORDER DOES NOT CUT SUPPORT',
		array(
			array(2,'Fleet',20,'Move',40,NULL,'No','Hold','Yes'),
			array(2,'Fleet',25,'Hold',NULL,NULL,'No','Hold','Yes'),
			array(3,'Fleet',41,'Support move',40,20,'No','Hold','Yes'),
			array(3,'Fleet',40,'Support hold',25,NULL,'No','Hold','Yes'),
			array(5,'Fleet',71,'Move',25,NULL,'No','Hold','Yes'),
			array(5,'Fleet',59,'Support move',25,71,'No','Hold','Yes')
		)
	),
	array('X.P','TEST CASE, NO CONVOYS ACROSS TWO HALF-STRENGTH SEGMENTS',
		array(
			array(2,'Fleet',40,'Convoy',29,20,'No','Hold','Yes'),
			array(2,'Army',20,'Move',29,NULL,'Yes','Hold','Yes'),
			array(7,'Army',6,'Move',29,NULL,'No','Success','Yes')
		)
	),
	array('X.Q','TEST CASE, CONVOY IS HALF-STRENGTH WHEN LAST SEGMENT IS HALF-STRENGTH',
		array(
			array(2,'Fleet',40,'Convoy',20,33,'No','Hold','Yes'),
			array(2,'Army',33,'Move',20,NULL,'Yes','Hold','Yes'),
			array(7,'Army',47,'Move',20,NULL,'No','Success','Yes')
		)
	),
	array('X.R','TEST CASE, CONVOY IS FULL-STRENGTH WHEN ONE NON-LAST SEGMENT IS HALF-STRENGTH',
		array(
			array(2,'Fleet',40,'Convoy',50,20,'No','Hold','Yes'),
			array(2,'Army',20,'Move',50,NULL,'Yes','Hold','Yes'),
			array(3,'Army',58,'Move',50,NULL,'No','Hold','Yes')
		)
	),
	array('X.S0','TEST CASE, PREFER FULL-STRENGTH CONVOY TO HALF-STRENGTH CONVOY',
		array(
			array(2,'Army',64,'Move',20,NULL,'Yes','Hold','Yes'),
			array(7,'Army',47,'Move',20,NULL,'No','Hold','Yes'),
			array(5,'Fleet',19,'Convoy',20,64,'No','Hold','Yes'),
			array(5,'Fleet',32,'Convoy',20,64,'No','Hold','Yes'),
			array(5,'Fleet',67,'Convoy',20,64,'No','Hold','Yes'),
			array(5,'Fleet',71,'Convoy',20,64,'No','Hold','Yes'),
			array(5,'Fleet',25,'Convoy',20,64,'No','Hold','Yes'),
			array(5,'Fleet',40,'Convoy',20,64,'No','Hold','Yes')
		)
	),
	array('X.S1','TEST CASE, USE HALF-STRENGTH CONVOY WHEN FULL-STRENGTH CONVOY DISRUPTED',
		array(
			array(2,'Army',58,'Move',20,NULL,'Yes','Hold','Yes'),
			array(7,'Army',47,'Move',20,NULL,'No','Success','Yes'),
			array(5,'Fleet',19,'Convoy',20,58,'No','Hold','Yes'),
			array(5,'Fleet',32,'Convoy',20,58,'No','Dislodged','Yes'),
			array(5,'Fleet',67,'Convoy',20,58,'No','Hold','Yes'),
			array(5,'Fleet',71,'Convoy',20,58,'No','Hold','Yes'),
			array(5,'Fleet',40,'Convoy',20,58,'No','Hold','Yes'),
			array(5,'Fleet',25,'Convoy',20,58,'No','Hold','Yes'),
			array(1,'Fleet',2,'Move',32,NULL,'No','Success','Yes'),
			array(1,'Fleet',1,'Support move',32,2,'No','Hold','Yes')
		)
	),
	array('X.S2','TEST CASE, UNOPPOSED HALF-STRENGTH CONVOY WHEN FULL-STRENGTH CONVOY DISRUPTED',
		array(
			array(2,'Army',58,'Move',20,NULL,'Yes','Success','Yes'),
			array(5,'Fleet',19,'Convoy',20,58,'No','Hold','Yes'),
			array(5,'Fleet',32,'Convoy',20,58,'No','Dislodged','Yes'),
			array(5,'Fleet',67,'Convoy',20,58,'No','Hold','Yes'),
			array(5,'Fleet',71,'Convoy',20,58,'No','Hold','Yes'),
			array(5,'Fleet',40,'Convoy',20,58,'No','Hold','Yes'),
			array(5,'Fleet',25,'Convoy',20,58,'No','Hold','Yes'),
			array(1,'Fleet',2,'Move',32,NULL,'No','Success','Yes'),
			array(1,'Fleet',1,'Support move',32,2,'No','Hold','Yes')
		)
	)
);

foreach($testCaseData as $testCase)
{
	list($name, $description, $orders) = $testCase;
	new TestCase($name, $description, $orders, $this->id);
}

unset($testCaseData);

TestCase::runSQL($this->id);