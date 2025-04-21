<?php

class NeutralUnits_OrderArchiv extends OrderArchiv
{	
	public function __construct()
	{
		parent::__construct();
		$this->countryIDToName[]='Neutral units';
	}
}

class MachiavelliTTRVariant_OrderArchiv extends NeutralUnits_OrderArchiv {}
