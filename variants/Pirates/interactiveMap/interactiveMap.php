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
class Transform_IAmap extends IAmap {

	protected function jsFooterScript() {
		global $Game;

		parent::jsFooterScript();

		if ($Game->phase == "Diplomacy")
			libHTML::$footerScript[] = 'loadIAtransform(Array("7","101","68","79","52"));';
	}

}

class PiratesVariant_IAmap extends Transform_IAmap {

	// custom icons
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

	//Resize build-icons for larger unit-images
	protected function generateBuildIcon($unitType) {
		$this->territoryPositions['0'] = array(10, 12); //position of unit on button
		//The image which stores the generated Build-Button
		$this->map = array('image' => imagecreatetruecolor(20, 20),
			'width' => 20,
			'height' => 20
		);
		imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
		$this->setTransparancy($this->map);

		$this->drawCreatedUnit(0, $unitType);

		$tempImage = $this->map['image'];

		$this->map['image'] = imagecreatetruecolor(15, 15);
		imagefill($this->map['image'], 0, 0, imagecolorallocate($this->map['image'], 255, 255, 255));
		$this->setTransparancy($this->map);

		imagecopyresized($this->map['image'], $tempImage, 0, 0, 0, 0, 15, 15, 20, 20);
		imagedestroy($tempImage);

		$this->write('variants/' . $this->Variant->name . '/interactiveMap/IA_BuildIcon_' . $unitType . '.png');

		imagedestroy($this->map['image']);
	}

	protected function jsFooterScript() {
		libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army&variantID=" . $this->Variant->id . "';
                                        interactiveMap.parameters.imgBuildFleet = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Fleet&variantID=" . $this->Variant->id . "';";

		parent::jsFooterScript();
	}

}

?>
