<?php

class Nomove_IAmap extends IAmap{

        protected function jsFooterScript() {
			global $Game,$DB;
                
			parent::jsFooterScript();

			if($Game->phase == "Diplomacy") {
				list($nomove)=$DB->sql_row("SELECT text FROM wD_Notices WHERE toUserID=3 AND timeSent=0 AND fromID=".$Game->id);
				libHTML::$footerIncludes[] = '../variants/Rinascimento/resources/nomove.js';
					
				libHTML::$footerScript[] = 'IA_nomove('.$nomove.');';
			}
        }
}

class CustomIcons_IAmap extends Nomove_IAmap{
        public function __construct($variant, $mapName = 'IA_smallmap.png') {
                parent::__construct($variant, $mapName);
                
                $this->buildButtonAutogeneration = true;
        }
        
        protected function resources() {
                return array(
                        'army'=>l_s('variants/'.$this->Variant->name.'/resources/smallarmy.png'),
                        'fleet'=>l_s('variants/'.$this->Variant->name.'/resources/smallfleet.png')
                );
        }
        
        protected function setTransparancies() {}
        
        protected function jsFooterScript() {
                libHTML::$footerScript[] = "    interactiveMap.parameters.imgBuildArmy = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Army&variantID=".$this->Variant->id."';
                                        interactiveMap.parameters.imgBuildFleet = 'interactiveMap/php/IAgetBuildIcon.php?unitType=Fleet&variantID=".$this->Variant->id."';";
        
                parent::jsFooterScript();
        }
}

class RinascimentoVariant_IAmap extends CustomIcons_IAmap{}

?>