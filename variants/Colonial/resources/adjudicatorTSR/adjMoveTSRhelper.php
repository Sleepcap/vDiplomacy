<?php

/*
 * TSRhelper are needed to check, if a unit could reach its final territory.
 * 
 * target territory is reachable, if every territory on the path before the final 
 * territory is unoccupied / gets unoccupied.
 * Territories are also considered as unoccupied if to equal forces 
 * (not including the helper) are bouncing at a territory and
 * no one is able to occupy the territory.
 * Russian units are ignored (countryID == 6)!
 */

/*
 * paradoxes are not checked for the test-numeric calls!
 */

class adjMoveTSRhelper extends adjMoveTSR {

	public $nextTSRmove;

	public function setUnits(array $units) {
		$this->nextTSRmove = $units[$this->nextTSRmove];

		parent::setUnits($units);
	}

	/*
	 * Calculates the prevent strength of a helper.
	 * 
	 * There is only one case when a helper should have a prevent strenght:
	 * The helper describes the link of the move chain which is blocked.
	 * 
	 * This is the case if the helper has a failed testSuccess(), but a valid 
	 * path (so previous helpers were (test)successful.
	 */
	protected function _preventStrength() {
		try {
			if (!$this->path() || $this->testSuccess())
				return array('min' => 0, 'max' => 0); 
		} catch (adjParadoxException $p) {
			//we might be able to ignore the territory (are not allowed to prevent as we can slip through)
			$min = 0;
		}

		$prevent = parent::_preventStrength();

		if (isset($min))
			$prevent['min'] = $min;

		if (isset($prevent['paradox']) and isset($p))
			$prevent['paradox']->downSizeTo($p);
		elseif (isset($p))
			$prevent['paradox'] = $p;

		return $prevent;
	}

	/*
	 * TSRhelpers cannot be supported as they are only helpers ...
	 */

	protected function supportStrength($checkCountryID = false) {
		return array('min' => 1, 'max' => 1); //no support for helpers
	}

