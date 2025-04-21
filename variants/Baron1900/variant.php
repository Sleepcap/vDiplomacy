<?php
/*
 * Variant Rules Copyright (C) 2003 Baron M. Powell
 * 
 * 
 * Variant Art Update Copyright (C) 2017 W. Alex Ronke 	
 * vDiplomacy / webDiplomacy adaptation Copyright (C) 2017 W. Alex Ronke
 * 
 * Implemented with permission from Baron M. Powell
 * 
 * I, W. Alex Ronke, a copyright holder for portions this work,
 * hereby publish my contributions to it under the following licenses:	
 * Creative Commons License:	https://creativecommons.org/licenses/by-sa/4.0/
 * GNU Free Documentation License:	https://en.wikipedia.org/wiki/GNU_Free_Documentation_License
 * 
 * 1.0.2: Map preview in vdip dev area fixed
 * 1.0.3: DATC customized
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class Baron1900Variant extends WDVariant {
	public $id         = 1900;
	public $mapID      = 1900;
	public $name       = 'Baron1900';
	public $fullName   = '1900';
	public $description= 'Baron VonPowell\'s 1900 is a variant that intends to capture the spirit of conventional Diplomacy while increasing both the historicity of the map and the likelihood of interaction between all seven powers.';
	public $author     = 'Baron M. Powell';
	public $adapter    = 'W. Alex Ronke';
	public $version    = '2006';
	public $codeVersion= '1.0.3';
	public $homepage   = 'http://uk.diplom.org/pouch//Online/variants/1900-061119.pdf';
	
	public $countries=array('Austria-Hungary','Britain','France','Germany','Italy','Russia','Turkey');

	public static $MidAtlanticOceanID=  40;
	public static $egyptID=             20;
	public static $hejazID=             29;
	//Mediterranean Sea Territories, that are needed for a convoy from MAO 
	//to egypt via the Mediterranean
	
	public static $mediterraneanTerritories=array(
		25, //Gibraltar
		71, //West Med
		67, //Tyrrhenian
		32, //Ionian
		19  //East Med
		); 
	
	public function __construct()
	{
		parent::__construct();
		$this->variantClasses['drawMap']            = 'Baron1900';
		$this->variantClasses['adjudicatorPreGame'] = 'Baron1900';
		$this->variantClasses['OrderInterface']     = 'Baron1900';
		$this->variantClasses['userOrderBuilds']    = 'Baron1900';
		$this->variantClasses['processOrderBuilds'] = 'Baron1900';
		$this->variantClasses['processMembers']     = 'Baron1900';
                
		/* Suez_Canal_Rule
		 * (1) A fleet may move back and forth between Egypt and Hejaz. 
                 *      (no changes needed)
		 * (2) Movement between Egypt or Hejaz and the Mid-Atlantic 
                 *      Ocean is allowed. It is assumed the unit travels around 
                 *      the southern tip of Africa. A unit that moves in this 
                 *      manner does so at half strength. This means that a unit 
                 *      adjacent Egypt or Hejaz succeeds in moving there if 
                 *      opposed only by a fleet moving from the Mid-Atlantic 
                 *      Ocean and a fleet adjacent to the Mid-Atlantic Ocean 
                 *      succeeds in moving there if opposed only by a fleet 
                 *      moving from Egypt or Hejaz.
		 * (3) A fleet in Egypt or Hejaz cannot support a unit holding 
                 *      in or moving to the Mid-Atlantic Ocean. This is true 
                 *      even though the fleet in Egypt or Hejaz can itself move 
                 *      to the Mid-Atlantic Ocean. Likewise, a fleet in the 
                 *      Mid-Atlantic Ocean cannot support a unit holding in or 
                 *      moving to Egypt or Hejaz.
		 * (4) A fleet moving from Egypt or Hejaz to the Mid-Atlantic 
                 *      Ocean does not cut support being provided by a fleet 
                 *      already in the Mid-Atlantic Ocean unless the attack 
                 *      results in F Mid-Atlantic Ocean being dislodged. The 
                 *      opposite is equally true. A fleet moving from the 
                 *      Mid-Atlantic Ocean to Egypt or Hejaz does not cut 
                 *      support being provided by a unit already in Egypt or 
                 *      Hejaz unless the attack results in the unit being 
                 *      dislodged.
		 * (5) F Mid-Atlantic Ocean can convoy an army from or to Egypt 
                 *      or Hejaz. An army convoyed from Egypt or Hejaz attacks 
                 *      its destination space at full strength. An army 
                 *      convoyed to Egypt or Hejaz attacks at half strength.
		 * (6) If two units are retreating to Egypt or Hejaz, or the 
                 *      Mid-Atlantic Ocean, and one of them must travel around 
                 *      the southern tip of Africa, the unit that does not 
                 *      travel around southern Africa may retreat while the 
                 *      other unit is disbanded.
		 */
		
		// (2), (4), (5) 
		$this->variantClasses['adjudicatorDiplomacy'] = 'Baron1900';
		
		// (2)
		$this->variantClasses['adjMove'] = 'Baron1900';
		$this->variantClasses['adjHeadToHeadMove'] = 'Baron1900';
		
		// (4)
		$this->variantClasses['adjSupportHold'] = "Baron1900";
		$this->variantClasses['adjSupportMove'] = "Baron1900";
		//handle additional paradox, that comes up with rule change (4) 
		//(comments in adjParadoxException for more information)
		$this->variantClasses['adjParadoxException'] = "Baron1900";
		
		// (3)
		//disable supports around tip of South Africa
		$this->variantClasses['userOrderDiplomacy'] = "Baron1900";
		//assert, no forbidden support goes through the adjudicator
		$this->variantClasses['adjudicatorDiplomacy'] = "Baron1900";
		//Disable supports in JS order interface
		$this->variantClasses['OrderInterface'] = 'Baron1900';
		
		// (5)
		$this->variantClasses['adjConvoyMove'] = "Baron1900";
		
		// (6)
		$this->variantClasses['adjudicatorRetreats'] = 'Baron1900';
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 18;
	}

	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1900);
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1900);
		};';
	}
}

?>