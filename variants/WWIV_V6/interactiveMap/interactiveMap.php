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
         
 
class MapName_IAmap extends IAmap 
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
                
                $map = parent::loadMap('map.png');
                
                $map2 = imagecreatefrompng('variants/'.$this->Variant->name.'/resources/map_2.png');
                $map3 = imagecreatetruecolor(imagesx($map2), imagesy($map2));
                
                imagecopyresampled($map3, $map2, 0, 0, 0, 0, imagesx($map2), imagesy($map2), imagesx($map2), imagesy($map2));
                imagecolortransparent($map3, imagecolorallocate($map3, 0, 0, 0));
                
                imagecopymerge($map, $map3, 0, 0, 0, 0, imagesx($map), imagesY($map), 100);
                        
                imagedestroy($map2);
                imagedestroy($map3);
                
                return $map;
        }
}

class CoastConvoyOrders_IAmap extends Draw_IAmap 
{
        protected function jsFooterScript() {
                global $Variant;
                
                parent::jsFooterScript();
                
                libHTML::$footerScript[] = 'loadCoastConvoyOrders(Array("'.implode($Variant->convoyCoasts, '","').'"))';
        }
}

class WWIV_V6Variant_IAmap extends CoastConvoyOrders_IAmap {}

?>
