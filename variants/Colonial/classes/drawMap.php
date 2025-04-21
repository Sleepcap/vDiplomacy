<?php

/*
  Copyright (C) 2010 Oliver Auth / 2014 Tobias Florin

  This file is part of the Colonial variant for webDiplomacy

  The Colonial variant for webDiplomacy is free software: you can redistribute
  it and/or modify it under the terms of the GNU Affero General Public License
  as published by the Free Software Foundation, either version 3 of the License,
  or (at your option) any later version.

  The Colonial variant for webDiplomacy is distributed in the hope that it will
  be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
  See the GNU General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

 */

defined('IN_CODE') or die('This script can not be run by itself.');

class SuezCanal_drawMap extends drawMap {
	/*
	 * Do not draw the neutral Suez unit
	 */

	public function addUnit($terrID, $unitType) {
		if ($terrID == '126')
			return;

		parent::addUnit($terrID, $unitType);
	}

	public function countryFlag($terrID, $countryID) {
		if ($terrID == '126')
			return;

		parent::countryFlag($terrID, $countryID);
	}

	/*
	 * Do not draw the support order from the Suez unit (the order that symbolizes a premission to use the SC)
	 * Draw small arrow to symbolize the direction of the permission (down for Med, up for Red Sea)
	 */

	public function drawSupportHold($fromTerrID, $toTerrID, $success) {
		if ($fromTerrID == '126')
			return $this->drawSuezPermission($toTerrID);

		parent::drawSupportHold($fromTerrID, $toTerrID, $success);
	}

	public function drawSuezPermission($toTerrID) {
		//the arrow should point into the direction of the targetting territory
		if ($toTerrID == '99')
			$toTerrID = '101';
		elseif ($toTerrID == '101')
			$toTerrID = '99';

		list($fromX, $fromY) = $this->territoryPositions[126]; //SuezCanal
		list($toX, $toY) = $this->territoryPositions[$toTerrID];

		$this->drawOrderArrow(array($fromX, $fromY), array($fromX + ($toX - $fromX) / 20, $fromY + ($toY - $fromY) / 20), 'SuezPermission');
	}

	/*
	 * Do an arrow 'along the Suez Canal' for SuezMoves to symbolize that the unit used
	 * or tried to use the Suez Canal
	 */

	public function drawMove($fromTerrID, $toTerrID, $success) {
		if (($fromTerrID == '99' && $toTerrID == '101') || ($fromTerrID == '101' && $toTerrID == '99'))
			return $this->drawMoveSuez($fromTerrID, $toTerrID, $success);

		parent::drawMove($fromTerrID, $toTerrID, $success);
	}

	public function drawMoveSuez($fromTerrID, $toTerrID, $success) {
		list($fromX, $fromY) = $this->territoryPositions[$fromTerrID];
		list($toX, $toY) = $this->territoryPositions[$toTerrID];
		list($SuezX, $SuezY) = $this->territoryPositions[126];

		$this->drawOrderArrow(array($fromX, $fromY), array($SuezX, $SuezY), 'MoveSuez');
		$this->drawOrderArrow(array($SuezX, $SuezY), array($toX, $toY), 'Move');

		if (!$success) {
			$this->drawFailure(array($fromX, $fromY), array($SuezX, $SuezY));
			$this->drawFailure(array($SuezX, $SuezY), array($toX, $toY));
		}
	}

	public function drawMoveGrey($fromTerrID, $toTerrID, $success) {
		if (($fromTerrID == '99' && $toTerrID == '101') || ($fromTerrID == '101' && $toTerrID == '99'))
			return $this->drawMoveGreySuez($fromTerrID, $toTerrID, $success);

		parent::drawMoveGrey($fromTerrID, $toTerrID, $success);
	}

	public function drawMoveGreySuez($fromTerrID, $toTerrID, $success) {
		list($fromX, $fromY) = $this->territoryPositions[$fromTerrID];
		list($toX, $toY) = $this->territoryPositions[$toTerrID];
		list($SuezX, $SuezY) = $this->territoryPositions[126];

		$this->drawOrderArrow(array($fromX, $fromY), array($SuezX, $SuezY), 'MoveGreySuez');
		$this->drawOrderArrow(array($SuezX, $SuezY), array($toX, $toY), 'MoveGrey');

		if (!$success) {
			$this->drawFailure(array($fromX, $fromY), array($SuezX, $SuezY));
			$this->drawFailure(array($SuezX, $SuezY), array($toX, $toY));
		}
	}

