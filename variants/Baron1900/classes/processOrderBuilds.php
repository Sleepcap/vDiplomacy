<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class BuildBasedOnFlagsVariant_processOrderBuilds extends processOrderBuilds
{
    public function create()
    {
        global $DB, $Game;
        $type = "";
        $newOrders = array();
        foreach($Game->Members->ByID as $Member )
        {
            $difference = $this->differenceForOnePower($Member, $type);
            
            for( $i=0; $i < $difference; ++$i )
            {
                $newOrders[] = "(".$Game->id.", ".$Member->countryID.", '".$type."')";
            }
        }

        if ( count($newOrders) )
        {
            $DB->sql_put("INSERT INTO wD_Orders
                            (gameID, countryID, type)
                            VALUES ".implode(', ', $newOrders));
        }
    }
    
    protected function differenceForOnePower($Member, &$type)
    {
        global $DB, $Game;
        
        $difference = 0;
        
        if ( $Member->unitNo > $Member->supplyCenterNo )
        {
            $difference = $Member->unitNo - $Member->supplyCenterNo;
            $type = 'Destroy';
        }
        elseif ( $Member->unitNo < $Member->supplyCenterNo )
        {
            $difference = $Member->supplyCenterNo - $Member->unitNo;
            $type = 'Build Army';

            list($max_builds) = $DB->sql_row("SELECT COUNT(*)
                    FROM wD_TerrStatus ts
                    INNER JOIN wD_Territories t
                            ON ( t.id = ts.terrID )
                    WHERE ts.gameID = ".$Game->id."
                            AND ts.countryID = ".$Member->countryID."
                            AND (t.buildEligibilityFlags & (1 << ".$Member->countryID.") <> 0)
                            AND ts.occupyingUnitID IS NULL
                            AND t.supply = 'Yes'
                            AND t.mapID=".$Game->Variant->mapID);

            if ( $difference > $max_builds )
            {
                $difference = $max_builds;
            }
        }

        return $difference;
    }
}

/**
 * Description of Baron1900Variant_processOrderBuilds
 *
 * @author Alex Ronke
 */
class Baron1900Variant_processOrderBuilds extends BuildBasedOnFlagsVariant_processOrderBuilds
{
    protected function differenceForOnePower($Member, &$type)
    {
        if ($Member->countryID == 6 && $this->russianEMRImpact() == 1)
        {
            return $this->diffForRussianEMR($Member, $type);
        }
        else
        {
            return parent::differenceForOnePower($Member, $type);
        }
    }
        
    protected function diffForRussianEMR($Member, &$type)
    {
        global $DB, $Game;
        
        $difference = 0;
        
        if ( $Member->unitNo > $Member->supplyCenterNo + 1)
        {
            $difference = $Member->unitNo - ($Member->supplyCenterNo + 1);
            $type = 'Destroy';
        }
        elseif ( $Member->unitNo < $Member->supplyCenterNo + 1)
        {
            $difference = ($Member->supplyCenterNo + 1) - $Member->unitNo;
            $type = 'Build Army';

            list($max_builds) = $DB->sql_row("SELECT COUNT(*)
                FROM wD_TerrStatus ts
                INNER JOIN wD_Territories t
                        ON ( t.id = ts.terrID )
                WHERE ts.gameID = ".$Game->id."
                    AND ts.occupyingUnitID IS NULL
                        AND 
                        ((ts.countryID = ".$Member->countryID."
                            AND (t.buildEligibilityFlags & (1 << ".$Member->countryID.") <> 0)
                            AND t.supply = 'Yes')
                        OR
                        (t.id = 54))
                        AND t.mapID=".$Game->Variant->mapID);

            if ( $difference > $max_builds )
            {
                $difference = $max_builds;
            }
        }

        return $difference;
    }
    
    
    /**
     * Wipe all the incomplete orders.
     */
    public function completeAll()
    {
        global $DB, $Game;

        // Incomplete destroy orders are dealt with in the adjudicator
        $DB->sql_put("UPDATE wD_Orders o INNER JOIN wD_Members m ON ( o.gameID = m.gameID AND o.countryID = m.countryID )
                SET o.type = 'Wait'
                WHERE o.gameID = ".$Game->id." AND o.toTerrID IS NULL AND ( o.type = 'Build Army' OR o.type = 'Build Fleet' )");

        // Make sure users are set to either Wait or Destroy orders correctly depending on how many SCs vs Units they have.
        $DB->sql_put(
            "UPDATE wD_Orders o 
            INNER JOIN wD_Members m ON ( o.gameID = m.gameID AND o.countryID = m.countryID )
            SET o.type = IF( m.supplyCenterNo < m.unitNo, 'Destroy', 'Wait'), o.toTerrID = NULL
                WHERE m.countryID <> 6 AND o.gameID = ".$Game->id." AND (
                        ( NOT o.type = 'Destroy' AND m.supplyCenterNo < m.unitNo )
                        OR ( o.type = 'Destroy' AND m.supplyCenterNo > m.unitNo ) )");

        $emrImpact = $this->russianEMRImpact();

        $DB->sql_put(
            "UPDATE wD_Orders o 
            INNER JOIN wD_Members m ON ( o.gameID = m.gameID AND o.countryID = m.countryID )
            SET o.type = IF( m.supplyCenterNo + ".$emrImpact." < m.unitNo, 'Destroy', 'Wait'), o.toTerrID = NULL
                WHERE m.countryID = 6 AND o.gameID = ".$Game->id." AND (
                        ( NOT o.type = 'Destroy' AND m.supplyCenterNo + ".$emrImpact." < m.unitNo )
                        OR ( o.type = 'Destroy' AND m.supplyCenterNo + ".$emrImpact." > m.unitNo ) )");
    }


    protected function russianEMRImpact()
    {
        global $DB, $Game;
        list($howManyRussianHome)=$DB->sql_row(
                "SELECT count(*)
                FROM wD_TerrStatus
                WHERE gameID=".$Game->id." AND 
                terrID IN(86,91,92,97) AND 
                countryID=6");

        if ($howManyRussianHome > 0 && $howManyRussianHome < 4)
        {
            return 1;
        }
        return 0;
    }
}
