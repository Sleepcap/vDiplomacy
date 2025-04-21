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
	public function __construct($variant)
	{
		parent::__construct($variant, 'IA_map.png');
	}
}

/*
 * Only for the auto-draw feature
 */
class Draw_IAmap extends MapName_IAmap
{
	protected function loadMap($mapName = '')
	{
		ini_set("max_execution_time","600");
		
		$map = parent::loadMap('php1.png');
		
		$map2 = imagecreatefrompng('variants/'.$this->Variant->name.'/resources/php2.png');
		$map3 = imagecreatefrompng('variants/'.$this->Variant->name.'/resources/php3.png');
		$map4 = imagecreatetruecolor(imagesx($map2), imagesy($map2));
		$map5 = imagecreatetruecolor(imagesx($map3), imagesy($map3));
		
		imagecopyresampled($map4, $map2, 0, 0, 0, 0, imagesx($map2), imagesy($map2), imagesx($map2), imagesy($map2));
		imagecopyresampled($map5, $map3, 0, 0, 0, 0, imagesx($map3), imagesy($map3), imagesx($map3), imagesy($map3));
		imagecolortransparent($map4, imagecolorallocate($map4, 255, 255, 255));
		imagecolortransparent($map5, imagecolorallocate($map5, 255, 255, 255));
		
		imagecopymerge($map, $map4, 0, 0, 0, 0, imagesx($map), imagesY($map), 100);
		imagecopymerge($map, $map5, 0, 0, 0, 0, imagesx($map), imagesY($map), 100);
				
		imagedestroy($map2);
		imagedestroy($map3);
		imagedestroy($map4);
		imagedestroy($map5);
		
		return $map;
	}  
}

class Europa_RenovatioVariant_IAmap extends Draw_IAmap {}

?>
