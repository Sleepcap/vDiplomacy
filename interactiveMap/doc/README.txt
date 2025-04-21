Content of download package:
----------------------------
- README
- required files for installation
- example for "WWIV" variant

Content of this file:
---------------------
- Installation
    - 'autosave' configuration
- IA_smallMap.png
- IA_mapData.map
- options
- parameters
- Variant-specific drawOrder functions
- IAmap-Extension


Installation:
-------------

- Copy the "interactiveMap" folder in the main directory of your webdip.


- Insert the following lines into the "jsLoadBoard" function of "board/orders/orderinterface.php":

    "libHTML::$footerIncludes[] = '../interactiveMap/javascript/interactiveMap.js';
     libHTML::$footerIncludes[] = '../interactiveMap/javascript/interactiveMapDraw.js';
     libHTML::$footerIncludes[] = '../interactiveMap/javascript/interactiveMapOrders.js';
     libHTML::$footerIncludes[] = '../interactiveMap/javascript/interactiveMapButtons.js';"

    and into the "libHTML::$footerScript[]"-String:
    "loadIA();"

    Finally the code inside your function should look like:
        "protected function jsLoadBoard() {          
		libHTML::$footerIncludes[] = 'board/model.js';
		libHTML::$footerIncludes[] = 'board/load.js';
		libHTML::$footerIncludes[] = 'orders/order.js';
		libHTML::$footerIncludes[] = 'orders/phase'.$this->phase.'.js';
		libHTML::$footerIncludes[] = '../'.libVariant::$Variant->territoriesJSONFile();
                //added
                libHTML::$footerIncludes[] = '../interactiveMap/javascript/interactiveMap.js';
                libHTML::$footerIncludes[] = '../interactiveMap/javascript/interactiveMapDraw.js';
                libHTML::$footerIncludes[] = '../interactiveMap/javascript/interactiveMapOrders.js';
                libHTML::$footerIncludes[] = '../interactiveMap/javascript/interactiveMapButtons.js';

		libHTML::$footerScript[] = '
		loadTerritories();
		loadBoardTurnData();
		loadModel();
		loadBoard();
		loadOrdersModel();
		loadOrdersForm();
		loadOrdersPhase();
                loadIA();
		';//^added
	}"


- in "css/global.css" you have to change line 44 from 
        "input[type="text"], input[type="submit"], input[type="password"], textarea, select {" to
        "input[type="text"], input[type="submit"], input[type="password"], textarea, select, .buttonIA, #orderLineIA {"
    and in "css/gamepanel.css" you have to add 
        ".buttonIA {
        font-size: 12px;
        margin-right: 2px;
        }"
    as a new class.


----'autosave' configuration----
The interface offers an autosave-function which saves the orders each time the "resetOrder"-function is called ("Reset Order"-Button or completed order) and detects an unsaved order. To deactivate the function, open "interactiveMap/javascript/interactiveMap.js" and change "interactiveMap.autosave = true;" to "interactiveMap.autosave = false;"!


IA_smallMap.png:
----------------

To detect the clicked territory, each territory must have a unique color. In the normal "smallmap.png" only the land territories are colored. To get a complete colored map as "IA_smallMap.png" the "IAgetMap.php" script checks, if such an image exists in the directory of the variant, and creates it, if it does not exist. 
As the territories are colored automatically, every territory needs to be separated by a black border. If this is not the case, the flood fill function will color two territories at once which will raise an error. Depending on the map, it could be necessary to over-work the produced map. Make sure every territory keeps its unique color.
You can use "IAgetMap.php" to create a map without using the interface by opening "IAgetMap.php?variantID=[yourVarID]" in your browser. The colored map should appear and is saved in the directory of the variant if it was not saved before.
You can use an existing map as "IA_smallMap", too, for example for a FoW-Variant where all territories are already colored. See "IAmap-Extension" for more information.


IA_mapData.map:
---------------

As javascript can not work with image-palettes (or at least I do not know how it could work), every pixel that should by greyed-out during the order-giving has to be checked manually. The fastest way to do this is using a flood fill algorithm, that needs coordinates for each seperated part of a territory. This coordinates are saved as json-file ("IA_mapData.map").
The file will be created automatically at runtime with "IAgetMapTerrDat.php".


options:
--------

