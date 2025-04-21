<?php

class EnglandCustomStart_IAmap extends IAmap {
        protected function jsFooterScript() {
                global $Member;
                
                parent::jsFooterScript();
                
                if( ($Member->Game->phase=='Builds') && ($Member->Game->turn==0) && ($Member->countryID == 1) )
                    libHTML::$footerScript[] = ' loadEnglandCustomStart();';
        }
}

class SpainOnlyFleets_IAmap extends EnglandCustomStart_IAmap {
		protected function jsFooterScript() {
                global $Member;
                
                parent::jsFooterScript();
                
                if( ($Member->Game->phase=='Builds') && ($Member->countryID == 3) )
                    libHTML::$footerScript[] = ' loadOnlyFleets(Array("9"));';
        }
}

class OneWay_IAmap extends SpainOnlyFleets_IAmap {
		protected function jsFooterScript() {
                global $Game;
                
                parent::jsFooterScript();
                
                if( ($Game->phase=='Diplomacy' || $Game->phase=='Retreats'))
                    libHTML::$footerScript[] = ' loadOneWay();';
        }
}

class DutchRevoltVariant_IAmap extends OneWay_IAmap {}

?>
