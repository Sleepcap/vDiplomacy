<?php

/*
  Copyright (C) 2011 Oliver Auth / 2014 Tobias Florin

  This file is part of the Duo variant for webDiplomacy

  The Duo variant for webDiplomacy is free software: you can redistribute
  it and/or modify it under the terms of the GNU Affero General Public License
  as published by the Free Software Foundation, either version 3 of the License,
  or (at your option) any later version.

  The Duo variant for webDiplomacy is distributed in the hope that it will
  be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
  See the GNU General Public License for more details.

  You should have received a copy of the GNU Affero General Public License
  along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

 */

defined('IN_CODE') or die('This script can not be run by itself.');

class TransSib_userOrderDiplomacy extends userOrderDiplomacy {

	private $transSibTerritories;
	static $isTransSibOrderSet = false;

	function __construct($orderID, $gameID, $countryID) {
		parent::__construct($orderID, $gameID, $countryID);

		$this->transSibTerritories = ColonialVariant::$transSibTerritories;
	}

	//moves via railroad by Russian player should be accepted as well
	protected function moveToTerrCheck() {
		$result = parent::moveToTerrCheck();

		if ($this->countryID == "6" && in_array($this->Unit->terrID, $this->transSibTerritories) && in_array($this->toTerrID, $this->transSibTerritories) && !TransSib_userOrderDiplomacy::$isTransSibOrderSet && $this->Unit->type == "Army") {

			if ($this->viaConvoy == 'Yes') {
				TransSib_userOrderDiplomacy::$isTransSibOrderSet = true;
			}

			$this->viaConvoyOptions[] = 'Yes';
			$result = true;
		}

		return $result;
	}

	//support moves for Russian units via TSR
	protected function supportMoveFromTerrCheck() {
		global $DB;

		$result = parent::supportMoveFromTerrCheck();

		//get fromTerr Unit to check if it is a Russian Unit
		$row = $DB->sql_hash("SELECT id FROM wD_Units WHERE terrID = " . $this->fromTerrID . " AND gameID = " . $this->gameID);
		if (!$row || count($row) != 1)
			return $result;
		else
			$this->fromTerritory->Unit = libVariant::$Variant->Unit($row['id']);

		if ($this->fromTerritory->Unit->countryID == "6" && in_array($this->fromTerrID, $this->transSibTerritories) && in_array($this->toTerrID, $this->transSibTerritories) && $this->Unit->terrID != $this->fromTerrID && $this->Unit->terrID != $this->toTerrID)
			$result = true;

		return $result;
	}

}

class Suez_userOrderDiplomacy extends TransSib_userOrderDiplomacy {
	/*
	 * allow move-orders via Suez Canal
	 */

	protected function moveToTerrCheck() {
		$result = parent::moveToTerrCheck();

		if (($this->Unit->terrID == '99' && $this->toTerrID == '101') || ($this->Unit->terrID == '101' && $this->toTerrID == '99')) {
			$this->viaConvoyOptions[] = "No";
			$result = true;
		}

		return $result;
	}

	/*
	 * allow supportMove-orders for suez-moves
	 */

	protected function supportMoveFromTerrCheck() {
		return parent::supportMoveFromTerrCheck() || (($this->toTerrID == '99' && $this->fromTerrID == '101') || ($this->toTerrID == '101' && $this->fromTerrID == '99')) && $this->Unit->terrID != $this->fromTerrID;
	}

	/*
	 * enable the viaSuez-order
	 */

	protected function supportHoldToTerrCheck() {
		return parent::supportHoldToTerrCheck() || ($this->Unit->terrID == '126'	//supporting unit is suez unit
				&& ($this->toTerrID == '99' || $this->toTerrID == '101')); //supported territories are MedSea and RedSea (which are allowed to use the Suez Canal)
	}

}

class ColonialVariant_userOrderDiplomacy extends Suez_userOrderDiplomacy {}
