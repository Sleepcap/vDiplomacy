<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class NewUnitNames_OrderArchiv extends OrderArchiv
{
	public function OutputOrder($order)
	{
		$ret=parent::OutputOrder($order);
		$ret = str_replace("army","king",$ret);
		$ret = str_replace("fleet","knight",$ret);
				
		return $ret;
	}
}

class ChesspolitikVariant_OrderArchiv extends NewUnitNames_OrderArchiv {}