It is possible to change the default-options for "scrollbars" and "greyOut", which can also be set by the user via a menu inside the interface. To do this in php just add something like this to one of the functions executed during the set-up of the order-interface (e.g. "jsLoadBoard" in "board/orders/orderinterface.php"):

    "libHTML::$footerScript[] = 'interactiveMap.options.scrollbars = '.$Boolean;
     libHTML::$footerScript[] = 'interactiveMap.options.greyOut = '.$Boolean;
     libHTML::$footerScript[] = 'interactiveMap.options.unitGreyOut = '.$Boolean;
     libHTML::$footerScript[] = 'interactiveMap.options.greyOutIntensity = '.$FloatBetween0and1;"


parameters:
-----------

The parameters-Object stores data which should be adjusted for specific variants. You could change it generally as well by editing the lines in "interactiveMap/javascript/interactiveMap.js".
Adjustable parameters are
- armyName - an alternative name for army
- fleetName - an alternative name for fleet
- imgHold - a path of the image which should represent the hold-Order
- imgMove - a path of the image which should represent the move-Order
- imgSHold, imgSMove, imgConvoy, imgDestroy, imgBuildArmy, imgBuildFleet, imgWait, imgRetreat, imgDisband

One way to achieve variant-specific changes is to create an extra JavaScript file. If you for example want to change the name of for an army to knights your JavaScript file would look like this:
    "interactiveMap.parameters.armyName = 'Knights';"

Place the file inside the html-code by appending it to the "footerIncludes"-array. This has to be done as an extension of the php orderInterface-class (example: "WWIV/classes/OrderInterface.php" (changed)):
"class IAparameters_OrderInterface extends OrderInterface 
{
        protected function jsLoadBoard()
	{
		global $Variant;

		parent::jsLoadBoard();
                
		libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/changeIAparameters.js';
	}
}"
Note that you have to define the variant-specific OrderInterface-class in the "variant.php" file, if this is not done yet (example: "WWIV/variant.php").


Variant-specific drawOrder functions:
-------------------------------------

Some variants changed the drawOrder functions of "drawMap.php". As the basic functions are rewritten in JavaScript for the interface, you have to add the changed functions manually. 
An example for the "WWIV" variant is in the download package.

- create a new JavaScript file (example: "WWIV/resources/interactiveMapDrawExtension.js")

- create a function in the following way:
"function extension(drawFunction, order, fromTerrID, toTerrID, terrID){}"

- place your code inside this function as it is directly called by the interface script. Note that toTerrID and terrID are not defined for some order types!

- place the file inside the html-code by appending it to the "footerIncludes"-array. This has to be done as an extension of the php orderInterface-class (example: "WWIV/classes/OrderInterface.php"):
"class InteractiveMapDrawExtension_OrderInterface extends OrderInterface 
{
        protected function jsLoadBoard()
	{
		global $Variant;

		parent::jsLoadBoard();
                
		libHTML::$footerIncludes[] = '../variants/'.$Variant->name.'/resources/interactiveMapDrawExtension.js';
	}
}"
Note that you have to define the variant-specific OrderInterface-class in the "variant.php" file, if this is not done yet (example: "WWIV/variant.php").


IAmap-Extension:
----------------

If you want to use a map different to "IA_smallMap.pnp" in a variant or if you want to use the data stored in the folder of a different variant or if you want to generate a colored map and the source map is different to "smallmap.png", you have to extend the IAmap-Class, which can be found in "interactiveMap/php/interactiveMap.php".

Create a php-file with the name "interactiveMap.php" inside your variant's class-folder and define a class called "[VariantName]Varian_IAmap", which extends "IAmap", inside there.

To change the used map, write something like this:

"[VariantName]Varian_IAmap extends IAmap{
    public function __construct($variant) {
        parent::__construct($variant, 'differentMap.png');
    }
}"

If you do this, the script will look for a file called "differentMap.png" which should contain the map with uniquely colored territories.


Another example:

"class ClassicVariant_IAmap extends IAmap {
    public function __construct() {
        parent::__construct(libVariant::loadFromVariantName('ClassicFog'), 'smallmap.png');
    }
}"

This class can be used for the Classic-Variant. It uses 'smallMap.png' from the ClassicFogVariant (which has to be installed and activated to do this!) as "IA_smallmap" as it already offers a map with uniquely colored land- AND sea-territories.

As every function can be extended it is also possible to make different, variant-specific extendsions which can for example use two or more maps as well. See my offered prepared variants for more examples.