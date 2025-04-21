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

class Fog_IAmap extends IAmap {
     
        protected function jsFooterScript() {
                global $User, $DB, $Game;
                
                parent::jsFooterScript();

		list($ccode)=$DB->sql_row("SELECT text FROM wD_Notices WHERE toUserID=3 AND timeSent=0 AND fromID=".$Game->id);
                $verify=substr($ccode,((int)$Game->Members->ByUserID[$User->id]->countryID)*6,6);
                
                $test = libHTML::$footerScript;
                
                foreach(libHTML::$footerScript as $index=>$script){
				if($script == 'loadIA();')
                                        libHTML::$footerScript[$index]=str_replace('loadIA();','loadIA("'.$this->Variant->name.'","'.$verify.'");', $script);
                }
        }
        
        //ignore fake-territories (Islands and Seas
        protected function getTerritoryPositions() {
                global $DB;

                $territoryPositionsSQL = "SELECT id, name, coast, smallMapX, smallMapY FROM wD_Territories WHERE mapID=" . $this->Variant->mapID;

                $territoryPositions = array();
                $tabl = $DB->sql_tabl($territoryPositionsSQL);
                while (list($terrID, $name, $coast, $x, $y) = $DB->tabl_row($tabl)) {
                        if (strpos($name,' - Islands') || strpos($name,' - Seas') ) continue;
                        
                        if ($coast != 'Child') {
                                $territoryPositions[$terrID] = array(intval($x), intval($y));
                        }
                }

                return $territoryPositions;
        }
}

class CustomIcons_IAmap extends Fog_IAmap {
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

class PunicWarsVariant_IAmap extends CustomIcons_IAmap {}

?>
