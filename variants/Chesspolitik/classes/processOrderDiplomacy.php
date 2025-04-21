<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class NoRetreats_processOrderDiplomacy extends processOrderDiplomacy {

    /**
     * Auto-disband all dislodged units
     */
    public function apply($standoffTerrs){
        global $DB;

        parent::apply($standoffTerrs);

        // auto-disband dislodged units
        $DB->sql_put(
            "DELETE FROM u
            USING wD_Units AS u
            INNER JOIN wD_TerrStatus ts ON ( ts.retreatingUnitID = u.id AND ts.gameID=".$GLOBALS['GAMEID']." )");

        // remove pending retreats
        $DB->sql_put(
            "UPDATE wD_TerrStatus ts
            SET ts.retreatingUnitID = NULL
            WHERE ( ts.retreatingUnitID IS NOT NULL AND ts.gameID=".$GLOBALS['GAMEID']." )");
            
    }
}

class ChesspolitikVariant_processOrderDiplomacy extends NoRetreats_processOrderDiplomacy{}
