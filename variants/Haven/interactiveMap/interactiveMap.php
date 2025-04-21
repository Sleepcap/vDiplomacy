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
    public function __construct($variant) {
        parent::__construct($variant, 'IA_map.png');
    }
    
    protected $sourceMapName = 'map_2.png';
    
    protected function getTerritoryPositions() {
        global $DB;

        $territoryPositionsSQL = "SELECT id, coast, smallMapX, smallMapY FROM wD_Territories WHERE mapID=" . $this->Variant->mapID . " AND id<249";//ignore fake-territories

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
                
                $this->smallmap = false;
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
                $this->generateBuildIcon("Army_8");
                $this->generateBuildIcon("Army_9");
                $this->generateBuildIcon("Army_10");
                $this->generateBuildIcon("Army_11");
                $this->generateBuildIcon("Army_12");
                $this->generateBuildIcon("Army_13");
                $this->generateBuildIcon("Army_14");
                $this->generateBuildIcon("Army_15");
                $this->generateBuildIcon("Army_16");
                $this->generateBuildIcon("Army_17");
                $this->generateBuildIcon("Army_18");
                $this->generateBuildIcon("Army_19");
                
                $this->generateBuildIcon("Fleet_1");
                $this->generateBuildIcon("Fleet_2");
                $this->generateBuildIcon("Fleet_3");
                $this->generateBuildIcon("Fleet_4");
                $this->generateBuildIcon("Fleet_5");
                $this->generateBuildIcon("Fleet_6");
                $this->generateBuildIcon("Fleet_7");
                $this->generateBuildIcon("Fleet_8");
                $this->generateBuildIcon("Fleet_9");
                $this->generateBuildIcon("Fleet_10");
                $this->generateBuildIcon("Fleet_11");
                $this->generateBuildIcon("Fleet_12");
                $this->generateBuildIcon("Fleet_13");
                $this->generateBuildIcon("Fleet_14");
                $this->generateBuildIcon("Fleet_15");
                $this->generateBuildIcon("Fleet_16");
                $this->generateBuildIcon("Fleet_17");
                $this->generateBuildIcon("Fleet_18");
                $this->generateBuildIcon("Fleet_19");
        }
        
        public function addUnit($terrName, $unitType) {
                $tempArmy = $this->army;
                $tempFleet = $this->fleet;
                
                if(strlen($unitType)>5){
                        $numberPos = strpos($unitType, "_")+1;
                        
                        if($numberPos==5){      //Army
                                $this->army = $this->loadImage("variants/".$this->Variant->name."/resources/army_country_".substr($unitType, $numberPos).".png");
                                $unitType = "Army";
                        }else{                  //Fleet
                                $this->fleet = $this->loadImage("variants/".$this->Variant->name."/resources/fleet_country_".substr($unitType, $numberPos).".png");
                                $unitType = "Fleet";
                        }
                }
                
                parent::addUnit($terrName, $unitType);
                
                $this->army = $tempArmy;
                $this->fleet = $tempFleet;
                
                $this->army['height'] = 18;
                $this->army['width']  = 18;
        }
        
        //Resize build-icons for larger unit-images
        protected function generateBuildIcon($unitType) {
                $this->territoryPositions['0'] = array(16,20);//position of unit on button
                
                //The image which stores the generated Build-Button
                $this->map = array(     'image' => imagecreatetruecolor(32, 32),
                                'width' => 32,
                                'height'=> 32
                );
                imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
                $this->setTransparancy($this->map);
        
                $this->drawCreatedUnit(0, $unitType);
                
                $tempImage = $this->map['image'];
                
                $this->map['image'] = imagecreatetruecolor(15, 15);
                imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
                $this->setTransparancy($this->map);
                        
                imagecopyresized($this->map['image'], $tempImage, 0, 0, 0, 0, 15, 15, 32, 32);
                imagedestroy($tempImage);
                
                $this->write('variants/'.$this->Variant->name.'/interactiveMap/IA_BuildIcon_'.$unitType.'.png');   
        
                imagedestroy($this->map['image']);
        }
        
        protected function jsFooterScript() {
                libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army_'+context.countryID+'&variantID=".$this->Variant->id."';
                                        interactiveMap.parameters.imgBuildFleet = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Fleet_'+context.countryID+'&variantID=".$this->Variant->id."';";
        
                parent::jsFooterScript();
        }
}

class HavenVariant_IAmap extends CustomIcons_IAmap {}

?>
