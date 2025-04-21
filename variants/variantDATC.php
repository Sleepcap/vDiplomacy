<?php
/*
    Copyright (C) 2019 Tobias Florin

	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */

defined('IN_CODE') or die('This script can not be run by itself.');

/**
 * Helper code which datc.php scripts can use to help make variant specific datc 
 * test easier.
 * 
 * This code will not perform any datc tests. It just installs additional tests
 * in the database. Test should still be performed via datc.php in maintanence 
 * mode.
 *
 * See a datc.php file with map data for examples of use.
 */
class TestCase {
	/*
	 * Run the SQL to install the additional DATC test cases. 
	 */ 
	public static function runSQL($variantID){
		global $DB;
		
		$sql =  self::generateSQL((int) $variantID);
		foreach($sql as $statement)
			$DB->sql_put($statement);
	}

	/* 
	 * Generate the SQL to install the addiational DATC test cases.
	 * 
	 * To distinguish between test cases of different variants, each test case
	 * gets its own slot of 1000 testIDs in wD_DATC: [variantID]xxx.
	 * 
	 * In addition, each test name has the following form: 
	 * v[variantID].[userDefinedName]
	 */
	public static function generateSQL($variantID){
		$sql = array();
		
		// Wipe existing tests data
		$sql[] = 
			"DELETE FROM wD_DATC 
				WHERE testID >= ".($variantID*1000)." AND testID < ".(($variantID+1)*1000);
		$sql[] = 
			"DELETE FROM wD_DATCOrders 
				WHERE testID >= ".($variantID*1000)." AND testID < ".(($variantID+1)*1000);
		
		// Generate testCaseIDs
		$ids = array();
		$i = $variantID*1000;
		foreach(self::$TestCases as $testCase){
			$ids[$testCase->name] = $i;
			$i++;
		}
		
		if($i>=($variantID+1)*1000)
			throw new Exception("Too many test cases. Variant specific test cases"
					. "are limited to 1000");
		
		// Create DATC and DATC order entries
		$sqlDATCRows = array();
		$sqlDATCOrdersRows = array();
		foreach($ids as $name=>$id){
			$testCase = self::$TestCases[$name];
			$sqlDATCRows[] = "(".$id.","
					.$variantID.","
					."'v".$variantID.".".$testCase->name."',"
					."'".$testCase->description."',"
					."'".$testCase->status."')";
			
			foreach($testCase->orders as $order){
				$sqlDATCOrdersRows[] = "(".$id.","
						.$order->countryID.","
						."'".$order->unitType."',"
						.$order->terrID.","
						."'".$order->moveType."',"
						.(isset($order->toTerrID)?$order->toTerrID:"NULL").","
						.(isset($order->fromTerrID)?$order->fromTerrID:"NULL").","
						."'".$order->viaConvoy."',"
						."'".$order->criteria."',"
						."'".$order->legal."')";
			}
		}
		
		$sql[] = "INSERT INTO wD_DATC (testID,variantID,testName,testDesc,status)
			VALUES ".implode(',',$sqlDATCRows);
		$sql[] = "INSERT INTO wD_DATCOrders (testID,countryID,unitType,terrID,moveType,toTerrID,fromTerrID,viaConvoy,criteria,legal)
			VALUES ".implode(',',$sqlDATCOrdersRows);
		
		return $sql;
	}

	/*
	 * An array of the loaded test cases indexed by name (should be unique).
	 */
	public static $TestCases;
	
	/*
	 * Test case data. 
	 * 
	 * $orders contains an array TestCaseOrder elements
	 */
	public $name, $description, $orders;
	
	/*
	 * The status of the test case. This should stay 'NotPassed' as long as the
	 * test case is valid. For invalid test cases no orders should be prepared.
	 */
	public $status = 'NotPassed';
	
	public function __construct($name, $description, $orders, $variantID) {
		if( strlen($name) > 15 - strlen($variantID) - 2 )
			throw new Exception("Name of test case too long ('".$name."'). "
					. "Max. length with current variant id is ".(15 - strlen((String) $Variant->id) - 2));
		
		if( isset(self::$TestCases[$name]) )
			throw new Exception("Duplicate test case name '".$name."'.");
			
		$this->name = $name;
		$this->description = $description;
		$this->orders = TestCaseOrder::loadTestCaseOrders($orders);
		
		if(count($this->orders) == 0) 
			$this->status = "Invalid";
		
		self::$TestCases[$name] = $this;
	}
}

/*
 * A class the stores the information of each order of a test case.
 */
class TestCaseOrder {
	/*
	 * An array of the loaded orders for one test case indexed by terrID.
	 */
	protected static $TestCaseOrders;

	/*
	 * Create an array of Test case orders from an array of raw data orders.
	 */
	public static function loadTestCaseOrders($orders){
		if(!is_array($orders))
			throw new Exception("Invalid value for orders. Orders should be passed as array.");
		
		self::$TestCaseOrders = array();
		
		foreach($orders as $order){
			list($countryID, $unitType, $terrID, $moveType, $toTerrID, $fromTerrID, $viaConvoy, $criteria, $legal) = $order;
			new TestCaseOrder($countryID, $unitType, $terrID, $moveType, $toTerrID, $fromTerrID, $viaConvoy, $criteria, $legal);
		}
		
		return self::$TestCaseOrders;
	}
	
	/*
	 * Stored orderdata
	 */
	public $countryID, $unitType, $terrID, $moveType, $toTerrID, $fromTerrID, $viaConvoy;
	
	/*
	 * TestCase data for order:
	 * 
	 * Expected output of adjudicator
	 */
	public $criteria;
	
	/*
	 * Expected legality of order. 
	 */
	public $legal;
	
	public function __construct($countryID, $unitType, $terrID, $moveType, $toTerrID, $fromTerrID, $viaConvoy, $criteria, $legal) {
		if($unitType != "Fleet" && $unitType != "Army")
			throw new Exception("Invalid value for unitType '".$unitType."', should be Army/Fleet.");
		
		if(!in_array($moveType, array('Hold','Move','Support hold','Support move','Convoy','Retreat','Disband','Build Army','Build Fleet','Wait','Destroy')))
			throw new Exception ("Invalid value for moveType '".$moveType."'");
	
		if($viaConvoy != "Yes" && $viaConvoy != "No")
			throw new Exception("Invalid value for viaConvoy '".$viaConvoy."', should be Yes/No.");
		
		if(!in_array($criteria, array('Success', 'Dislodged', 'Hold')))
			throw new Exception ("Invalid value for critera '".$criteria."'");
		
		if($legal != "Yes" && $legal != "No")
			throw new Exception("Invalid value for legal '".$legal."', should be Yes/No.");
		
		if( isset(self::$TestCaseOrders[$terrID]) )
			throw new Exception("Duplicate order entry for unit in '".$terrID."' (terrID).");
		
		$this->countryID = $countryID;
		$this->unitType = $unitType;
		$this->terrID = $terrID;
		$this->moveType = $moveType;
		$this->toTerrID = $toTerrID;
		$this->fromTerrID = $fromTerrID;
		$this->viaConvoy = $viaConvoy;
		$this->criteria = $criteria;
		$this->legal = $legal;
		
		self::$TestCaseOrders[$terrID] = $this;
	}
}
