<?php
if(!in_array('header.php', get_required_files())){
    chdir(dirname(__FILE__).'/../..');
    require_once('header.php');
}

require_once 'map/drawMap.php';
    

/*
 * extends drawMap to acces some useful protected functions inside the drawMap-Class (for BuildIcon_generation)
 */
class IAmap extends drawMap {

    protected $mapName;

    public function __construct($variant, $mapName = 'IA_smallmap.png') {
        $this->Variant = $variant;

        $this->mapName = $mapName;
    }

    protected $Variant;

    protected $map;
    protected $territoryPositions;
    protected $usedColors;

	/**
	 * @param bool $colorCheck - If set true the map color check of the autocolor
	 * script is executed even if the autoscript is not used. Usefull for debugging
	 * provided maps.
	 */
    public function drawMap($colorCheck = false) {
        //check if there is a cached version. Delete it (it's only needed for development)
        if(file_exists('variants/' . $this->Variant->name . '/cache/temp_' . $this->mapName))
            unlink('variants/' . $this->Variant->name . '/cache/temp_' . $this->mapName);
        
		/*
		 * If the map is not drawn yet, an autocolor script will be called and 
		 * attempts to draw map.
		 */
		$needsDrawing = !file_exists('variants/' . $this->Variant->name . '/interactiveMap/' . $this->mapName);
		
		
        if ($needsDrawing || $colorCheck) {
            ini_set("memory_limit", "1024M");

            $this->territoryPositions = $this->getTerritoryPositions();

			if($needsDrawing){
				$this->map = $this->loadMap();
				$this->usedColors = $this->getColors();
				$this->colorTerritories();
			}elseif ($colorCheck) {
				$this->map = imagecreatefrompng('variants/' . $this->Variant->name . '/interactiveMap/' . $this->mapName);
			}

            $cC = $this->coloredCorrectly();
            if ($cC != null)
                die($cC);

            if($needsDrawing)
				$this->saveMap();
			        
			imagedestroy($this->map);	
            
            $this->deleteMapData(); //after the map has been updated, the MapData is propably inaccurate
        }
    }
	
    protected $sourceMapName = 'smallmap.png';

    protected function loadMap($mapName = '') {
        $mapName = ($mapName == '') ? $this->sourceMapName : $mapName;

        $map = imagecreatefrompng('variants/' . $this->Variant->name . '/resources/' . $mapName);

        $map2 = imagecreatetruecolor(imagesx($map), imagesy($map));

        imagecopyresampled($map2, $map, 0, 0, 0, 0, imagesx($map), imagesy($map), imagesx($map), imagesy($map));

        imagedestroy($map);

        return $map2;
    }

    protected function getTerritoryPositions() {
        global $DB;

        $territoryPositionsSQL = "SELECT id, coast, smallMapX, smallMapY FROM wD_Territories WHERE mapID=" . $this->Variant->mapID;

        $territoryPositions = array();
        $tabl = $DB->sql_tabl($territoryPositionsSQL);
        while (list($terrID, $coast, $x, $y) = $DB->tabl_row($tabl)) {
            if ($coast != 'Child') {
                $territoryPositions[$terrID] = array(intval($x), intval($y));
            }
        }

        return $territoryPositions;
    }

    protected function getColors() {
        $colors = array();

        $colors[0] = array(//black borders
            'r' => 0,
            'g' => 0,
            'b' => 0
        );

        foreach ($this->territoryPositions as $terrID => $terrPos) {
            $rgb = imagecolorat($this->map, $terrPos[0], $terrPos[1]);
            $colors[$terrID] = array(
                'r' => ($rgb >> 16) & 0xFF,
                'g' => ($rgb >> 8) & 0xFF,
                'b' => $rgb & 0xFF
            );
        }

        return $colors;
    }

    protected function colorTerritories() {
        foreach ($this->territoryPositions as $terrID => $terrPos) {
            $this->usedColors[$terrID] = $this->IA_colorTerritory($terrID);
            $color = imagecolorallocate($this->map, $this->usedColors[$terrID]['r'], $this->usedColors[$terrID]['g'], $this->usedColors[$terrID]['b']);
            imagefill($this->map, $terrPos[0], $terrPos[1], $color);
        }
    }

    protected function IA_colorTerritory($terrID) {
        $territories = array_keys($this->usedColors, $this->usedColors[$terrID]);
        if (count($territories) > 1) {
            return $this->newColor();
        } else {
            return $this->usedColors[$terrID];
        }
    }

    protected function newColor() {

        $newColor = array(
            'r' => 255,
            'g' => 255,
            'b' => 255
        );

        for (; $newColor['r'] >= 0; $newColor['r']--) {
            if (in_array($newColor, $this->usedColors)) {
                for (; $newColor['g'] >= 0; $newColor['g']--) {
                    if (in_array($newColor, $this->usedColors)) {
                        for (; $newColor['b'] >= 0; $newColor['b']--) {
                            if (!in_array($newColor, $this->usedColors)) {
                                return $newColor;
                            }
                        }
                    } else {
                        return $newColor;
                    }
                }
            } else {
                return $newColor;
            }
        }

        return $newColor;
    }
    
