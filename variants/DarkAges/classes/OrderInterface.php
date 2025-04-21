<?php

defined('IN_CODE') or die('This script can not be run by itself.');


class DarkAgesVariant_OrderInterface extends OrderInterface
{
	protected function jsLoadBoard() {
		parent::jsLoadBoard();

		libHTML::$footerIncludes[] = '../variants/DarkAges/resources/iconscorrect.js';
		foreach(libHTML::$footerScript as $index=>$script)
			libHTML::$footerScript[$index]=str_replace('loadOrdersModel();','loadOrdersModel();IconsCorrect();', $script);
	}

}