	public function drawSupportMove($terrID, $fromTerrID, $toTerrID, $success) {
		if (($fromTerrID == '99' && $toTerrID == '101') || ($fromTerrID == '101' && $toTerrID == '99'))
			return parent::drawSupportMove($terrID, '126', $toTerrID, $success);

		parent::drawSupportMove($terrID, $fromTerrID, $toTerrID, $success);
	}

	public function __construct($smallmap) {

		$this->orderArrows['SuezPermission'] = array('color' => array(4, 113, 160),
			'thickness' => array(2, 4),
			'headAngle' => 7,
			'headStart' => 0.1,
			'headLength' => array(12, 30),
			'border' => array(0, 0)
		);
		$this->orderArrows['MoveSuez'] = array('color' => array(196, 32, 0), //0, 153, 2),//
			'thickness' => array(2, 4),
			'headAngle' => 7,
			'headStart' => .1,
			'headLength' => array(0, 0),
			'border' => array(0, 0)
		);
		$this->orderArrows['MoveGreySuez'] = array('color' => array(100, 100, 100),
			'thickness' => array(2, 4),
			'headAngle' => 7,
			'headStart' => .1,
			'headLength' => array(0, 0),
			'border' => array(0, 0)
		);

		parent::__construct($smallmap);
	}

}

class TSR_drawMap extends SuezCanal_drawMap {
	
	public function drawMove($fromTerrID, $toTerrID, $success) {
		if($this->TSRexists && $this->TSRfromTerrID == $fromTerrID && $this->TSRtoTerrID == $toTerrID)
			$this->drawMoveTSR($success);
		else
			parent::drawMove($fromTerrID, $toTerrID, $success);
	}
	
	public function drawMoveGrey($fromTerrID, $toTerrID, $success) {
		if($this->TSRexists && $this->TSRfromTerrID == $fromTerrID && $this->TSRtoTerrID == $toTerrID)
			$this->drawMoveTSR($success);
		else
			parent::drawMoveGrey($fromTerrID, $toTerrID, $success);
	}
	
	public function drawSupportMove($terrID, $fromTerrID, $toTerrID, $success) {
		if($this->TSRexists && $this->TSRfromTerrID == $fromTerrID && $this->TSRinitTargetID == $toTerrID)
			$this->drawSupportMoveTSR ($terrID, $success);
		else
			parent::drawSupportMove($terrID, $fromTerrID, $toTerrID, $success);
	}

	/*
	 * A helper function which extracts all TSR territories between $from and $to
	 * (in correct ordering beginning with $from).
	 */
	protected function getTSRroute($from, $to) {
		// get indices of TSR territory array as indicator of the direction the TSR moves
		$fromIdx = array_search($from, ColonialVariant::$transSibTerritories);
		$toIdx = array_search($to, ColonialVariant::$transSibTerritories);
		
		$eastwards = ($toIdx > $fromIdx);
		
		$route = array($from);
		
		$currentIdx = $fromIdx;
		
		while($currentIdx != $toIdx){
			if($eastwards)
				$currentIdx++;
			else
				$currentIdx--;
			
			$route[] = ColonialVariant::$transSibTerritories[$currentIdx];
		}
		
		return $route;
	}
	
	protected function getTSRdrawingCoordinates($coordinates){
		$y0 = ($this->smallmap)?7:15;
		
		return array($coordinates[0],$y0);
	}
	
	protected function drawTSRarrow($from, $to, $failed, $initial, $final){
		$fromTSR = $this->getTSRdrawingCoordinates($from);
		$toTSR = $this->getTSRdrawingCoordinates($to);
		
		$this->drawOrderArrow($from, $fromTSR, ($initial)?'MoveTSRwithoutArrow':'MoveTSRwithoutArrowSmall');
		$this->drawOrderArrow($fromTSR, $toTSR, ($failed)?'MoveTSRsmall':'MoveTSR');
		$this->drawOrderArrow($toTSR, $to, ($final)?'MoveTSRwithoutArrow':'MoveTSRwithoutArrowSmall');
		
		if($failed)
			$this->drawFailure($fromTSR, $toTSR);
	}


