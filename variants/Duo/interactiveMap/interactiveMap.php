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
class DuoVariant_IAmap extends IAmap{
        protected function jsFooterScript() {
                global $Game;
                
                parent::jsFooterScript();
                
                if($Game->phase == "Diplomacy")
                        libHTML::$footerScript[] = 'loadIAtransform();';
        }
}

?>
