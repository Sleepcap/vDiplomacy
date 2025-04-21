<?php

class RiverRule_IAmap extends IAmap {
		protected function jsFooterScript() {
                global $Game;
                
                parent::jsFooterScript();
                
                if($Game->phase=='Diplomacy')
                    libHTML::$footerScript[] = ' loadNoSupportSouthAfrica();';
        }
}

class Baron1900Variant_IAmap extends RiverRule_IAmap {}

?>