	public function drawMoveTSR($success) {
		$reachedStop = ($this->TSRtoTerrID == $this->TSRinitTargetID);
		
		$route = $this->getTSRroute($this->TSRfromTerrID, $this->TSRinitTargetID);
		
		$routeA = array_pop($route);
		while(count($route) > 0){
			$routeB = $routeA;
			$routeA = array_pop($route);
			
			$this->drawTSRarrow(
					$this->territoryPositions[$routeA], 
					$this->territoryPositions[$routeB], 
					(!$success || !$reachedStop), 
					$success && ($routeA == $this->TSRfromTerrID),
					$success && ($routeB == $this->TSRtoTerrID));
			
			if($routeA == $this->TSRtoTerrID)
				$reachedStop = true;
		}
		
	}
	
	public function drawSupportMoveTSR($terrID, $success){
		if ( $this->smallmap and !$success ) return;
		
		// just draw support for last segment where it has value
		$route = $this->getTSRroute($this->TSRfromTerrID, $this->TSRinitTargetID);
		$last = array_pop($route);
		$slast = array_pop($route);

		// Our toX and toY are 1/3 of the way between the two territories
		list($fromX, $fromY) = $this->getTSRdrawingCoordinates($this->territoryPositions[$slast]);
		list($toX, $toY) = $this->getTSRdrawingCoordinates($this->territoryPositions[$last]);

		$toX -= ( $toX - $fromX ) / 3;
		$toY -= ( $toY - $fromY ) / 3;

		list($fromX, $fromY) = $this->territoryPositions[$terrID];

		$this->drawOrderArrow(array($fromX, $fromY), array($toX, $toY), 'Support move');

		if ( !$success ) $this->drawFailure(array($fromX, $fromY), array($toX, $toY));
	}

	/*
	 * Load additional arrow types and detect TSR order
	 */
	public function __construct($smallmap) {
		$this->orderArrows['MoveTSR'] = array('color' => array(255, 156, 0),
			'thickness' => array(2, 4),
			'headAngle' => 7,
			'headStart' => .4,
			'headLength' => array(12, 30),
			'border' => array(0, 0)
		);
		
		$this->orderArrows['MoveTSRsmall'] = array('color' => array(255, 156, 0),
			'thickness' => array(1, 2),
			'headAngle' => 7,
			'headStart' => .4,
			'headLength' => array(12, 30),
			'border' => array(0, 0)
		);
		
		$this->orderArrows['MoveTSRwithoutArrow'] = array('color' => array(255, 156, 0),
			'thickness' => array(2, 4),
			'headAngle' => 7,
			'headStart' => .1,
			'headLength' => array(0, 0),
			'border' => array(0, 0)
		);
		
		$this->orderArrows['MoveTSRwithoutArrowSmall'] = array('color' => array(255, 156, 0),
			'thickness' => array(1, 2),
			'headAngle' => 7,
			'headStart' => .1,
			'headLength' => array(0, 0),
			'border' => array(0, 0)
		);
		
		$this->detectTSRorder();

		parent::__construct($smallmap);
	}
	
