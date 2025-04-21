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
class CustomUnitIcons_IAmap extends IAmap {
        public function __construct($variant, $mapName = 'IA_smallmap.png') {
                parent::__construct($variant, $mapName);
                
                $this->buildButtonAutogeneration = true;
        }
        
        protected function resources() {
                return array(
                        'army'=>l_s('variants/'.$this->Variant->name.'/resources/Army.png'),
                        'fleet'=>l_s('variants/'.$this->Variant->name.'/resources/Fleet.png')
                );
        }
        
        protected function setTransparancies() {}
        
        protected function jsFooterScript() {
                libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army&variantID=".$this->Variant->id."';
                                        interactiveMap.parameters.imgBuildFleet = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Fleet&variantID=".$this->Variant->id."';";
        
                parent::jsFooterScript();
        }
}

class MapName_IAmap extends CustomUnitIcons_IAmap 
{
        public function __construct($variant) {
                parent::__construct($variant, 'IA_map.png');
        }
}

/*
 * Only for the auto-draw feature
 */
class Draw_IAmap extends MapName_IAmap
{
        protected function loadMap($mapName = '') {
                ini_set("max_execution_time","60");
                
                $map = imagecreatefrompng('variants/'.$this->Variant->name.'/resources/map.png');
                $map2 = imagecreatefrompng('variants/'.$this->Variant->name.'/resources/map_2.png');
                $map3 = imagecreatetruecolor(imagesx($map2), imagesy($map2));
                
                imagecopyresampled($map3, $map2, 0, 0, 0, 0, imagesx($map2), imagesy($map2), imagesx($map2), imagesy($map2));
                //imagecolortransparent($map3, imagecolorallocate($map3, 255, 255, 255));
                
                imagecopymerge($map3, $map, 0, 0, 0, 0, imagesx($map), imagesY($map), 100);
                        
                imagedestroy($map);
                imagedestroy($map2);
                
                return $map3;
        }  
}

class Imperial2Variant_IAmap extends Draw_IAmap {}

?>
