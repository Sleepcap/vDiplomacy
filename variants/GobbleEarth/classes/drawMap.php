<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class GobbleEarthVariant_drawMap extends drawMap
{
	protected $countryColors = array(
		0  => array(226, 198, 158), // Neutral
		1  => array(255, 102, 102), // Austria
		2  => array(242, 176, 225), // England
		3  => array(108, 208, 208), // France
  		4  => array(127, 127, 127), // Germany
  		5  => array( 94, 232,  94), // Italy
  		6  => array(235, 235, 235), // Russia
		7  => array(218, 218,  68), // Turkey
  		8  => array(194, 102, 194), // China
  		9  => array(185, 185, 104), // Japan
  		10 => array( 83, 157,  83), // Brazil
  		11 => array(179, 102, 102), // Argentina
  		12 => array(255, 194, 102), // Colombia
  		13 => array(137, 118, 100), // Mexico
  		14 => array(102, 102, 255), // USA
	);

	// Always only load the largemap (as there is no smallmap)
	// Map is too big, so up the memory-limit
	public function __construct($smallmap)
	{
		parent::__construct(false);
		ini_set('memory_limit',"32M");
	}

	protected function resources() {
		return array(
			'map'=>'variants/GobbleEarth/resources/map.png',
			'army'=>'contrib/smallarmy.png',
			'fleet'=>'contrib/smallfleet.png',
			'names'=>'variants/GobbleEarth/resources/mapNames.png',
			'standoff'=>'images/icons/cross.png'
		);
	}
	
	// Wait build orders are saved as Disloged to avoid bigger problems with map.php
	public function drawDislodgedUnit($terrID)
	{
		if ($terrID != 0) parent::drawDislodgedUnit($terrID);
	}
	
	// Always use the small orderarrows...
	protected function loadOrderArrows()
	{
		$this->smallmap=true;
		parent::loadOrderArrows();
		$this->smallmap=false;
	}	
	
	// Always use the small standoff-Icons
	public function drawStandoff($terrName)
	{
		$this->smallmap=true;
		parent::drawStandoff($terrName);
		$this->smallmap=false;
	}

	// Do draw a cross for failed build-orders.
	public function addUnit($drawToTerrID, $unitType)
	{
		if ($unitType == "") 
			$this->drawStandoff($drawToTerrID);
		else
			parent::addUnit($drawToTerrID, $unitType);
	}

	private function WrapArrowsX($fromTerr, $toTerr, $terr=0) 
	{
		list($startX, $startY) = $this->territoryPositions[$fromTerr];
		list($endX  , $endY  ) = $this->territoryPositions[$toTerr];
		
		if (abs($startX-$endX) > $this->map['width'] * 1/2)
		{
			$leftX = ($startX<$endX?$startX:$endX);
			$leftY = ($startX<$endX?$startY:$endY);
			$rightX = ($startX>$endX?$startX:$endX);
			$rightY = ($startX>$endX?$startY:$endY);
			$drawToLeftX = 0;
			$drawToRightX = $this->map['width'];
			// Ratio of diff(left side and left x) and diff (right side and right x)
			$ratioLeft = $leftX / ($leftX + $drawToRightX - $rightX);
			$ratioRight = 1.0 - $ratioLeft;
			if ($leftY > $rightY) { // Downward slope
				$drawToLeftY = $leftY - (abs($leftY-$rightY) * $ratioLeft);
				$drawToRightY = $rightY + (abs($leftY-$rightY) * $ratioRight);
			} else { // Upward Slope
				$drawToLeftY = $leftY + (abs($leftY-$rightY) * $ratioLeft);
				$drawToRightY = $rightY - (abs($leftY-$rightY) * $ratioRight);
			}
			if ($startX == $leftX) {
				$this->territoryPositions['WarpFrom1']= array ($leftX       ,$leftY       );
				$this->territoryPositions['WarpTo1']  =	array ($drawToLeftX ,$drawToLeftY );
				$this->territoryPositions['WarpFrom2']=	array ($drawToRightX,$drawToRightY);
				$this->territoryPositions['WarpTo2']  =	array ($rightX      ,$rightY      );
			} else  {
				$this->territoryPositions['WarpFrom1']=	array ($drawToLeftX ,$drawToLeftY );
				$this->territoryPositions['WarpTo1']  =	array ($leftX       ,$leftY       );
				$this->territoryPositions['WarpFrom2']= array ($rightX      ,$rightY      );
				$this->territoryPositions['WarpTo2']  =	array ($drawToRightX,$drawToRightY);
			}
		} else {
			$this->territoryPositions['WarpFrom1'] = $this->territoryPositions[$fromTerr];
			$this->territoryPositions['WarpTo1']   = $this->territoryPositions[$toTerr];
			$this->territoryPositions['WarpFrom2'] = $this->territoryPositions[$fromTerr];
			$this->territoryPositions['WarpTo2']   = $this->territoryPositions[$toTerr];
		}
		// If I have a support-move or convoy
		if ($terr != 0)
		{
			// If I have two arrows check which one to point to:
			if ($this->territoryPositions['WarpFrom1'] != $this->territoryPositions['WarpFrom2'])
			{		
				list($unitX, $unitY) = $this->territoryPositions[$terr];
				$dist1 = abs($unitX - $leftX)  + abs($unitY - $leftY)  + abs($unitX - $drawToLeftX)  + abs($unitY - $drawToLeftY);
				$dist2 = abs($unitX - $rightX) + abs($unitY - $rightY) + abs($unitX - $drawToRightX) + abs($unitY - $drawToRightY);

				if ($dist1 < $dist2) {
					$this->territoryPositions['WarpFrom2'] = $this->territoryPositions['WarpFrom1'];
					$this->territoryPositions['WarpTo2']   = $this->territoryPositions['WarpTo1'];
				} else {
					$this->territoryPositions['WarpFrom1'] = $this->territoryPositions['WarpFrom2'];
					$this->territoryPositions['WarpTo1']   = $this->territoryPositions['WarpTo2'];
				}
				$this->territoryPositions['WarpTerr1'] = $this->territoryPositions[$terr];
				$this->territoryPositions['WarpTerr2'] = $this->territoryPositions[$terr];
			}
			// Maybe the Support/Convoy arrow needs to be split too...
			else
			{
				$this->territoryPositions['SupTo'][0] = $endX - ( $endX - $startX ) / 3;
				$this->territoryPositions['SupTo'][1] = $endY - ( $endY - $startY ) / 3;
				$this->WrapArrowsX($terr, 'SupTo');
				$this->territoryPositions['WarpTerr1'] = $this->territoryPositions['WarpFrom1'];
				$this->territoryPositions['WarpFrom1'] = $this->territoryPositions['WarpTo1'];
				$this->territoryPositions['WarpTo1']   = $this->territoryPositions['WarpTo1'];
				$this->territoryPositions['WarpTerr2'] = $this->territoryPositions['WarpFrom2'];
				$this->territoryPositions['WarpFrom2'] = $this->territoryPositions['WarpTo2'];
				$this->territoryPositions['WarpTo2']   = $this->territoryPositions['WarpTo2'];	
			}
		}
	}

	public function drawSupportMove($terr, $fromTerr, $toTerr, $success)
	{		
		$this->WrapArrowsX($fromTerr, $toTerr, $terr);
		parent::drawSupportMove('WarpTerr1', 'WarpFrom1', 'WarpTo1', $success);
		parent::drawSupportMove('WarpTerr2', 'WarpFrom2', 'WarpTo2', $success);
	}
	
	public function drawConvoy($terr, $fromTerr, $toTerr, $success)
	{		
		$this->WrapArrowsX($fromTerr, $toTerr, $terr);
		parent::drawConvoy('WarpTerr1', 'WarpFrom1', 'WarpTo1', $success);
		parent::drawConvoy('WarpTerr2', 'WarpFrom2', 'WarpTo2', $success);
	}

	public function drawMove($fromTerr, $toTerr, $success)
	{
		$this->WrapArrowsX($fromTerr, $toTerr);
		parent::drawMove('WarpFrom1','WarpTo1', $success);
		parent::drawMove('WarpFrom2','WarpTo2', $success);
	}
	
	public function drawRetreat($fromTerr, $toTerr, $success)
	{
		$this->WrapArrowsX($fromTerr, $toTerr);
		parent::drawRetreat('WarpFrom1','WarpTo1', $success);
		parent::drawRetreat('WarpFrom2','WarpTo2', $success);
	}

	public function drawSupportHold($fromTerr, $toTerr, $success)
	{
		$this->WrapArrowsX($fromTerr, $toTerr);			
		parent::drawSupportHold('WarpFrom1','WarpTo1', $success);
		parent::drawSupportHold('WarpFrom2','WarpTo2', $success);	
	}
	
}

?>