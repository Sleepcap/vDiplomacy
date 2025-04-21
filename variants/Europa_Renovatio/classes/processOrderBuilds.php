<?php

/*
	Copyright (C) 2019 Technostar / Oliver Auth

	This file is part of the Europa Renovatio variant for webDiplomacy

	The Europa Renovatio variant for webDiplomacy is free software: you can redistribute
	it and/or modify it under the terms of the GNU Affero General Public License
	as published by the Free Software Foundation, either version 3 of the License,
	or (at your option) any later version.

	The Divided States variant for webDiplomacy is distributed in the hope that it will be
	useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
	
*/

defined('IN_CODE') or die('This script can not be run by itself.');

class BuildAnywhere_processOrderBuilds extends processOrderBuilds
{
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
	
	/** 
	 * This extension replaces the algorithm to decide which units to destroy if 
	 * valid destroy orders were missing. 
	 * Originally the unit destroy index is used to determine which unit is 
	 * furthest away from home SCs. Since there are no real home SCs in Build 
	 * Anywhere variants and it is also possible to get units into spots that 
	 * are not reachable from the original home SCs the algorithm is replaced by
	 * a simpler one that just randomly chooses to destroy units not currently 
	 * capturing a SC.
	 */
	public function apply()
	{
		global $Game, $DB;

		$DB->sql_put(
				"DELETE FROM u
				USING wD_Units AS u
				INNER JOIN wD_Orders AS o ON ( ".$Game->Variant->deCoastCompare('o.toTerrID','u.terrID')." AND u.gameID = o.gameID )
				INNER JOIN wD_Moves m ON ( m.orderID = o.id AND m.gameID=".$GLOBALS['GAMEID']." )
				WHERE o.gameID = ".$Game->id." AND o.type = 'Destroy'
					AND m.success='Yes'");

		// Remove units randomly from non-SCs for any destroy orders that weren't successful
		$tabl = $DB->sql_tabl(
					"SELECT o.id, o.countryID FROM wD_Orders o
					INNER JOIN wD_Moves m ON ( m.orderID = o.id AND m.gameID=".$GLOBALS['GAMEID']." )
					WHERE o.type = 'Destroy' AND m.success = 'No' AND o.gameID = ".$Game->id
				);
		while(list($orderID, $countryID) = $DB->tabl_row($tabl))
		{
			list($unitID, $terrID) = $DB->sql_row(
				"SELECT u.id, u.terrID FROM wD_Units u
					INNER JOIN wD_Territories t
						ON ".$Game->Variant->deCoastCompare('t.id','u.terrID')."
				WHERE u.gameID = ".$Game->id." AND u.countryID = ".$countryID."
					AND t.mapID=".$Game->Variant->mapID." AND t.supply = 'No'
				ORDER BY RAND() LIMIT 1");

			$DB->sql_put("UPDATE wD_Orders SET toTerrID = '".$terrID."' WHERE id = ".$orderID);
			$DB->sql_put("UPDATE wD_Moves
				SET success = 'Yes', toTerrID = ".$Game->Variant->deCoast($terrID)." WHERE gameID=".$GLOBALS['GAMEID']." AND orderID = ".$orderID);

			$DB->sql_put("DELETE FROM wD_Units WHERE id = ".$unitID);
		}

		$DB->sql_put("INSERT INTO wD_Units ( gameID, countryID, type, terrID )
					SELECT o.gameID, o.countryID, IF(o.type = 'Build Army','Army','Fleet') as type, o.toTerrID
					FROM wD_Orders o INNER JOIN wD_Moves m ON ( m.orderID = o.id AND m.gameID=".$GLOBALS['GAMEID']." )
					WHERE o.gameID=".$Game->id." AND o.type LIKE 'Build%' AND m.success = 'Yes'");
		// All players have the correct amount of units
	}
	
}

class CustomStart_processOrderBuilds extends BuildAnywhere_processOrderBuilds
{
	protected $countryUnits = array(

		// => array (''=>'Army', ''=>'Army', ''=>'Fleet')
		/*'Maine' => array ('Bangor (BNG)'=>'Army', 'Augusta, Maine (AUM)'=>'Army', 'Portland, Maine (PTM)'=>'Fleet'),
		'New-Hampshire' => array ('Concord (CNC)'=>'Army', 'Manchester (MAN)'=>'Army', 'Portsmouth (POM)'=>'Fleet'),
		'Vermont' => array ('Burlington (BUR)'=>'Army', 'Montpelier (MNP)'=>'Army', 'Rutland (RUT)'=>'Army'),
		'Massachusetts' => array ('Springfield, Massachusetts (SFM)'=>'Army', 'Worcester (WOR)'=>'Army', 'Boston (BOS)'=>'Fleet'),
		'Rhode-Island' => array ('Providence (PR)'=>'Army', 'Newport (NP)'=>'Fleet', 'Martha\'s Vineyard (MVN)'=>'Fleet'),
		'Connecticut' => array ('Hartford (HRT)'=>'Army', 'Waterbury (WTB)'=>'Army', 'New Haven (NHV)'=>'Fleet'),
		'New-York' => array ('Albany (ABY)'=>'Army', 'Buffalo (BUF)'=>'Army', 'Rochester, New York (RCN)'=>'Fleet', 'New York City (NYC)'=>'Fleet'),
		'New-Jersey' => array ('Newark (NWK)'=>'Army', 'Trenton (TRE)'=>'Army', 'Atlantic City (ATC)'=>'Fleet'),
		'Pennsylvania' => array ('Philadelphia (PHL)'=>'Army', 'Harrisburg (HRB)'=>'Army', 'Pittsburgh (PIT)'=>'Army', 'Erie (ERI)'=>'Fleet'),
		'Delaware' => array ('Wilmington, Delaware (WLD)'=>'Army', 'Dover (DOV)'=>'Fleet', 'Rehoboth Beach (RHB)'=>'Fleet'),
		'Maryland' => array ('Frederick (FDK)'=>'Army', 'Baltimore (BLT)'=>'Army', 'Annapolis (ANA)'=>'Fleet'),
		'Virginia' => array ('Richmond (RIC)'=>'Army', 'Roanoke (ROA)'=>'Army', 'Norfolk (NFK)'=>'Fleet'),
		'North-Carolina' => array ('Raleigh (RAL)'=>'Army', 'Charlotte (CHL)'=>'Army', 'Wilmington, North Carolina (WLN)'=>'Fleet'),
		'South-Carolina' => array ('Greenville, South Carolina (GVS)'=>'Army', 'Columbia, South Carolina (CLS)'=>'Army', 'Charleston, South Carolina (CHS)'=>'Fleet'),
		'Georgia' => array ('Atlanta (ATL)'=>'Army', 'Columbus, Georgia (CUG)'=>'Army', 'Savannah (SAV)'=>'Fleet'),
		'Florida' => array ('Tallahassee (TAL)'=>'Army', 'Orlando (ORL)'=>'Army', 'Tampa (TAM)'=>'Fleet', 'Miami (MIA)'=>'Fleet'),
		'Alabama' => array ('Montgomery (MGY)'=>'Army', 'Birmingham (BMG)'=>'Army', 'Mobile (MOB)'=>'Fleet'),
		'Mississippi' => array ('Tupelo (TUP)'=>'Army', 'Jackson, Mississippi (JKM)'=>'Army', 'Biloxi (BLX)'=>'Fleet'),
		'Tennessee' => array ('Memphis (MEM)'=>'Army', 'Chattanooga (CNG)'=>'Army', 'Nashville (NSH)'=>'Army'),
		'Kentucky' => array ('Owensboro (OWB)'=>'Army', 'Frankfort (FRK)'=>'Army', 'Louisville (LOU)'=>'Army'),
		'West-Virginia' => array ('Huntington (HNT)'=>'Army', 'Charleston, West Virginia (CHW)'=>'Army', 'Parkersburg (PKB)'=>'Army'),
		'Ohio' => array ('Cincinnati (CIN)'=>'Army', 'Columbus, Ohio (CUO)'=>'Army', 'Cleveland (CLE)'=>'Fleet'),
		'Michigan' => array ('Detroit (DET)'=>'Army', 'Lansing (LNS)'=>'Army', 'Grand Rapids (GRP)'=>'Fleet'),
		'Indiana' => array ('Fort Wayne (FWY)'=>'Army', 'Indianapolis (IND)'=>'Army', 'Evansville (EVN)'=>'Army'),
		'Illinois' => array ('Cairo (CAI)'=>'Army', 'Springfield, Illinois (SFI)'=>'Army', 'Rockford (RKF)'=>'Army', 'Chicago (CHG)'=>'Fleet'),
		'Wisconsin' => array ('Madison (MAD)'=>'Army', 'Green Bay (GRB)'=>'Army', 'Milwaukee (MWL)'=>'Fleet'),
		'Minnesota' => array ('Minneapolis (MIN)'=>'Army', 'St. Paul (STP)'=>'Army', 'Duluth (DUL)'=>'Fleet'),
		// => array (''=>'Army', ''=>'Army', ''=>'Army')
		'Iowa' => array ('Sioux City (SXC)'=>'Army', 'Davenport (DAV)'=>'Army', 'Des Moines (DSM)'=>'Army'),
		'Missouri' => array ('Kansas City (KSC)'=>'Army', 'Jefferson City (JEF)'=>'Army', 'St. Louis (SLU)'=>'Army'),
		'Arkansas' => array ('Fayetteville, Arkansas (FYA)'=>'Army', 'Jonesboro (JNB)'=>'Army', 'Little Rock (LTR)'=>'Army'),
		'Louisiana' => array ('Shreveport (SHV)'=>'Army', 'Baton Rouge (BTR)'=>'Army', 'New Orleans (NOL)'=>'Fleet'),
		'Texas' => array ('Austin (AUS)'=>'Army', 'Dallas (DAL)'=>'Army', 'Fort Worth (FTW)'=>'Army', 'El Paso (ELP)'=>'Army', 'Houston (HOU)'=>'Fleet'),
		'Oklahoma' => array ('Ardmore (ARD)'=>'Army', 'Oklahoma City (OKC)'=>'Army', 'Enid (ENI)'=>'Army'),
		'Kansas' => array ('Topeka (TPK)'=>'Army', 'Lawrence (LAW)'=>'Army', 'Hays (HAY)'=>'Army'),
		'Nebraska' => array ('Lincoln (LIN)'=>'Army', 'Omaha (OMA)'=>'Army', 'North Platte (NPL)'=>'Army'),
		'South-Dakota' => array ('Sioux Falls (SXF)'=>'Army', 'Pierre (PIE)'=>'Army', 'Rapid City (RPC)'=>'Army'),
		'North-Dakota' => array ('Fargo (FAR)'=>'Army', 'Minot (MNO)'=>'Army', 'Bismarck (BMK)'=>'Army'),
		'Montana' => array ('Missoula (MSU)'=>'Army', 'Helena (HEL)'=>'Army', 'Billings (BIL)'=>'Army'),
		'Wyoming' => array ('Yellowstone (YEL)'=>'Army', 'Casper (CSP)'=>'Army', 'Cheyenne (CYN)'=>'Army'),
		'Colorado' => array ('Denver (DEN)'=>'Army', 'Grand Junction (GJT)'=>'Army', 'Colorado Springs (COS)'=>'Army'),
		'New-Mexico' => array ('Santa Fe (STF)'=>'Army', 'Albuquerque (ABQ)'=>'Army', 'Las Cruces (LCR)'=>'Army'),
		'Arizona' => array ('Tuscon (TUS)'=>'Army', 'Phoenix (PHX)'=>'Army', 'Flagstaff (FLA)'=>'Army'),
		'Utah' => array ('St. George (STG)'=>'Army', 'Moab (MAB)'=>'Army', 'Salt Lake City (SLC)'=>'Army'),
		'Nevada' => array ('Las Vegas (LV)'=>'Army', 'Carson City (CAR)'=>'Army', 'Elko (ELK)'=>'Army'),
		'Idaho' => array ('Pocatello (POC)'=>'Army', 'Boise (BOI)'=>'Army', 'Coeur d\'Alene (CDA)'=>'Army'),
		'Washington' => array ('Spokane (SPK)'=>'Army', 'Olympia (OLY)'=>'Army', 'Seattle (SEA)'=>'Fleet'),
		'Oregon' => array ('Portland, Oregon (PTO)'=>'Army', 'Medford (MDF)'=>'Army', 'Salem, Oregon (SLO)'=>'Fleet'),
		'California' => array ('Sacramento (SCM)'=>'Army', 'Fresno (FRS)'=>'Army', 'San Diego (SDG)'=>'Army', 'San Francisco (SFO)'=>'Fleet', 'Los Angeles (LA)'=>'Fleet'),
		'Alaska' => array ('Anchorage (ANC)'=>'Army', 'Fairbanks (FBK)'=>'Army', 'Juneau (JUN)'=>'Fleet'),
		'Hawaii' => array ('Hilo (HIL)'=>'Fleet', 'Honolulu (HON)'=>'Fleet', 'Lihue (LIH)'=>'Fleet'),
		/*'Neutrals'=> array('Calgary (CAL)'=>'Army', 'Saskatoon (SKT)'=>'Army', 'Sudbury (SUD)'=>'Army', 'Gatineau (GAT)'=>'Army',
		'Fort Rupert (FRU)'=>'Army', 'Saguenay (SGU)'=>'Army', 'St. Johns (SJH)'=>'Army', 'Prince Edward Island (PEI)'=>'Army', 'Monterrey (MTE)'=>'Army',
		'Durango (DUR)'=>'Army', 'Mazatlan (MAZ)'=>'Army')*/
		//'Neutrals'=> array()
		'Aragon' => array ('Palermo (PLM)'=>'Fleet', 'Zaragoza (ZAR)'=>'Army', 'Barcelona (BRC)'=>'Army', 'Valencia (VLC)'=>'Fleet'),
		'Austria' => array ('Wien (WIE)'=>'Army', 'Graz (GRA)'=>'Army', 'Krain (KRN)'=>'Army', 'Karnten (KTN)'=>'Army'),
		'Bavaria' => array ('Munchen (MUN)'=>'Army', 'Landshut (LSH)'=>'Army', 'Regensburg (REG)'=>'Army'),
		'Bohemia' => array ('Breslau (BSL)'=>'Army', 'Hradecko (HRA)'=>'Army', 'Prague (PRA)'=>'Army'),
		'Brandenburg' => array ('Berlin (BER)'=>'Army', 'Uckermark (UCK)'=>'Army', 'Altmark (ALT)'=>'Army'),
		'Burgundy' => array ('Dijon (DIJ)'=>'Army', 'Hainaut (HAI)'=>'Army', 'Picardie (PIC)'=>'Fleet'),
		'Castille' => array ('Asturias (AST)'=>'Fleet', 'Toledo (TOL)'=>'Army', 'Madrid (MDR)'=>'Army', 'Vizcaya (VIZ)'=>'Army'), 
		'Denmark' => array ('Lund (LUN)'=>'Fleet', 'Copenhagen (COP)'=>'Fleet', 'Fyn (FYN)'=>'Army', 'Slesvig-Holstein (SVH)'=>'Army'),
		'England' => array ('York (YRK)'=>'Fleet', 'London (LON)'=>'Fleet', 'Oxford (OXF)'=>'Army', 'Normandy (NOR)'=>'Army'),
		'France' => array ('La Rochelle (LRC)'=>'Fleet', 'Paris (PRS)'=>'Army', 'Orleans (ORL)'=>'Army', 'Toulouse (TOU)'=>'Army'),
		'Genoa' => array ('Chios (CHO)'=>'Fleet', 'Caffa (CFF)'=>'Fleet', 'Corsica (COR)'=>'Fleet', 'Genoa (GEN)'=>'Army'),
		'Great-Horde' => array ('Majar (MAJ)'=>'Army', 'Astrakhan (AKH)'=>'Army', 'Saratov (SRV)'=>'Army'),
		'Hungary' => array ('Belgrade (BEL)'=>'Army', 'Budapest (BUD)'=>'Army', 'Temes (TEM)'=>'Army', 'Kiralyfold (KIR)'=>'Army'),
		'Lithuania' => array ('Yedisan (YED)'=>'Fleet', 'Vilna (VIL)'=>'Army', 'Smolensk (SMO)'=>'Army', 'Kiev (KIE)'=>'Army'),
		'Livonian-Order' => array ('Livland (LIV)'=>'Fleet', 'Dorpat (DOR)'=>'Army', 'Mitau (MTU)'=>'Army'),
		'Mamluks' => array ('Suez (SEZ) (North Coast)'=>'Fleet', 'Alexandria (ALX)'=>'Fleet', 'Jerusalem (JSM)'=>'Army', 'Cairo (CAI)'=>'Army'),
		'Milan' => array ('Milan (MIL)'=>'Army', 'Cremona (CRM)'=>'Army', 'Parma (PAR)'=>'Army'),
		'Morocco' => array ('Anfa (ANF)'=>'Fleet', 'Marrakech (MRK)'=>'Army', 'Fez (FEZ)'=>'Army'),
		'Muscovy' => array ('Kasimov (KSV)'=>'Army', 'Nizhny Novgorod (NNV)'=>'Army', 'Suzdal (SUZ)'=>'Army', 'Moskva (MSK)'=>'Army'),
		'Naples' => array ('Napoli (NAP)'=>'Fleet', 'Salento (SLT)'=>'Fleet', 'Bari (BRI)'=>'Army'),
		'Norway' => array ('Reykjavik (RYK)'=>'Fleet', 'Bergenhus (BGN)'=>'Fleet', 'Finnmark (FNM)'=>'Army', 'Oslo (OSL)'=>'Army'),
		'Novgorod' => array ('Kargopol (KGP)'=>'Army', 'Tikhvin (TKH)'=>'Army', 'Novgorod (NOV)'=>'Army'),
		'Ottomans' => array ('Kocaeli (KOC)'=>'Fleet', 'Hudavendigar (HVG)'=>'Army', 'Ankara (ANK)'=>'Army', 'Amasya (AMY)'=>'Army', 'Edirne (EDI)'=>'Army'),
		'Papacy' => array ('Ancona (ANC)'=>'Fleet', 'Umbria (UMB)'=>'Army', 'Roma (ROM)'=>'Army', 'Avignon (AVI)'=>'Army'),
		'Poland' => array ('Krakow (KRA)'=>'Army', 'Nowy Sacz (NSZ)'=>'Army', 'Lublin (LBL)'=>'Army', 'Lwow (LWO)'=>'Army'),
		'Portugal' => array ('Madiera (MAD)'=>'Fleet', 'Ceuta (CEU)'=>'Fleet', 'Lisboa (LIS)'=>'Fleet', 'Porto (POR)'=>'Army'),
		'Qara-Qoyunlu' => array ('Mosul (MOS)'=>'Army', 'Tabriz (TBZ)'=>'Army', 'Van (VAN)'=>'Army'),
		'Savoy' => array ('Wallis (WLL)'=>'Army', 'Savoie (SAV)'=>'Army', 'Vaud (VAU)'=>'Army'),
		'Saxony' => array ('Dresden (DRE)'=>'Army', 'Thuringen (THU)'=>'Army', 'Leipzig (LEI)'=>'Army'),
		'Scotland' => array ('Perth (PER)'=>'Fleet', 'Ayrshire (AYR)'=>'Fleet', 'Lothian (LTH)'=>'Army'),
		'Sweden' => array ('Kalmar (KAL)'=>'Fleet', 'Stockholm (STK)'=>'Fleet', 'Skaraborg (SKB)'=>'Army', 'Abo (ABO)'=>'Army'),
		'Switzerland' => array ('St. Gallen (SGL)'=>'Army', 'Zurich (ZUR)'=>'Army', 'Bern (BEN)'=>'Army'),
		'Teutonic-Order' => array ('Danzig (DAZ)'=>'Fleet', 'Marienburg (MRG)'=>'Army', 'Konigsberg (KOG)'=>'Army'),
		'Tlemcen' => array ('Mers el Kebir (MKB)'=>'Fleet', 'Kasdir (KSD)'=>'Army', 'Dahra (DHR)'=>'Army'),
		'Tunis' => array ('Tunis (TUN)'=>'Fleet', 'Sousse (SOS)'=>'Army', 'Tripoli (TRP)'=>'Army'),
		'Venice' => array ('Venezia (VEN)'=>'Fleet', 'Crete (CRE)'=>'Fleet', 'Treviso (TRV)'=>'Army', 'Durres (DRR)'=>'Army')
	);

	public function create()
	{
		global $DB, $Game;
		if ($Game->turn == 0) {

			$terrIDByName = array();
			$tabl = $DB->sql_tabl("SELECT id, name FROM wD_Territories WHERE mapID=".$Game->Variant->mapID);
			while(list($id, $name) = $DB->tabl_row($tabl))
				$terrIDByName[$name]=$id;

			$UnitINSERTs = array();
			foreach($this->countryUnits as $countryName => $params)
			{
				$countryID = $Game->Variant->countryID($countryName);

				foreach($params as $terrName=>$unitType)
				{
					$terrID = $terrIDByName[$terrName];
					$unitType = "Build " . $unitType;
					$UnitINSERTs[] = "(".$Game->id.", ".$countryID.", '".$terrID."', '".$unitType."')"; // ( gameID, countryID, terrID, type )
				}
			}
			$DB->sql_put(
				"INSERT INTO wD_Orders ( gameID, countryID, toTerrID, type )
				VALUES ".implode(', ', $UnitINSERTs)
			);		
		} else {
			parent::create();
		}		
	}
}

class Europa_RenovatioVariant_processOrderBuilds extends CustomStart_processOrderBuilds {}
