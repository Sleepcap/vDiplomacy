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
                
                $this->smallmap = false;
        }
      
        protected function generateBuildIcons() {
                parent::generateBuildIcons();
                
                $this->generateBuildIcon("Army_1");
                $this->generateBuildIcon("Army_2");
                $this->generateBuildIcon("Army_3");
                $this->generateBuildIcon("Army_4");
                
                $this->generateBuildIcon("Fleet_1");
                $this->generateBuildIcon("Fleet_2");
                $this->generateBuildIcon("Fleet_3");
                $this->generateBuildIcon("Fleet_4");
        }
        
        public function addUnit($terrName, $unitType) {
                $tempArmy = $this->army;
                $tempFleet = $this->fleet;
                
                if(strlen($unitType)>5){
                        $numberPos = strpos($unitType, "_")+1;
                        
                        if($numberPos==5){      //Army
                                $this->army = $this->loadImage("variants/".$this->Variant->name."/resources/army".$this->Variant->countries[substr($unitType, $numberPos)-1].".png");
                                $unitType = "Army";
                        }else{                  //Fleet
                                $this->fleet = $this->loadImage("variants/".$this->Variant->name."/resources/fleet".$this->Variant->countries[substr($unitType, $numberPos)-1].".png");
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

class ChesspolitikVariant_IAmap extends CustomIcons_IAmap {}

?>
