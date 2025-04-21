<?php

class BuildBasedOnFlagsVariant_userOrderBuilds extends userOrderBuilds
{
    public function __construct($orderID, $gameID, $countryID)
    {
        parent::__construct($orderID, $gameID, $countryID);
    }

    protected function toTerrIDCheck()
    {
        global $DB;

        // Don't duplicate destroy validation code
        if( $this->type != 'Build Army' && $this->type != 'Build Fleet' )
        {
            return parent::toTerrIDCheck();
        }

        if( $this->type == 'Build Army' )
        {
            /*
             * Creating an army at which territory
             *
             * Unoccupied supply centers owned by our country, which the specified unit type
             * can be built in. If a parent coast is found return Child entries.
             */
            return $this->sqlCheck("SELECT t.id
                FROM wD_TerrStatus ts
                INNER JOIN wD_Territories t
                        ON ( t.id = ts.terrID )
                WHERE ts.gameID = ".$this->gameID."
                        AND t.mapID=".MAPID."
                        AND ts.countryID = ".$this->countryID."
                        AND ts.occupyingUnitID IS NULL
                        AND t.id=".$this->toTerrID."
                        AND t.supply = 'Yes' AND NOT t.type='Sea'
                        AND NOT t.coast = 'Child'
                        AND (t.buildEligibilityFlags & (1 << ".$this->countryID.") <> 0)");
        }
        elseif( $this->type == 'Build Fleet' )
        {
            $query ="SELECT IF(t.coast='Parent', coast.id, t.id) as terrID
                FROM wD_TerrStatus ts
                INNER JOIN wD_Territories t ON ( t.id = ts.terrID )
                LEFT JOIN wD_Territories coast ON ( coast.mapID=".MAPID." AND coast.coastParentID = t.id AND NOT t.id = coast.id )
                WHERE ts.gameID = ".$this->gameID."
                        AND t.mapID=".MAPID."
                        AND ts.countryID = ".$this->countryID."
                        AND ts.occupyingUnitID IS NULL
                        AND t.supply = 'Yes'
                        AND ( t.type = 'Coast' OR t.type = 'Strait' )
                        AND (
                                (t.coast='Parent' AND coast.id=".$this->toTerrID.")
                                OR t.id=".$this->toTerrID."
                        )
                        AND (
                                t.coast='No' OR ( t.coast='Parent' AND NOT coast.id IS NULL )
                        AND (t.buildEligibilityFlags & (1 << ".$this->countryID.") <> 0))";
            
            return $this->sqlCheck($query);
        }
    }
}

class Baron1900Variant_userOrderBuilds extends BuildBasedOnFlagsVariant_userOrderBuilds
{
    public function __construct($orderID, $gameID, $countryID)
    {
        parent::__construct($orderID, $gameID, $countryID);
    }
    
    protected function toTerrIDCheck()
    {
        global $DB;
        
        // We only change behavior if it's a Russian order, it's an army, 
        // the EMR is in effect, and we're attempting to build in Siberia
        if ($this->countryID != 6 || $this->type != 'Build Army' || 
            $this->toTerrID != 54 || $this->russianEMRImpact() == 0)
        {
            return parent::toTerrIDCheck();
        }
        
        return $this->sqlCheck("SELECT t.id
            FROM wD_TerrStatus ts
            INNER JOIN wD_Territories t
                    ON ( t.id = ts.terrID )
            WHERE ts.gameID = ".$this->gameID."
                    AND t.mapID=".MAPID."
                    AND t.id=".$this->toTerrID."
                    AND ts.occupyingUnitID IS NULL");
    }
    
    protected function russianEMRImpact()
    {
        global $DB;
        list($howManyRussianHome)=$DB->sql_row(
                "SELECT count(*)
                FROM wD_TerrStatus
                WHERE gameID=".$this->gameID." AND 
                terrID IN(86,91,92,97) AND 
                countryID=6");

        if ($howManyRussianHome > 0 && $howManyRussianHome < 4)
        {
            return 1;
        }
        return 0;
    }
}