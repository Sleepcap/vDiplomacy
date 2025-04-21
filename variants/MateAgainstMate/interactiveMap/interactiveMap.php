<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of interactiveMap
 *
 * @author tobi
 */
class IgnoreFakeTerritories_IAmap extends IAmap {

    protected function getTerritoryPositions() {
        global $DB;

        $territoryPositionsSQL = "SELECT id, coast, smallMapX, smallMapY FROM wD_Territories WHERE mapID=" . $this->Variant->mapID . " AND id<114";//ignore fake-territories

        $territoryPositions = array();
        $tabl = $DB->sql_tabl($territoryPositionsSQL);
        while (list($terrID, $coast, $x, $y) = $DB->tabl_row($tabl)) {
            if ($coast != 'Child') {
                $territoryPositions[$terrID] = array(intval($x), intval($y));
            }
        }

        return $territoryPositions;
	}
}

class Build_Indonesia_IAmap extends IgnoreFakeTerritories_IAmap {
		protected function jsFooterScript() {
                global $Member;
                
                parent::jsFooterScript();
                
                if( ($Member->Game->phase=='Builds') && ($Member->countryID == 1) )
                    libHTML::$footerScript[] = ' loadCustomBuildIndonesia();';
        }
}

class MateAgainstMateVariant_IAmap extends Build_Indonesia_IAmap {}

?>
