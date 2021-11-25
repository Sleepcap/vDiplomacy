<?php

/*
 * 1.2.3:	Fixes of Colonial builds after code base updates
 */

defined('IN_CODE') or die('This script can not be run by itself.');

class GobbleEarthVariant extends WDVariant {
	public $id         = 96;
	public $mapID      = 96;
	public $name       = 'GobbleEarth';
	public $fullName   = 'Gobble-Earth';
	public $description= 'Global World War I';
	public $author     = 'Christian Günther-Hanssen';
	public $adapter    = 'The same aided by Oliver Auth';
	public $version    = '1';
	public $codeVersion= '1.2.3';

	public $countries=array('Austria','England','France','Germany','Italy','Russia','Turkey','China','Japan','Brazil','Argentina','Colombia','Mexico','USA');

	public function __construct() {
		parent::__construct();
		$this->variantClasses['drawMap']            = 'GobbleEarth';
		$this->variantClasses['adjudicatorPreGame'] = 'GobbleEarth';
		
		// Colonial anywhere
		$this->variantClasses['OrderInterface']     = 'GobbleEarth';		
		$this->variantClasses['processOrderBuilds'] = 'GobbleEarth';
		$this->variantClasses['userOrderBuilds']    = 'GobbleEarth';
		$this->variantClasses['adjudicatorBuilds']  = 'GobbleEarth';
		$this->variantClasses['OrderArchiv']        = 'GobbleEarth';
		
		// Zoom map:
		$this->variantClasses['panelGameBoard']     = 'GobbleEarth';
	}

	public $homeSCs = array(
		'Austria'  => array('Budapest'      , 'Trieste'    , 'Vienna'  ),
		'England'  => array('Liverpool'     , 'Edinburgh'  , 'London'  ),
		'France'   => array('Marseilles'    , 'Brest'      , 'Paris'   ),
		'Germany'  => array('Munich'        , 'Berlin'     , 'Kiel'    ),
		'Italy'    => array('Naples'        , 'Rome'       , 'Venice'  ),
		'Russia'   => array('St Petersburg' , 'Sevastopol' , 'Moscow'   , 'Warsaw'),
		'Turkey'   => array('Constantinople', 'Ankara'     , 'Smyrna'  ),
		'China'    => array('Chungking'     , 'Shanghai'   , 'Peking'  ),
		'Japan'    => array('Tokyo'         , 'Nagasaki'   , 'Sapporo' ),
		'Brazil'   => array('Rio de Janeiro', 'Fortaleza'  , 'Recife'  ),
		'Argentina'=> array('Buenos Aires'  , 'Santa Cruz' , 'Cordoba' ),
		'Colombia' => array('Barranquilla'  , 'Bogota'                 ),
		'Mexico'   => array('Guadalajara'   , 'Merida'     , 'Mexico'  ),
		'USA'      => array('Los Angeles'   , 'Washington' , 'Miami'   )
	);
	
	public function turnAsDate($turn) {
		if ( $turn==-1 ) return "Pre-game";
		else return ( $turn % 2 ? "Autumn, " : "Spring, " ).(floor($turn/2) + 1901);
	}

	public function initialize() {
		parent::initialize();
		$this->supplyCenterTarget = 37;
	}

	public function turnAsDateJS() {
		return 'function(turn) {
			if( turn==-1 ) return "Pre-game";
			else return ( turn%2 ? "Autumn, " : "Spring, " )+(Math.floor(turn/2) + 1901);
		};';
	}
}

?>