    protected function getTerritoryNames() {
        global $DB;

        $territoryPositionsSQL = "SELECT id, coast, name FROM wD_Territories WHERE mapID=" . $this->Variant->mapID;

        $territoryNames = array();
        $tabl = $DB->sql_tabl($territoryPositionsSQL);
        while (list($terrID, $coast, $name) = $DB->tabl_row($tabl)) {
            if ($coast != 'Child') {
                $territoryNames[$terrID] = $name;
            }
        }

        return $territoryNames;
    }

    protected function coloredCorrectly() {
        $errors = array();
        
        $this->usedColors = $this->getColors();
        foreach ($this->usedColors as $color) {
            $territories = array_keys($this->usedColors, $color);
            if (count($territories) > 1) {
                $errors[] = $territories;
            }
        }
        if(count($errors) > 0){
            $terrNames = $this->getTerritoryNames();
            
            $errorString = "Unable to load ".$this->mapName."<br> The following territories aren't separated by a border: <br>";
            foreach($errors as $index=>$error){
                $errorString .= "<p> ".$index.":<br>";
                foreach($error as $terrID){
					if($terrID!=0)
						$errorString .= $terrNames[$terrID] . "(" . $terrID ."), ";
					else
						$errorString .= "black color (reserved for border), ";
                }
                $errorString .= "</p>";
            }
            
            return $errorString;
        }
        
        return null;
    }

    protected function saveMap() {
        if(file_exists('variants/' . $this->Variant->name . '/interactiveMap/'))
            imagepng($this->map, 'variants/' . $this->Variant->name . '/interactiveMap/' . $this->mapName);
        else
            //there is now interactiveMap directory yet -> created a temp version in the cache directory
            imagepng($this->map, 'variants/' . $this->Variant->name . '/cache/temp_' . $this->mapName );
    }

    public function serveMap() {
        require_once('lib/html.php');
        
        define('DELETECACHE', 0);

        if(file_exists('variants/' . $this->Variant->name . '/interactiveMap/' . $this->mapName))
            libHTML::serveImage('variants/' . $this->Variant->name . '/interactiveMap/' . $this->mapName);
        else
            libHTML::serveImage('variants/' . $this->Variant->name . '/cache/temp_' . $this->mapName);
    }
    
    protected function deleteMapData(){
		foreach (glob('variants/' . $this->Variant->name . '/cache/IA_mapData*.map') as $jsfilename)
			unlink($jsfilename);
    }

	/*
	 * Generate a path to the cashed map data. If the variant has a code version
	 * this verion number will be used in the naming scheme so an outdated cashed
	 * file can be identified.
	 */
	public function generateMapDataPath(){
		$fileName = 'variants/' . $this->Variant->name . '/cache/IA_mapData';
		
		if( isset($this->Variant->codeVersion)
			&& $this->Variant->codeVersion !=null && $this->Variant->codeVersion != 0 )
			{
				$fileName .= '_'.$this->Variant->codeVersion;
			}
		$fileName .='.map';
		
		return $fileName;
	}
	
    public function createMapData($uncache = false) {
		$fileName = $this->generateMapDataPath();
		
        if ($uncache || !file_exists($fileName)) {
			$this->deleteMapData(); // delete any map data files that are not needed any longer (a new one will be just created)
			
            ini_set("memory_limit", "1024M");
            set_time_limit(30);

            $colors = array();

            $map = imagecreatefrompng('variants/' . $this->Variant->name . '/interactiveMap/' . $this->mapName);

            $territoryPositions = $this->getTerritoryPositions();

            foreach ($territoryPositions as $terrID => $terrPos) {
                $colors[imagecolorat($map, $terrPos[0], $terrPos[1])]["ID"] = $terrID;
                $colors[imagecolorat($map, $terrPos[0], $terrPos[1])]["Positions"][] = $terrPos;
                imagefill($map, $terrPos[0], $terrPos[1], 0);
            }

            for ($y = 0; $y < imagesy($map); $y++) {
                for ($x = 0; $x < imagesx($map); $x++) {
                    $color = imagecolorat($map, $x, $y);
                    if ($color != 0) {
                        if (isset($colors[$color]["ID"])) {
                            $colors[$color]["Positions"][] = array($x, $y);
                            imagefill($map, $x, $y, 0);
                        }
                    }
                }
            }
            imagedestroy($map);
            //var_dump($colors);

            $terrColorPos = array();

            foreach ($colors as $content) {
                $terrColorPos[$content["ID"]] = $content["Positions"];
            }
            //var_dump($terrColorPos);

            file_put_contents($fileName, json_encode($terrColorPos));
        }
    }

    public function serveMapData() {
        echo file_get_contents($this->generateMapDataPath());
    }
    
    /*
     * variant-specific JS-scripts for interactiveMap
     */
    