	protected function setSuccess() {
		global $DB;

		if (!$this->successSetted /* && !$this->testSuccess */) {
			if (!$this->nextTSRmove->realSuccess()) { //update territory and id
				$TSRpath = $this->TSRmove->getTSRpath();
				$terrID = $TSRpath[$this->id * -1 - 1]['terrID'];


				//$this->id = $this->TSRmove->id;

				$DB->sql_put("UPDATE wD_Orders o 
                                                        INNER JOIN wD_Moves m ON (m.orderID = o.id AND m.gameID = o.gameID)
                                                        SET o.toTerrID = '" . $terrID . "'
                                                        WHERE m.id='" . $this->TSRmove->id . "' AND o.gameID = " . $GLOBALS['GAMEID']);

				//$DB->sql_put("UPDATE wD_Moves SET success = 'Yes' WHERE id = '".$this->TSRmove->id."' AND gameID = ".$GLOBALS['GAMEID']); //ajdMoveTSR->_success()

				$this->TSRmove->reachedTerritory = $terrID;

				$this->successSetted = true;
			}//else 
			//$this->id = 0;
		}
	}

	protected function getFinalTSRmove() {
		$unit = $this;

		while (isset($unit->nextTSRmove)) {
			$unit = $unit->nextTSRmove;
		}

		return $unit;
	}

	/*
	 * helper orders are renamed when they are checked for success.
	 */

	protected function _success() {
		if ($this->successForStopping() || ($this->nextTSRmove->realSuccess() && $this->testSuccess()) ) {
			$this->setSuccess();
			return true;
		} else {
			return false;
		}
	}

	/*
	 * Needed for parent ajdMoveTSR but not the helper-unit's class. Here only 
	 * the real successes are relevant so they are tested directly via _success()
	 */

	protected function _realSuccess() {
		return $this->success();
	}

	/*
	 * Test if the helper can be used for an interrupted TSR move. If there is any
	 * unit or any preventer on the target of this helper, than this will not be
	 * successfull. 
	 */
	protected function successForStopping() {

		try {
			/*
			 * Checking that our attack strength is greater than 0 is a roundabout
			 * way of checking whether we have a path to the destination. If we don't
			 * our attack strength is 0, and empty territories are considered to have
			 * a hold strength of 0.
			 */
			/*
			 * TSRhelper do not have any attackStrength so we are checking the path instead
			 */
			if (!$this->path())
				return false;
		} catch (adjParadoxException $p) {
			
		}

		if (isset($this->defender)) { 
			try {
				if (!$this->defenderMoving())
					return false;
			} catch (adjParadoxException $pe) {
				if (isset($p))
					$p->downSizeTo($pe);
				else
					$p = $pe;
			}
		}

		//helpers are never supported, so if there is a preventer and the unit cannot ignore it because of another preventer and a free territory behind, there won't be a success!
		foreach ($this->preventers as $preventer) {
			return false;
		}

		if (isset($p))
			throw $p;
		else {
			return true;
		}
	}

	/*
	 * Check if helperMove would be successfull if following TSRmove would be successful as well
	 */

	protected function _testSuccess() {
		try {
			/*
			 * Checking that our attack strength is greater than 0 is a roundabout
			 * way of checking whether we have a path to the destination. If we don't
			 * our attack strength is 0, and empty territories are considered to have
			 * a hold strength of 0.
			 */

			/*
			 * TSRhelper do not have any attackStrength so we are checking the path instead
			 */
			if (!$this->path())
				return false;
		} catch (adjParadoxException $p) {
			
		}

		if (isset($this->defender)) { //&& ($this->defender->countryID != 6  || (!$this->testSuccess && !$this->nextTSRmove->success())))
			try {
				//see http://www.diplom.org/Zine/S1997M/Schwarz/Paradox.html //triesMove not for  moves along the TSR!
				//if($this->testSuccess || $this->nextTSRmove->success())
				if ($this->defender->countryID != 6 && !$this->defenderTriesMoveOut())
					return false;
				//elseif(!$this->defenderMoving())
				//return false;
			} catch (adjParadoxException $pe) {
				if (isset($p))
					$p->downSizeTo($pe);
				else
					$p = $pe;
			}
		}

		//$maxPreventers = array();
		//helpers are never supported, so if there is a preventer and the unit cannot ignore it because of another preventer and a free territory behind, there won't be a success!
		foreach ($this->preventers as $preventer) {
			try {
				if (($preventer->countryID != 6 /* || !($this->testSuccess || $this->nextTSRmove->success()) */) && $this->preventerTestSuccess($preventer)) //no standoff -> territory occupied -> TSR has to stop
					return false;
			} catch (adjParadoxException $pe) {
				if (isset($p))
					$p->downSizeTo($pe);
				else
					$p = $pe;
			}
		}

		if (isset($p))
			throw $p;
		else {
			//$this->setSuccess();
			return true;
		}
	}

	/*
	 * preventerTestSuccess does not test the real success of the preventer but only
	 * its possibility to block the TSR. It does indeed block the TSR if:
	 * - it is not bounced out by a third unit moving in
	 * - it is not bounced out by a holding unit
	 * - it is not bounced out by a head-to-head move
	 * 
	 * If it is bounced out by a returning unit it does however block the TSR
	 * (this compensates the disability of a leaving unit to block the TSR).
	 */

	protected function preventerTestSuccess($preventer) {
		try {
			/*
			 * Checking that our attack strength is greater than 0 is a roundabout
			 * way of checking whether we have a path to the destination. If we don't
			 * our attack strength is 0, and empty territories are considered to have
			 * a hold strength of 0.
			 */
			if (!$preventer->compare('attackStrength', '>', 0))
				return false;
		} catch (adjParadoxException $p) {
			
		}

		/*
		 * If there is a defender: Check if it is moving out (and not a head-to-head move).
		 * If true, it can be ignored. If it is a head-to-head move -> check if it is moving
		 * against preventer, so the preventer cannot block the TSR if not successfull)
		 */
		if (isset($preventer->defender)) {
			try {
				// We're moving head to head
				if ($preventer instanceof adjHeadToHeadMove) {
					// We're in a head to head and I don't have more attack strength than the defender
					if (!$preventer->compare('attackStrength', '>', array($preventer->defender, 'defendStrength')))
						return false;
				}
				else {
					// I do not have more attack strength than the defender has hold strength
					if (!($preventer->defender instanceof adjMove) && !$preventer->compare('attackStrength', '>', array($preventer->defender, 'holdStrength')))
						return false;
				}
			} catch (adjParadoxException $pe) {
				if (isset($p))
					$p->downSizeTo($pe);
				else
					$p = $pe;
			}
		}

		// I need to have more attack strength than each preventer
		foreach ($preventer->preventers as $preventerPreventer) {

			//skip us, as we only test the preventer for bounces with others
			if ($preventerPreventer == $this) {
				continue;
			}

			try {
				if (!$preventer->compare('attackStrength', '>', array($preventerPreventer, 'preventStrength')))
					return false;
			} catch (adjParadoxException $pe) {
				if (isset($p))
					$p->downSizeTo($pe);
				else
					$p = $pe;
			}
		}

		if (isset($p))
			throw $p;
		else
			return true;
	}

	/*
	 * Check if the defender tries to move out of the TSR -> no block of TSR.
	 * 
	 * If defender tries to move along the TSR in a head to head move
	 * he will block the TSR (even if he is bounced in his target territory 
	 * where his move would be ignored by the TSR).
	 */
	protected function defenderTriesMoveOut() {
		if (!isset($this->defender))
			return true; //no defender -> empty territory


			
		//we have defender who tries to move [and is not directly moving along on the TSR]
		if ($this->defender instanceof adjMove && !(in_array($this->moveTSRhelper, $this->defender->preventers) || in_array($this->nextTSRmove, $this->defender->preventers))) 
			return true;
		else
			return false;
	}

	/* protected $testSuccess = false; //to test if successful in the case, the next order of TSR would be successfull
	  protected function _theoreticalSuccess(){
	  $this->testSuccess = true;
	  $return = $this->testSuccess();
	  $this->testSuccess = false;
	  return $return;
	  } */
}

?>
