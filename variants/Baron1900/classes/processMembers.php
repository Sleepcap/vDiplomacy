<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class Baron1900Variant_processMembers extends processMembers {

    function countUnitsSCs()
    {
            parent::countUnitsSCs();

    }

    function checkForWinner()
    {

        $countPlaying = count($this->ByStatus['Playing']);

        // Is the game over? Is there only 1/0 players left?
        if ( $countPlaying < 2 )
        {

            if ( $countPlaying == 1 )
            {
                foreach($this->ByStatus['Playing'] as $Member);
                return $Member;
            }
            elseif ( $countPlaying == 0 )
            {
                $this->Game->setAbandoned(); // Throws exception
            }
        }
        else
        {
            // If more than one is left over see if any of them have supplyCenterTarget or more supply centers
            foreach($this->ByStatus['Playing'] as $Member)
            {
				if ( $this->Game->targetSCs > 0 )
				{
					if ( $this->Game->targetSCs <= $Member->supplyCenterNo )
					{
						return $this->check_for_Winner_that_works_with_same_SC_count();
					}
				}
				elseif ( $this->Game->Variant->supplyCenterTarget <= $Member->supplyCenterNo )
				{
					return $this->check_for_Winner_that_works_with_same_SC_count();
				}
				// The players which have lost go into 'Survived' mode when the other player is set to Won
            }
        }
		
		// Do an additional check if we reached maxTurns:
		if (($this->Game->turn == ($this->Game->maxTurns - 1)) && ($this->Game->maxTurns > 0))
			return $this->check_for_Winner_that_works_with_same_SC_count();

        return false;
    }
    
    function check_for_Winner_that_works_with_same_SC_count()
    {
        $winners=array();
        $maxSC=0;
        foreach($this->ByStatus['Playing'] as $Member)
        {
            if ($Member->supplyCenterNo > $maxSC)
            {
                $maxSC=$Member->supplyCenterNo;
                $winners=array();
            }
            if ((count($winners)==0) or ($Member->supplyCenterNo == $maxSC))
            {
                $winners[]=$Member->countryID;
            }
                
        }
        if (count($winners) > 1)
        {
            return false;
        }
        
        return $this->ByCountryID[$winners[0]];
    }
    
    
    /**
    * Returns true if any member has a different number of supply centers than units. Used to
    * detect whether a builds phase is needed.
    *
    * @return boolean
    */
    function checkForUnitSCDifference()
    {
        foreach($this->ByID as $Member)
        {
            if( $Member->countryID == 6 && $this->checkForRussianEMR())
            {
                if ($Member->supplyCenterNo + 1 != $Member->unitNo)
                {
                    return true;
                }
            }
            else if( $Member->supplyCenterNo != $Member->unitNo )
            {
                return true;
            }
        }

        return false;
    }
    
    function checkForRussianEMR()
    {
        global $DB;
        list($howManyRussianHome)=$DB->sql_row(
                "SELECT count(*)
                FROM wD_TerrStatus
                WHERE gameID=".$this->Game->id." AND 
                terrID IN(86,91,92,97) AND 
                countryID=6");
        
        if ($howManyRussianHome > 0 && $howManyRussianHome < 4)
        {
            return true;
        }
        return false;
    }
}

?>
