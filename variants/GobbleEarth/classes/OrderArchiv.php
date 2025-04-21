<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class GobbleEarthVariant_OrderArchiv extends OrderArchiv
{	
	public function OutputOrder($order)
	{
		if ($order['dislodged'] == 'Yes' && $order['type'] == 'wait')
			$order['dislodged'] = 'No';
			
		return parent::OutputOrder($order);	
	}
}