    protected function jsLoadBasicIAScripts() {
        libHTML::$footerIncludes[] = '../interactiveMap/javascript_1.0/interactiveMap.js';
	libHTML::$footerIncludes[] = '../interactiveMap/javascript_1.0/interactiveMapDraw.js';
	libHTML::$footerIncludes[] = '../interactiveMap/javascript_1.0/interactiveMapOrders.js';
	libHTML::$footerIncludes[] = '../interactiveMap/javascript_1.0/interactiveMapButtons.js';
    }
    
    protected function jsAutoloadScripts($IApath) {
        $directoryContent = scandir(l_r($IApath));
        
        foreach($directoryContent as $filename){
                if(pathinfo($IApath.'/'.$filename, PATHINFO_EXTENSION) == 'js')
                        libHTML::$footerIncludes[] = '../'.$IApath.'/'.$filename;
        }
    }
    
    protected function jsFooterScript() {
        global $User;
        
        if(isset($User->pointNClick)){    
            libHTML::$footerScript[] = 'interactiveMap.options.scrollbars = '.($User->scrollbars=='Yes' ? 'true' : 'false');
            libHTML::$footerScript[] = 'interactiveMap.options.greyOut = '.($User->terrGrey=='off' ? 'false' : 'true');
            libHTML::$footerScript[] = 'interactiveMap.options.unitGreyOut = '.($User->terrGrey=='all' ? 'true' : 'false');
            libHTML::$footerScript[] = 'interactiveMap.options.greyOutIntensity = 0.'.$User->greyOut;
			libHTML::$footerScript[] = 'interactiveMap.options.buttonWidthAutomatic = '.($User->buttonWidth == 'auto' ? 'true' : 'false');
			libHTML::$footerScript[] = 'interactiveMap.options.buttonWidth = '.($User->buttonWidth == 'small' ? 'interactiveMap.parameters.smallButtonSize' : 'interactiveMap.parameters.largeButtonSize');
        }
			
	libHTML::$footerScript[]   = 'loadIA();';
    }
    
    public function jsLoadInteractiveMap() {
        global $Game;
        
        $IApath = l_r('variants/'.$Game->Variant->name.'/interactiveMap');
        
        if(!file_exists($IApath)) return; //interactiveMap-feature not implemented for this variant
        
        $this->jsLoadBasicIAScripts();
        
        $this->jsAutoloadScripts($IApath);
        
        $this->jsFooterScript();
    }
    
    /*
     * Autogeneration of Build-Buttons (with variant-typical unit-icons)
     */
    protected $buildButtonAutogeneration = false;
    
    //equivalent to drawMap->resources()
    protected function resources(){
        return array(
                'army'=>l_s('contrib/smallarmy.png'),
                'fleet'=>l_s('contrib/smallfleet.png')
        );
    }
    
    //equivalent to drawMap->loadImages()
    protected function loadImages(){
        $resources = $this->resources();
      
        $this->army = $this->loadImage($resources['army']);
        $this->fleet = $this->loadImage($resources['fleet']);
        
        $this->imagesLoaded = true;
    
    }
    
    protected $imagesLoaded = false;    
    
    public function serveBuildIcon($unitType = 'Army') {
        if(!$this->buildButtonAutogeneration) return;
            
        if(!file_exists('variants/'.$this->Variant->name.'/interactiveMap/IA_BuildIcon_'.$unitType.'.png'))
                $this->generateBuildIcons();
        
        require_once('lib/html.php');
        
        define('DELETECACHE', 0);

        libHTML::serveImage('variants/' . $this->Variant->name . '/interactiveMap/IA_BuildIcon_'.$unitType.'.png');
    }
    
    protected function generateBuildIcons(){
        $this->loadImages();
        
        $this->setTransparancies();
        
        $this->territoryPositions['0'] = array(5,10);//position of unit on button
        
        $this->generateBuildIcon('Army');
        $this->generateBuildIcon('Fleet');
       
    }
    
    protected function generateBuildIcon($unitType){
        //The image which stores the generated Build-Button
        $this->map = array(     'image' => imagecreatetruecolor(15, 15),
                                'width' => 15,
                                'height'=> 15
        );
        imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
        $this->setTransparancy($this->map);
        
        $this->drawCreatedUnit(0, $unitType);
        $this->write('variants/'.$this->Variant->name.'/interactiveMap/IA_BuildIcon_'.$unitType.'.png');   
        
        imagedestroy($this->map['image']);
    }
    
    public function __destruct() {}
}

function getIAmapObject(){
    $variant = loadVariant();
    
    $IAmapClassName = 'IAmap';
    if(file_exists('variants/' . $variant->name . '/interactiveMap/interactiveMap.php')){
        require_once 'variants/' . $variant->name . '/interactiveMap/interactiveMap.php';
        $IAmapClassName =  $variant->name.'Variant_IAmap';
    }
    
    return new $IAmapClassName($variant);
}

function loadVariant() {
    if (isset($_REQUEST['gameID'])) {
        return libVariant::loadFromGameID($_REQUEST['gameID']);
    } elseif (isset($_REQUEST['variantID'])) {
        return libVariant::loadFromVariantID($_REQUEST['variantID']);
    } else {
        die("No gameID/variantID");
    }
}

?>
