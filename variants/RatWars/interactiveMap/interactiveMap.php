<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of interactiveMap
 *
 * @author tobi
 */
class CustomIcons_IAmap extends IAmap {

	public function __construct($variant, $mapName = 'IA_smallmap.png') {
		parent::__construct($variant, $mapName);

		$this->buildButtonAutogeneration = true;
	}

	protected function resources() {
		return array(
			'army' => l_s('variants/' . $this->Variant->name . '/resources/smallarmy.png'),
			'fleet' => l_s('variants/' . $this->Variant->name . '/resources/smallfleet.png')
		);
	}

	protected function setTransparancies() {
		
	}

	protected function jsFooterScript() {
		libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army&variantID=" . $this->Variant->id . "';
                                        interactiveMap.parameters.imgBuildFleet = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Fleet&variantID=" . $this->Variant->id . "';";

		parent::jsFooterScript();
	}
	
	//Resize build-icons for larger unit-images
	protected function generateBuildIcon($unitType) {
		$this->territoryPositions['0'] = array(12, 15); //position of unit on button
		//The image which stores the generated Build-Button
		$this->map = array('image' => imagecreatetruecolor(25, 25),
			'width' => 25,
			'height' => 25
		);
		imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
		$this->setTransparancy($this->map);

		$this->drawCreatedUnit(0, $unitType);

		$tempImage = $this->map['image'];

		$this->map['image'] = imagecreatetruecolor(15, 15);
		imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
		$this->setTransparancy($this->map);

		imagecopyresized($this->map['image'], $tempImage, 0, 0, 0, 0, 15, 15, 25, 25);
		imagedestroy($tempImage);

		$this->write('variants/' . $this->Variant->name . '/interactiveMap/IA_BuildIcon_' . $unitType . '.png');

		imagedestroy($this->map['image']);
	}

}

class Fog_IAmap extends CustomIcons_IAmap {

	protected function jsFooterScript() {
		global $User, $DB, $Game;

		parent::jsFooterScript();

		list($ccode) = $DB->sql_row("SELECT text FROM wD_Notices WHERE toUserID=3 AND timeSent=0 AND fromID=" . $Game->id);
		$verify = substr($ccode, ((int) $Game->Members->ByUserID[$User->id]->countryID) * 6, 6);

		$test = libHTML::$footerScript;

		foreach (libHTML::$footerScript as $index => $script) {
			if ($script == 'loadIA();')
				libHTML::$footerScript[$index] = str_replace('loadIA();', 'loadIA("' . $this->Variant->name . '","' . $verify . '");', $script);
		}
	}

	//ignore fake-territories (Islands and Seas
	protected function getTerritoryPositions() {
		global $DB;

		$territoryPositionsSQL = "SELECT id, name, coast, smallMapX, smallMapY FROM wD_Territories WHERE mapID=" . $this->Variant->mapID;

		$territoryPositions = array();
		$tabl = $DB->sql_tabl($territoryPositionsSQL);
		while (list($terrID, $name, $coast, $x, $y) = $DB->tabl_row($tabl)) {
			if (strpos($name, ' - Islands') || strpos($name, ' - Seas'))
				continue;

			if ($coast != 'Child') {
				$territoryPositions[$terrID] = array(intval($x), intval($y));
			}
		}

		return $territoryPositions;
	}

}

class RatWarsVariant_IAmap extends Fog_IAmap {
	
}

?>
