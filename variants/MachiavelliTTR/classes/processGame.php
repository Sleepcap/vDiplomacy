<?php

// Neutral units -> needs extend processGame directly or the code will bug for some admin-tools.
class MachiavelliTTRVariant_processGame extends processGame
{
	function process()
	{
		global $DB;
		parent::process();
		
		// If only the "neutral player has to do retreats process again.
		if ($this->phase == 'Retreats')
		{	
			list($count) = $DB->sql_row("SELECT COUNT(*)
				FROM wD_Members 
				WHERE orderStatus != 'None' AND gameID = ".$this->id);
			if ($count == 0)
				parent::process();
		}	
	}
}
