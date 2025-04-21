<?php

// ingore fake coin stake territories
class IgnoreFakeTerritories_IAmap extends IAmap {
    protected function getTerritoryPositions() {
        global $DB;

        $territoryPositionsSQL = "SELECT id, coast, smallMapX, smallMapY FROM wD_Territories WHERE mapID=" . $this->Variant->mapID . " AND (id<85 OR id>108)";//ignore fake-territories

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

class CustomIcons_IAmap extends IgnoreFakeTerritories_IAmap {
        
        public function __construct($variant, $mapName = 'IA_smallmap.png') {
                parent::__construct($variant, $mapName);
                
                $this->buildButtonAutogeneration = true;
        }
		
		protected function resources() {
                return array(
                        'army'=>l_s('variants/'.$this->Variant->name.'/resources/smallarmy.png'),
                        'fleet'=>l_s('variants/'.$this->Variant->name.'/resources/smallfleet.png')
                );
        }
        
        protected function setTransparancies() {}
        
        protected function jsFooterScript() {
                libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army&variantID=".$this->Variant->id."';
                                        interactiveMap.parameters.imgBuildFleet = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Fleet&variantID=".$this->Variant->id."';";
        
                parent::jsFooterScript();
        }
}

/*
 * Do not load the interactive map during bidding phase.
 */
class GreekDipVariant_IAmap extends CustomIcons_IAmap {
	function jsLoadInteractiveMap() {
		global $Game;
		
		if($Game->turn == 0) return; //skip bidding phase
		
		parent::jsLoadInteractiveMap();
	}
}
