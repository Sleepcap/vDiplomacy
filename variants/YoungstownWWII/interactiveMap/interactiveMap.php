<?php

class MapName_IAmap extends IAmap 
{
		protected $sourceMapName = "map.png";
	
        public function __construct($variant) {
                parent::__construct($variant, 'IA_map.png');
        }
}

class YoungstownWWIIVariant_IAmap extends MapName_IAmap {}

?>
