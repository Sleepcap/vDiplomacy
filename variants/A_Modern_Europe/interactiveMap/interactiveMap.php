<?php

class Transform_IAmap extends IAmap
{
	protected function jsFooterScript()
	{
		global $Game;
		
		parent::jsFooterScript();
		
		if($Game->phase == "Diplomacy")
			libHTML::$footerScript[] = 'loadIAtransform();';
	}
}

class MapName_IAmap extends Transform_IAmap 
{
		protected $sourceMapName = "map.png";
	
        public function __construct($variant) {
                parent::__construct($variant, 'IA_map.png');
        }
}

class A_Modern_EuropeVariant_IAmap extends MapName_IAmap {}

?>