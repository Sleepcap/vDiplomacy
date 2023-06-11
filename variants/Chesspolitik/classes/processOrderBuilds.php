<?php

defined('IN_CODE') or die('This script can not be run by itself.');

class AddHomeSCs_processOrderBuilds extends processOrderBuilds {

	public function create()
	{
		global $DB, $Game;

		$newOrders = array();
		foreach($Game->Members->ByID as $Member )
		{
			$difference = 0;
			if ( $Member->unitNo > $Member->supplyCenterNo )
			{
				$difference = $Member->unitNo - $Member->supplyCenterNo;
				$type = 'Destroy';
			}
			elseif ( $Member->unitNo < $Member->supplyCenterNo )
			{
				$difference = $Member->supplyCenterNo - $Member->unitNo;
				$type = 'Build Army';

				$addSCs = ChesspolitikVariant::countryAdditionalSCsByID($Member->countryID); 

				list($max_builds) = $DB->sql_row("SELECT COUNT(*)
					FROM wD_TerrStatus ts
					INNER JOIN wD_Territories t
						ON ( t.id = ts.terrID )
					WHERE ts.gameID = ".$Game->id."
						AND ts.countryID = ".$Member->countryID."
						AND (t.countryID = ".$Member->countryID." OR t.name IN ('".implode("','", $addSCs)."'))
						AND ts.occupyingUnitID IS NULL
						AND t.supply = 'Yes'
						AND t.mapID=".$Game->Variant->mapID);

				if ( $difference > $max_builds )
				{
					$difference = $max_builds;
				}
			}

			for( $i=0; $i < $difference; ++$i )
			{
				$newOrders[] = "(".$Game->id.", ".$Member->countryID.", '".$type."')";
			}
		}

		if ( count($newOrders) )
		{
			$DB->sql_put("INSERT INTO wD_Orders
							(gameID, countryID, type)
							VALUES ".implode(', ', $newOrders));
		}
	}
	
}

class ChesspolitikVariant_processOrderBuilds extends AddHomeSCs_processOrderBuilds {}
