<?php

class SpecialRules_IAmap extends IAmap{

        protected function jsFooterScript() {
                parent::jsFooterScript();
                
                libHTML::$footerScript[] = 'loadSpecialOrders(); updateButtons();';
        }
}

class SpecialBuildIcons_IAmap extends SpecialRules_IAmap {

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
        
        protected function jsFooterScript() {
                libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army&variantID=".$this->Variant->id."';";
        
                parent::jsFooterScript();
        }

        //Resize build-icons for larger unit-images
        protected function generateBuildIcon($unitType) {
                $this->territoryPositions['0'] = array(8,14);//position of unit on button
                
                //The image which stores the generated Build-Button
                $this->map = array(     'image' => imagecreatetruecolor(22, 22),
                                'width' => 22,
                                'height'=> 22
                );
                imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
                $this->setTransparancy($this->map);
        
                $this->drawCreatedUnit(0, $unitType);
                
                $tempImage = $this->map['image'];
                
                $this->map['image'] = imagecreatetruecolor(15, 15);
                imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
                $this->setTransparancy($this->map);
                        
                imagecopyresized($this->map['image'], $tempImage, 0, 0, 0, 0, 15, 15, 22, 22);
                imagedestroy($tempImage);
                
                $this->write('variants/'.$this->Variant->name.'/interactiveMap/IA_BuildIcon_'.$unitType.'.png');   
        
                imagedestroy($this->map['image']);
        }
}

class LepantoVariant_IAmap extends SpecialBuildIcons_IAmap{}

?>