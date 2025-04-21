<?php

class Transform_IAmap extends IAmap{
        protected function jsFooterScript() {
                global $Game;
                
                parent::jsFooterScript();
                
                if($Game->phase == "Diplomacy")
                        libHTML::$footerScript[] = 'loadIAtransform();';
        }
}

class MapName_IAmap extends Transform_IAmap 
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
                
                $map = parent::loadMap('usphp1.png');
                
                $map2 = imagecreatefrompng('variants/'.$this->Variant->name.'/resources/usphp2.png');
                $map3 = imagecreatetruecolor(imagesx($map2), imagesy($map2));
                
                imagecopyresampled($map3, $map2, 0, 0, 0, 0, imagesx($map2), imagesy($map2), imagesx($map2), imagesy($map2));
                imagecolortransparent($map3, imagecolorallocate($map3, 255, 255, 255));
                
                imagecopymerge($map, $map3, 0, 0, 0, 0, imagesx($map), imagesY($map), 100);
                        
                imagedestroy($map2);
                imagedestroy($map3);
                
                return $map;
        }  
}

class Divided_StatesVariant_IAmap extends Draw_IAmap {}

?>
