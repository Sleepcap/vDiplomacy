<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class Baron1900Variant_drawMap extends drawMap {

        protected $armies=array();
        protected $fleets=array();
    
        /**
	 * Initialize the image resource, getting it ready to be drawn to
	 *
	 * @param bool $smallmap True if displaying the smallmap, false if the large map
	 */
	public function __construct($smallmap)
	{
		global $Game;

		$this->mapID = MAPID;

		$this->smallmap = (bool) $smallmap;

		if ( !$this->smallmap )
			ini_set('memory_limit',"60M");

		$this->loadTerritories();
		$this->loadImages();
		$this->loadColors();
		$this->setTransparancies();
		$this->loadFont();
		$this->loadOrderArrows();
	}
    
	protected $countryColors = array(
		0 =>  array(210, 180, 140), // Neutral          D2 B4 8C
		1 =>  array(237, 188,  41), // Austria-Hungary  ED BC 29
		2 =>  array(  0,  36, 125), // Britain          00 24 7D
		3 =>  array(  3, 128, 184), // France           03 80 B8
		4 =>  array( 32,  32,  32), // Germany          10 10 10
		5 =>  array(  0, 166,  80), // Italy            00 92 46
                6 =>  array( 80, 113,  80), // Russia           50 71 50
                7 =>  array(227,  10,  23)  // Turkey           E3 0A 17
	);

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'     =>l_s('variants/Baron1900/resources/smallmap.png'),
				'army0'   =>l_s('variants/Baron1900/resources/units/Army0_32.png'),
				'fleet0'  =>l_s('variants/Baron1900/resources/units/Fleet0_32.png'),
                                'army1'   =>l_s('variants/Baron1900/resources/units/Army1_32.png'),
				'fleet1'  =>l_s('variants/Baron1900/resources/units/Fleet1_32.png'),
                                'army2'   =>l_s('variants/Baron1900/resources/units/Army2_32.png'),
				'fleet2'  =>l_s('variants/Baron1900/resources/units/Fleet2_32.png'),
                                'army3'   =>l_s('variants/Baron1900/resources/units/Army3_32.png'),
				'fleet3'  =>l_s('variants/Baron1900/resources/units/Fleet3_32.png'),
                                'army4'   =>l_s('variants/Baron1900/resources/units/Army4_32.png'),
				'fleet4'  =>l_s('variants/Baron1900/resources/units/Fleet4_32.png'),
                                'army5'   =>l_s('variants/Baron1900/resources/units/Army5_32.png'),
				'fleet5'  =>l_s('variants/Baron1900/resources/units/Fleet5_32.png'),
                                'army6'   =>l_s('variants/Baron1900/resources/units/Army6_32.png'),
				'fleet6'  =>l_s('variants/Baron1900/resources/units/Fleet6_32.png'),
                                'army7'   =>l_s('variants/Baron1900/resources/units/Army7_32.png'),
				'fleet7'  =>l_s('variants/Baron1900/resources/units/Fleet7_32.png'),
				'names'   =>l_s('variants/Baron1900/resources/smallmapNames.png'),
				'water'   =>l_s('variants/Baron1900/resources/smallMapWater.png'),
                                'standoff'=>l_s('images/icons/cross.png')
			);
		}
		else
		{
			return array(
				'map'     =>l_s('variants/Baron1900/resources/map.png'),
				'army0'   =>l_s('variants/Baron1900/resources/units/Army0_64.png'),
				'fleet0'  =>l_s('variants/Baron1900/resources/units/Fleet0_64.png'),
                                'army1'   =>l_s('variants/Baron1900/resources/units/Army1_64.png'),
				'fleet1'  =>l_s('variants/Baron1900/resources/units/Fleet1_64.png'),
                                'army2'   =>l_s('variants/Baron1900/resources/units/Army2_64.png'),
				'fleet2'  =>l_s('variants/Baron1900/resources/units/Fleet2_64.png'),
                                'army3'   =>l_s('variants/Baron1900/resources/units/Army3_64.png'),
				'fleet3'  =>l_s('variants/Baron1900/resources/units/Fleet3_64.png'),
                                'army4'   =>l_s('variants/Baron1900/resources/units/Army4_64.png'),
				'fleet4'  =>l_s('variants/Baron1900/resources/units/Fleet4_64.png'),
                                'army5'   =>l_s('variants/Baron1900/resources/units/Army5_64.png'),
				'fleet5'  =>l_s('variants/Baron1900/resources/units/Fleet5_64.png'),
                                'army6'   =>l_s('variants/Baron1900/resources/units/Army6_64.png'),
				'fleet6'  =>l_s('variants/Baron1900/resources/units/Fleet6_64.png'),
                                'army7'   =>l_s('variants/Baron1900/resources/units/Army7_64.png'),
				'fleet7'  =>l_s('variants/Baron1900/resources/units/Fleet7_64.png'),
				'names'   =>l_s('variants/Baron1900/resources/mapNames.png'),
                                'water'   =>l_s('variants/Baron1900/resources/mapWater.png'),
				'standoff'=>l_s('images/icons/cross.png')
			);
		}
	}
        
        protected function setTransparancy(array $image, array $color=array(255,255,255))
        {
            // Do nothing
        }

        public function countryFlag($terrName, $countryID)	
	{
		$this->unit_c[$terrName]=$countryID;
	}
        
        /**
	 * Put the given image array into the given position
	 * @param array $image An image array containing image resource, width and height
	 * @param int $x The x position on the map
	 * @param int $y The y position on the map
	 */
	protected function putImage(array $image, $x, $y)
	{
		$this->imagecopymerge_alpha($this->map['image'], $image['image'],
			$x, $y, 0, 0, $image['width'], $image['height'], 100);
	}
        
        /**
	 * Load a particular image resource and measure its width and length
	 * @param string $location A path to a PNG file to load
	 * @return array
	 */
	protected function loadImage($location)
	{
		$image = array();

		$image['image'] = imagecreatefrompng($location);
                imagealphablending($image['image'], true);
                imagesavealpha($image['image'], true);
		
                $image['width'] = imagesx($image['image']);
		$image['height'] = imagesy($image['image']);

		return $image;
	}
        
        /**
	 * Load all the image resources required, and measure their width and length
	 */
	protected function loadImages()
	{
		$resources = $this->resources();

		$this->map = $this->loadImage($resources['map']);
		$this->army = $this->loadImage($resources['army0']);
                $this->fleet = $this->loadImage($resources['fleet0']);
                
                for($i=0;$i<=7;$i++)
                {
                    $armyWithNum = "army$i";
                    $fleetWithNum = "fleet$i";
                    $this->armies[$i] = $this->loadImage($resources[$armyWithNum]);
                    $this->fleets[$i] = $this->loadImage($resources[$fleetWithNum]);
                }
                
		
		$this->standoff = $this->loadImage($resources['standoff']);
		$this->intermediateLayer=$resources['water'];
                $this->mapNames=$resources['names'];
	}
        
        /**
        * PNG ALPHA CHANNEL SUPPORT for imagecopymerge();
        * by Sina Salek
        *
        * 08-JAN-2011
        *
        **/
        protected function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
        {
            // creating a cut resource
            $cut = imagecreatetruecolor($src_w, $src_h);
            imagealphablending($cut, true);
            imagesavealpha($cut, true);

            // copying relevant section from background to the cut resource
            imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);

            // copying relevant section from watermark to the cut resource
            imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);

            // insert cut resource to destination image
            imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
            
            // free the cut resource memory
            imagedestroy($cut);
        } 
        
        public function colorTerritory($terrID, $countryID)
	{
                $terrName=$this->territoryNames[$terrID];
		if (strpos($terrName,')') === false)
		{
			$this->unit_c[$terrID]=$countryID;
			$this->unit_c[array_search($terrName. " (North Coast)" ,$this->territoryNames)]=$countryID;
			$this->unit_c[array_search($terrName. " (East Coast)"  ,$this->territoryNames)]=$countryID;
			$this->unit_c[array_search($terrName. " (South Coast)" ,$this->territoryNames)]=$countryID;
			$this->unit_c[array_search($terrName. " (West Coast)"  ,$this->territoryNames)]=$countryID;
		}
            
		/*
		 * The map files are both color coded so that each territory has its own
		 * unique color. When coloring a territory the territory's color is
		 * selected from the map, and imagecolorset() is used to change the
		 * territory's unique color to the desired color
		 */
		list($x, $y) = $this->territoryPositions[$terrID];

		$territoryColor = imagecolorat($this->map['image'], $x, $y);

                list($r, $g, $b) = $this->countryColors[$countryID];
                
                $fillColor = imagecolorallocatealpha($this->map['image'], $r, $g, $b, 0);
                
		imagefill($this->map['image'], $x, $y, $fillColor);
	}
        
        /**
	 * Draw a unit icon on the map
	 * @param string $terrName The territory to draw it at
	 * @param string $unitType The unit type
	 */
	public function addUnit($terrName, $unitType)
	{
            //libHTML::notice(l_t('Baron1900:addUnit called'),l_t('Baron1900:addUnit called'));
            
		list($x, $y) = $this->territoryPositions[$terrName];

		$this->updateBoundaries($x, $y);

                $country = $this->unit_c[$terrName];
                
		if ( $unitType == 'Army' )
			$unit = $this->armies[$country];
		else
			$unit = $this->fleets[$country];

		list($x,$y) = $this->absolutePosition($x, $y, $unit['width'], $unit['height']);

		$this->putImage($unit, $x, $y);
	}
        
}

?>