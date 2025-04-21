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
		protected $sourceMapName = "waw1937php.png";
	
        public function __construct($variant) {
                parent::__construct($variant, 'IA_map.png');
        }
}

class WorldAtWar1937Variant_IAmap extends MapName_IAmap {}

?>
