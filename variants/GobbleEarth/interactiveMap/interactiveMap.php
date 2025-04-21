<?php

class ColonialBuild_IA extends IAmap {
		protected function jsFooterScript() {
                global $Game;
                
                parent::jsFooterScript();
                
                if($Game->phase=='Builds')
                    libHTML::$footerScript[] = ' loadRenameWait();';
        }
}

class GobbleEarthVariant_IAmap extends ColonialBuild_IA {}

?>
