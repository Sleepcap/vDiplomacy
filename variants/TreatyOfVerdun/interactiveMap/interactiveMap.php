<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of IAmap
 *
 * @author tobi
 */
class LargeMapOnly_IAmap extends IAmap 
{
        protected $sourceMapName = 'map.png';
}

class CustomUnitIcons_IAmap extends LargeMapOnly_IAmap
{
        public function __construct($variant, $mapName = 'IA_smallmap.png') {
                parent::__construct($variant, $mapName);
                
                $this->buildButtonAutogeneration = true;
        }
        
        protected function resources() {
                return array(
                        'army'=>l_s('variants/'.$this->Variant->name.'/resources/army.png'),
                        'fleet'=>l_s('variants/'.$this->Variant->name.'/resources/fleet.png')
                );
        }
        
        protected function setTransparancies() {}
        
        protected function jsFooterScript() {
                libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army&variantID=".$this->Variant->id."';
                                        interactiveMap.parameters.imgBuildFleet = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Fleet&variantID=".$this->Variant->id."';";
        
                parent::jsFooterScript();
        }
		
		//Resize build-icons for larger unit-images
        protected function generateBuildIcon($unitType) {
                $this->territoryPositions['0'] = array(12,20);//position of unit on button
                
                //The image which stores the generated Build-Button
                $this->map = array(     'image' => imagecreatetruecolor(30, 30),
                                'width' => 30,
                                'height'=> 30
                );
                imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
                $this->setTransparancy($this->map);
        
                $this->drawCreatedUnit(0, $unitType);
                
                $tempImage = $this->map['image'];
                
                $this->map['image'] = imagecreatetruecolor(15, 15);
                imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
                $this->setTransparancy($this->map);
                        
                imagecopyresized($this->map['image'], $tempImage, 0, 0, 0, 0, 15, 15, 30, 30);
                imagedestroy($tempImage);
                
                $this->write('variants/'.$this->Variant->name.'/interactiveMap/IA_BuildIcon_'.$unitType.'.png');   
        
                imagedestroy($this->map['image']);
        }
}

class TreatyOfVerdunVariant_IAmap extends CustomUnitIcons_IAmap {}

?>