	public $TSRexists = false;
	public $TSRfromTerrID;
	public $TSRtoTerrID;
	public $TSRinitTargetID;
	public function detectTSRorder(){
		global $DB, $Game;
		
		if( !isset($Game) ) return;
		
		if(PREVIEW) {

			$sql = "
				SELECT u.terrID, o.toTerrID
				FROM wD_Orders o
				LEFT JOIN wD_Units u ON (u.id = o.unitID)
				WHERE u.terrID IN (".implode(',',ColonialVariant::$transSibTerritories).")
					AND o.toTerrID IN (".implode(',',ColonialVariant::$transSibTerritories).")
					AND o.type = 'Move'
					AND o.viaConvoy = 'Yes'
					AND o.gameID = " . $Game->id;
			
			$tabl = $DB->sql_tabl($sql);
					
			list($fromTerrID, $toTerrID) = $DB->tabl_row($tabl);
			
			if( isset($fromTerrID) ){
				$this->TSRfromTerrID = $fromTerrID;
				$this->TSRtoTerrID = $toTerrID;
				$this->TSRinitTargetID = $toTerrID;

				$this->TSRexists = true;
			}
			
		} else {
					
			// -- code from map.php to determine turn number
			// Determine the turn number:
			if ( $Game->phase == 'Diplomacy' ) 
				$latestTurn = $Game->turn-1;
			else 
				$latestTurn = $Game->turn;

			$turn = $latestTurn;

			$givenTurn = (int) $_REQUEST['turn'];
			if ( $givenTurn >= -1 && $givenTurn <= $latestTurn )
				$turn = $givenTurn;
			// --

			$sql = " 
				SELECT terrID, toTerrID, fromTerrID
				FROM wD_MovesArchive
				WHERE 
					terrID IN (".implode(',',ColonialVariant::$transSibTerritories).")
					AND toTerrID IN (".implode(',',ColonialVariant::$transSibTerritories).")
					AND type = 'Move'
					AND fromTerrID IS NOT NULL
					AND gameID = " . $Game->id . " AND turn = " . $turn;

			$tabl = $DB->sql_tabl($sql);

			list($fromTerrID, $toTerrID, $initialTargetID) = $DB->tabl_row($tabl);

			
			if ( isset($initialTargetID) ) { 

				$this->TSRfromTerrID = $fromTerrID;
				$this->TSRtoTerrID = $toTerrID;
				$this->TSRinitTargetID = $initialTargetID;

				$this->TSRexists = true;
			}
		}
	}

}

