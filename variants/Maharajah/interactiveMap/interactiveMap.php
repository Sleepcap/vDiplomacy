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
class CoastConvoyOrders_IAmap extends IAmap 
{
        protected function jsFooterScript() {
                global $Variant;
                
                parent::jsFooterScript();
                
                libHTML::$footerScript[] = 'loadCoastConvoyOrders(Array("'.implode($Variant->convoyCoasts, '","').'"))';
        }
}

class MaharajahVariant_IAmap extends CoastConvoyOrders_IAmap {}

?>
