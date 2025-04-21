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

require_once 'variants/TenSixtySix/interactiveMap/interactiveMap.php';

class TenSixtySix_V3Variant_IAmap extends TenSixtySixVariant_IAmap {
        
        protected function jsFooterScript() {
                global $User, $DB, $Game;
                
                parent::jsFooterScript();

		list($ccode)=$DB->sql_row("SELECT text FROM wD_Notices WHERE toUserID=3 AND timeSent=0 AND fromID=".$Game->id);
                $verify=substr($ccode,((int)$Game->Members->ByUserID[$User->id]->countryID)*6,6);
                
                libHTML::$footerScript[count(libHTML::$footerScript)-1] = 'loadIA("TenSixtySix","'.$verify.'");';
        }
}

?>