class ColonialVariant_drawMap extends TSR_drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), /* Neutral */
		1 => array(239, 196, 228), /* Britain */
		2 => array(196, 143, 133), /* China   */
		3 => array(121, 175, 198), /* France  */
		4 => array(160, 138, 117), /* Holland */
		5 => array(164, 196, 153), /* Japan   */
		6 => array(168, 126, 159), /* Russia  */
		7 => array(234, 234, 175), /* Turkey  */
		8 => array(0, 0, 0) /* Neutral Suez (invisible) */
	);

	protected function resources() {
		if ($this->smallmap) {
			return array(
				'army' => l_s('contrib/smallarmy.png'),
				'fleet' => l_s('contrib/smallfleet.png'),
				'names' => l_s('variants/Colonial/resources/smallmapNames.png'),
				'standoff' => l_s('images/icons/cross.png'),
				'map' => l_s('variants/Colonial/resources/smallmap.png'),
			);
		} else {
			return array(
				'army' => l_s('contrib/army.png'),
				'fleet' => l_s('contrib/fleet.png'),
				'names' => l_s('variants/Colonial/resources/mapNames.png'),
				'standoff' => l_s('images/icons/cross.png'),
				'map' => l_s('variants/Colonial/resources/map.png'),
			);
		}
	}

	/**
	 * An array containing the neutral support-center icon image resource, and its width and height.
	 * $image['image'],['width'],['height']
	 * @var array
	 */
	protected $sc = array();

	/**
	 * An array containing the information if one of the first 9 territories 
	 * still has a neutral support-center (So we might not need to draw a flag)
	 */
	protected $nsc = array();

	protected function loadImages() {
		parent::loadImages();

		if ($this->smallmap)
			$this->sc = $this->loadImage(l_s('variants/Colonial/resources/sc_small.png'));
		else
			$this->sc = $this->loadImage(l_s('variants/Colonial/resources/sc_large.png'));
	}

	protected function setTransparancies() {
		parent::setTransparancies();
		$this->setTransparancy($this->sc);
	}

	/* There are 8 territories on the map that belong to a country but have a supply-center that is considered
	 * * "neutral"
	 * * They are set to owner "Neutral" in the installation-file, so we need to check if they are still
	 * * "neutal" and paint the territory in the color of the country they "should" belong to.
	 * * after that draw the "Neutral-SC-overloay" on the map.
	 */

	public function ColorTerritory($terrID, $countryID) {
		if ($terrID == 1 && $countryID == 0 && $this->smallmap) {
			$sx = 527;
			$sy = 50;
			$countryID = 6;
		} elseif ($terrID == 1 && $countryID == 0 && !$this->smallmap) {
			$sx = 1325;
			$sy = 133;
			$countryID = 6;
		} elseif ($terrID == 2 && $countryID == 0 && $this->smallmap) {
			$sx = 221;
			$sy = 101;
			$countryID = 6;
		} elseif ($terrID == 2 && $countryID == 0 && !$this->smallmap) {
			$sx = 561;
			$sy = 260;
			$countryID = 6;
		} elseif ($terrID == 3 && $countryID == 0 && $this->smallmap) {
			$sx = 338;
			$sy = 214;
			$countryID = 1;
		} elseif ($terrID == 3 && $countryID == 0 && !$this->smallmap) {
			$sx = 854;
			$sy = 548;
			$countryID = 1;
		} elseif ($terrID == 4 && $countryID == 0 && $this->smallmap) {
			$sx = 268;
			$sy = 149;
			$countryID = 1;
		} elseif ($terrID == 4 && $countryID == 0 && !$this->smallmap) {
			$sx = 679;
			$sy = 381;
			$countryID = 1;
		} elseif ($terrID == 5 && $countryID == 0 && $this->smallmap) {
			$sx = 301;
			$sy = 293;
			$countryID = 1;
		} elseif ($terrID == 5 && $countryID == 0 && !$this->smallmap) {
			$sx = 749;
			$sy = 751;
			$countryID = 1;
		} elseif ($terrID == 6 && $countryID == 0 && $this->smallmap) {
			$sx = 347;
			$sy = 73;
			$countryID = 2;
		} elseif ($terrID == 6 && $countryID == 0 && !$this->smallmap) {
			$sx = 876;
			$sy = 191;
			$countryID = 2;
		} elseif ($terrID == 7 && $countryID == 0 && $this->smallmap) {
			$sx = 289;
			$sy = 132;
			$countryID = 2;
		} elseif ($terrID == 7 && $countryID == 0 && !$this->smallmap) {
			$sx = 726;
			$sy = 336;
			$countryID = 2;
		} elseif ($terrID == 8 && $countryID == 0 && $this->smallmap) {
			$sx = 414;
			$sy = 172;
			$countryID = 2;
		} elseif ($terrID == 8 && $countryID == 0 && !$this->smallmap) {
			$sx = 1040;
			$sy = 440;
			$countryID = 2;
		} elseif ($terrID == 9 && $countryID == 2 && $this->smallmap) {
			$sx = 446;
			$sy = 230;
		} elseif ($terrID == 9 && $countryID == 2 && !$this->smallmap) {
			$sx = 1133;
			$sy = 588;
		}

		parent::ColorTerritory($terrID, $countryID);
		$this->nsc[$terrID] = 0;

		if (isset($sx)) {
			$this->putImage($this->sc, $sx, $sy);
			$this->nsc[$terrID] = 1;
		}
	}

	/* No need to draw the country flags for "neural-SC-territories if they get occupied by 
	 * * the country they should belong to
	 */

	public function countryFlag($terrID, $countryID) {
		if (($this->nsc[1] == 1) && ($terrID == 1) && ( $countryID == 6))
			return;
		if (($this->nsc[2] == 1) && ($terrID == 2) && ( $countryID == 6))
			return;
		if (($this->nsc[3] == 1) && ($terrID == 3) && ( $countryID == 1))
			return;
		if (($this->nsc[4] == 1) && ($terrID == 4) && ( $countryID == 1))
			return;
		if (($this->nsc[5] == 1) && ($terrID == 5) && ( $countryID == 1))
			return;
		if (($this->nsc[6] == 1) && ($terrID == 6) && ( $countryID == 2))
			return;
		if (($this->nsc[7] == 1) && ($terrID == 7) && ( $countryID == 2))
			return;
		if (($this->nsc[8] == 1) && ($terrID == 8) && ( $countryID == 2))
			return;

		parent::countryFlag($terrID, $countryID);
	}

}

?>
