<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class Transform_IAmap extends CustomIcons_IAmap
{
    protected function jsFooterScript() {
        global $Game;

        if(!$this->Variant->rules[RULE_TRANSFORM]){
			return parent::jsFooterScript();
		}

        parent::jsFooterScript();

        if($Game->phase == "Diplomacy") {
            libHTML::$footerIncludes[] = '../variants/RuleExtensions/interactiveMap/4_Transform/IA_transform.js';

            $resources = $this->resources();
            libHTML::$footerScript[] = 'loadIAtransform(\''.$resources['army'].'\',\''.$resources['fleet'].'\');';
        }
    }
}