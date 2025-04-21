<?php

/*
 * PROBLEM: cached results for all numeric calls: _... -> problems with testresults ... :(
 */

class adjMoveTSR extends adjMove {

	public $moveTSRhelper;
	public $TSRmove;		//object which stores important information about TSR move

	public function setUnits(array $units) {
		if (isset($this->moveTSRhelper))
			$this->moveTSRhelper = $units[$this->moveTSRhelper];

		parent::setUnits($units);
	}

	protected function _holdStrength() {
		try {
			if ($this->success()/* || $this->helperSuccess() */) {
				$min = 0;
				$max = 0;
			} else {
				$min = 1;
				$max = 1;
			}
		} catch (adjParadoxException $p) {
			$min = 0;
			$max = 1;
		}

		$holdStrength = array('min' => $min, 'max' => $max);
		if (isset($p))
			$holdStrength['paradox'] = $p;

		return $holdStrength;
	}

	protected function _preventStrength() {
		try {
			// No path; we can't have any effect on the target
			if (!$this->path())
				return array('max' => 0, 'min' => 0);
		} catch (adjParadoxException $p) {
			// We might end up path-less
			$min = 0;
		}

		// The max/min array of the units which are supporting my move

		$prevent = $this->supportStrength();

		if (isset($min))
			$prevent['min'] = $min;

		if (isset($prevent['paradox']) and isset($p))
			$prevent['paradox']->downSizeTo($p);
		elseif (isset($p))
			$prevent['paradox'] = $p;

		return $prevent;
	}

	/* protected function _dislodged()
	  {
	  // This needs to be known first, so the paradox doesn't need to be handled
	  if ( $this->success()/* || $this->helperSuccess()*-/)
	  return false;
	  else
	  return parent::_dislodged();
	  } */

	protected function helperSuccess() {
		$current = $this;

		while (isset($current->moveTSRhelper)) {
			$current = $current->moveTSRhelper;

			if ($current->success())
				return true;
		}

		return false;
	}

	protected $successSetted = false;

	protected function setSuccess() {
		global $DB;

		if (!$this->successSetted) { //update territory and id
			$DB->sql_put("UPDATE wD_Orders
                                                        SET toTerrID = '" . $this->TSRmove->toTerrID . "'
                                                        WHERE id = '" . $this->id . "' AND gameID = " . $GLOBALS['GAMEID']);

			$this->successSetted = true;
		}
	}

	function paradoxForce($name, $value) {

		if ($name == 'success' && $value == true) {
			$this->setSuccess();
		}

		parent::paradoxForce($name, $value);
	}

	/*
	 * When the initial move is not successful but at least one helper is successful
	 * the unit is still moving and is leaving the territory. If this is the case
	 * the order is 'successful' so it is saved as 'success' and the unit does not
	 * block friendly units trying to enter the left territory.
	 * 
	 * realSuccess is directly used by this objects and instances of adjMoveTSRhelper only.
	 */

	protected function _success() {
		return $this->realSuccess() || $this->helperSuccess();
	}

	protected function _realSuccess() {
		try {
			/*
			 * Checking that our prevent strength is greater than 0 is a roundabout
			 * way of checking whether we have a path to the destination. If we don't
			 * our prevent strength is 0, and empty territories are considered to have
			 * a hold strength of 0.
			 * 
			 * In comparison to move prevent strength have to chosen, as a TSR 
			 * move always has attack strength 0 (no attacks allowed).
			 */
			if (!$this->compare('preventStrength', '>', 0))
				return false;
		} catch (adjParadoxException $p) {
			
		}

		if (isset($this->defender)) {
			try {
				//we have a defender but are not allowed to attack him
				if (!$this->defenderMoving())
					return false;
			} catch (adjParadoxException $pe) {
				if (isset($p))
					$p->downSizeTo($pe);
				else
					$pe = $pe;
			}
		}

		// I need to have more attack strength than each preventer
		foreach ($this->preventers as $preventer) {
			try {
				if (!$this->compare('preventStrength', '>', array($preventer, 'preventStrength')))
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
			$this->setSuccess();
			return true;
		}
	}

	protected function defenderMoving() {
		if (!isset($this->defender))
			return true; //no defender -> empty territory


		if ($this->defender instanceof adjMove and $this->defender->success())
			return true;
		else
			return false;
	}

	/*
	 * A TSR unit is not allowed to attack another territory.
	 */

	protected function _attackStrength() {
		return array('min' => 0, 'max' => 0);
	}

	/**
	 * This is not an official value, but a helper for the other numeric functions
	 * which perform similar tasks; counting all supporting units
	 *
	 * @param bool $checkCountryID Do we need to check the supporting countryID?
	 * @return int|array A max/min/paradox array, or an int
	 */
	protected function supportStrength($checkCountryID = false) {
		$min = 1;
		$max = 1;

		foreach ($this->supporters as $supporter) {
			/*
			 * If specified then countries are checked to ensure no-one can
			 * give attack support against their own countryID
			 */
			//if ( $checkCountryID and $this->defender->countryID == $supporter->countryID )
			//continue;

			try {
				if ($supporter->success()) {
					$min++;
					$max++;
				}
			} catch (adjParadoxException $pe) {
				$max++; // It is a possible supporter
				if (isset($p))
					$p->downSizeTo($pe);
				else
					$p = $pe;
			}
		}

		$support = array('min' => $min, 'max' => $max);
		if (isset($p))
			$support['paradox'] = $p;

		return $support;
	}

	/*
	 * Check if we could reach this territory, so if our helper, the TSRorder for the 
	 * previous territory is successful (if we have one) (and its previous order ...)
	 */

	protected function _path() {
		return (!isset($this->moveTSRhelper) || $this->moveTSRhelper->testSuccess());
	}

}

?>
