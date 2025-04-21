<?php

class DutchRevoltVariant_processGame extends processGame {
	public function __construct($id)
	{
		parent::__construct($id);
	}

	protected function changePhase() {
		if( $this->phase == 'Pre-game' )
		{
			// Builds first after the game starts
			$this->setPhase('Builds');

			// This gives the map some color to start with
			$this->archiveTerrStatus();

			return false;
		}
		elseif( $this->phase == 'Builds' && $this->turn==0 )
		{
			// The first Spring builds just finished, make sure we don't go to the next turn

			$this->phase='Pre-game'; // This prevents a turn being added on in setPhase, keeping it in Spring, 1901
			// (It won't activate twice because the next time it won't go into a Builds phase in Spring)

			$this->setPhase('Diplomacy'); // Diplomacy, Spring 1901, and from then on like nothing was different

			$this->archiveTerrStatus();
			return false;
		}
		else
			return parent::changePhase(); // Except those two phases above behave normally
	}

	protected function updateOwners()
	{
		
		parent::updateOwners();
		
		global $DB;

		// Check SCs of Spain...
		list($SpainSCs) = $DB->sql_row('
			SELECT count(*) FROM wD_TerrStatus ts
			INNER JOIN wD_Territories t ON ( t.id = ts.terrID AND t.supply="Yes" )
			WHERE ts.gameID = '.$this->id.' AND ts.countryID = 3 AND t.mapID=32 AND ts.terrID != 9');
		
		// If the country of Spain has no SCs besides Spain, it becomes "neutral"
		if ($SpainSCs == 0)	
			$DB->sql_put('UPDATE wD_TerrStatus SET countryID = 0 WHERE gameID = '.$this->id.' AND terrID = 9');
		else
			$DB->sql_put('UPDATE wD_TerrStatus SET countryID = 3 WHERE gameID = '.$this->id.' AND terrID = 9');
		
	}
	
}