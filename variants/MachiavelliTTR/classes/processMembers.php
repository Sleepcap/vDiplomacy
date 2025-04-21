<?php

class NeutralMember
{
	public $supplyCenterNo;
	public $unitNo;
}

class NeutralUnits_processMembers extends processMembers
{
	// bevore the processing add a minimal-member-object for the "neutral  player"
	function countUnitsSCs()
	{
		$this->ByCountryID[count($this->Game->Variant->countries)+1] = new NeutralMember();
		parent::countUnitsSCs();
		unset($this->ByCountryID[count($this->Game->Variant->countries)+1]);
	}
}

class MachiavelliTTRVariant_processMembers extends NeutralUnits_processMembers {}
