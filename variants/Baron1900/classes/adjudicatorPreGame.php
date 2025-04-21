<?php

class Baron1900Variant_adjudicatorPreGame extends ClassicVariant_adjudicatorPreGame {

	protected $countryUnits = array(
                'Austria-Hungary' => array(
					'Vienna {Vie}'=>'Army', 'Trieste {Tri}'=>'Army', 'Budapest {Bud}'=>'Army'
				),	
                'Britain' => array(
					'Edinburgh {Edi}'=>'Fleet', 'London {Lon}'=>'Fleet', 'Gibraltar {Gib}'=>'Fleet', 'Egypt {Egy}'=>'Fleet'
				),
		'France' => array(
					'Brest {Bre}'=>'Fleet', 'Paris {Par}'=>'Army', 'Marseilles {Mar}'=>'Army', 'Algeria {Alg}'=>'Army'
				),
                'Germany' => array(
					'Kiel {Kie}'=>'Fleet', 'Berlin {Ber}'=>'Army', 'Munich {Mun}'=>'Army', 'Cologne {Col}'=>'Army'
				),
		'Italy' => array(
					'Milan {Mil}'=>'Army', 'Rome {Rom}'=>'Army', 'Naples {Nap}'=>'Fleet'
				),
		
		'Russia' => array(
					'Moscow {Mos}'=>'Army', 'St. Petersburg {StP} (South Coast)'=>'Fleet', 'Warsaw {War}'=>'Army', 'Sevastopol {Sev}'=>'Fleet'
				),
		'Turkey' => array(
					'Damascus {Dam}'=>'Army', 'Ankara {Ank}'=>'Fleet', 'Constantinople {Con}'=>'Army'
				)
		
		);

}
