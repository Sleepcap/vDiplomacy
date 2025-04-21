<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class ClassicLayeredVariant_drawMap extends drawMap {

	protected $countryColors = array(
		0 => array(226, 198, 158), /* Neutral */
		1 => array(239, 196, 228), /* England */
		2 => array(121, 175, 198), /* France  */
		3 => array(164, 196, 153), /* Italy   */
		4 => array(160, 138, 117), /* Germany */
		5 => array(196, 143, 133), /* Austria */
		6 => array(234, 234, 175), /* Turkey  */
		7 => array(168, 126, 159)  /* Russia  */
	);
	
	public function __construct($smallmap)
	{
		// Map is too big, so up the memory-limit
		parent::__construct($smallmap);
		if ( !$this->smallmap )
			ini_set('memory_limit',"32M");
	}

	protected function resources() {
		if( $this->smallmap )
		{
			return array(
				'map'=>'variants/ClassicLayered/resources/smallmap.png',
				'army'=>'contrib/smallarmy.png',
				'fleet'=>'contrib/smallfleet.png',
				'names'=>'variants/ClassicLayered/resources/smallmapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
		else
		{
			return array(
				'map'=>'variants/ClassicLayered/resources/map.png',
				'army'=>'contrib/army.png',
				'fleet'=>'contrib/fleet.png',
				'names'=>'variants/ClassicLayered/resources/mapNames.png',
				'standoff'=>'images/icons/cross.png'
			);
		}
	}
}

?>