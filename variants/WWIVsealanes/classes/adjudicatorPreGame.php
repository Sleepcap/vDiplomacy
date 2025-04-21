<?php
defined('IN_CODE') or die('This script can not be run by itself.');

// No initial units or occupations
class CustomStartVariant_adjudicatorPreGame extends adjudicatorPreGame
{
	protected function assignUnits() {}
	protected function assignUnitOccupations() {}
}

class WWIVsealanesVariant_adjudicatorPreGame extends CustomStartVariant_adjudicatorPreGame {}
