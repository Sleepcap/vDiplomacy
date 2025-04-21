<?php
defined('IN_CODE') or die('This script can not be run by itself.');

class GobbleEarthVariant_adjudicatorPreGame extends adjudicatorPreGame
{
	protected $countryUnits = array(
		'Austria'=> array('Budapest' => 'Army', 'Trieste' => 'Fleet', 'Vienna' => 'Army'),
		'England'=> array('London' => 'Fleet', 'Liverpool' => 'Army', 'Edinburgh' => 'Fleet', 'Accra' => 'Fleet', 'Aden' => 'Fleet', 'Cairo' => 'Fleet','Bombay' => 'Army', 'Capetown' => 'Fleet','Lagos' => 'Army','Madras' => 'Fleet', 'Quebec' => 'Fleet', 'Melbourne' => 'Fleet','Vancouver' => 'Army'),
		'France'=> array('Brest' => 'Fleet', 'Marseilles' => 'Army', 'Paris' => 'Army', 'Libreville' => 'Fleet', 'Antananarivo' => 'Fleet', 'Bamako' => 'Army', 'Hanoi' => 'Fleet', 'Tunis' => 'Army'),
		'Germany'=> array('Munich' => 'Army', 'Berlin' => 'Army', 'Kiel' => 'Fleet', 'Dar es Salaam' => 'Fleet', 'Duala' => 'Army', 'Friedrich-Wilhelmshafen' => 'Fleet', 'Windhoek' => 'Army'),
		'Italy'=> array('Naples' => 'Fleet', 'Rome' => 'Army', 'Venice' => 'Army', 'Asmara' => 'Fleet', 'Mogadishu' => 'Army', 'Tripoli' => 'Fleet'),
		'Russia'=> array('St Petersburg (South Coast)' => 'Fleet', 'Moscow' => 'Army', 'Sevastopol' => 'Fleet', 'Warsaw' => 'Army', 'Irkutsk' => 'Army', 'Samarkand' => 'Army', 'Vladivostok' => 'Fleet'),
		'Turkey'=> array('Constantinople' => 'Army', 'Ankara' => 'Fleet', 'Smyrna' => 'Army', 'Baghdad' => 'Army', 'Mecca' => 'Fleet'),
		'China'=> array('Urumtsi' => 'Army', 'Chungking' => 'Army', 'Peking' => 'Army', 'Shanghai' => 'Fleet'),
		'Japan'=> array('Seoul' => 'Army', 'Tokyo' => 'Fleet', 'Sapporo' => 'Fleet', 'Nagasaki' => 'Fleet'),
		'Brazil'=> array('Fortaleza' => 'Army', 'Rio de Janeiro' => 'Army', 'Recife' => 'Fleet'),
		'Argentina'=> array('Santa Cruz' => 'Fleet', 'Buenos Aires' => 'Army', 'Cordoba' => 'Army'),
		'Colombia'=> array('Bogota' => 'Army', 'Barranquilla' => 'Fleet'),
		'Mexico'=> array('Guadalajara' => 'Fleet', 'Merida' => 'Fleet', 'Mexico' => 'Army'),
		'USA'=> array('Honolulu' => 'Fleet', 'Miami' => 'Fleet', 'Fairbanks' => 'Fleet', 'Manila' => 'Fleet', 'Los Angeles' => 'Army', 'Washington' => 'Army')
	);
}