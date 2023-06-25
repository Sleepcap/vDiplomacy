<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class BuildAnywhere_OrderInterface extends RuleExtensionsVariant_OrderInterface_base {

	protected function jsLoadBoard() {

		if(!$this->Variant->rules[RULE_BUILD_ANYHWERE]){
			return parent::jsLoadBoard();
		}

		parent::jsLoadBoard();

		if( $this->phase=='Builds' )
		{
			// Expand the allowed SupplyCenters array to include non-home SCs.
			libHTML::$footerIncludes[] = l_jf('../variants/RuleExtensions/resources/3_BuildAnywhere/supplycenterscorrect.js');
			foreach(libHTML::$footerScript as $index=>$script)
				libHTML::$footerScript[$index]=str_replace(l_jf('loadBoard').'();',l_jf('loadBoard').'();'.l_jf('SupplyCentersCorrect').'();', $script);
		}
	}

}