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

class CustomIcons_IAmap extends IAmap {
        
        public function __construct($variant, $mapName = 'IA_smallmap.png') {
                parent::__construct($variant, $mapName);
                
                $this->buildButtonAutogeneration = true;
        }
      
        protected function generateBuildIcons() {
                parent::generateBuildIcons();
                
                $this->generateBuildIcon("Army_1");
                $this->generateBuildIcon("Army_2");
                $this->generateBuildIcon("Army_3");
				$this->generateBuildIcon("Army_4");
				$this->generateBuildIcon("Army_5");
				$this->generateBuildIcon("Army_6");
				$this->generateBuildIcon("Army_7");
                
                $this->generateBuildIcon("Fleet_1");
                $this->generateBuildIcon("Fleet_2");
                $this->generateBuildIcon("Fleet_3");
				$this->generateBuildIcon("Fleet_4");
				$this->generateBuildIcon("Fleet_5");
				$this->generateBuildIcon("Fleet_6");
				$this->generateBuildIcon("Fleet_7");
				
        }
        
        public function addUnit($terrName, $unitType) {
                $tempArmy = $this->army;
                $tempFleet = $this->fleet;
                
                if(strlen($unitType)>5){
                        $numberPos = strpos($unitType, "_")+1;
                        
                        if($numberPos==5){      //Army
                                $this->army = $this->loadImage("variants/".$this->Variant->name."/resources/smallarmy_".substr($unitType, $numberPos).".png");
                                $unitType = "Army";
                        }else{                  //Fleet
                                $this->fleet = $this->loadImage("variants/".$this->Variant->name."/resources/smallfleet_".substr($unitType, $numberPos).".png");
                                $unitType = "Fleet";
                        }
                }
                
                parent::addUnit($terrName, $unitType);
                
                $this->army = $tempArmy;
                $this->fleet = $tempFleet;
        }
        
        protected function jsFooterScript() {
                libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army_'+context.countryID+'&variantID=".$this->Variant->id."';
                                        interactiveMap.parameters.imgBuildFleet = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Fleet_'+context.countryID+'&variantID=".$this->Variant->id."';";
        
                parent::jsFooterScript();
        }
}

class FirstCrusadeVariant_IAmap extends CustomIcons_IAmap {}

?>
