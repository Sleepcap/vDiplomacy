<?php
// ingore fake north sea
class IgnoreFakeTerritories_IAmap extends IAmap {
    protected function getTerritoryPositions() {
        global $DB;

        $territoryPositionsSQL = "SELECT id, coast, smallMapX, smallMapY FROM wD_Territories WHERE mapID=" . $this->Variant->mapID . " AND id<34";//ignore fake-territories

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

class CustomBuildIcons_IAmap extends IgnoreFakeTerritories_IAmap {
		
		// custom build icons (taken from Vikings)
		protected function jsFooterScript() {
                libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'variants/".$this->Variant->name."/interactiveMap/IA_BuildIcon_Army.png';
                                        interactiveMap.parameters.imgBuildFleet = 'variants/".$this->Variant->name."/interactiveMap/IA_BuildIcon_Fleet.png';";
        
                parent::jsFooterScript();
        }
}

class OneWay_IAmap extends CustomBuildIcons_IAmap {
		protected function jsFooterScript() {
                global $Game;
                
                parent::jsFooterScript();
                
                if( ($Game->phase=='Diplomacy') )
                    libHTML::$footerScript[] = ' loadOneWay();';
        }
}

class NorthSeaWarsVariant_IAmap extends OneWay_IAmap {}

?>
