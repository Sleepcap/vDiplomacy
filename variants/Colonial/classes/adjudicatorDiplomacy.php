<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of adjudicatorDiplomacy
 *
 * @author tobi
 */
                
class TSR_adjudicatorDiplomacy extends adjudicatorDiplomacy {
        
        private $TSRsupports = array();
        private $TSRmove;
        
        function adjudicate() {
                
                global $DB, $Game;
                require_once(l_r('variants/'.$Game->Variant->name.'/resources/adjudicatorTSR/TSRmove.php'));
                require_once(l_r('variants/'.$Game->Variant->name.'/resources/adjudicatorTSR/adjMoveTSR.php'));   
                require_once(l_r('variants/'.$Game->Variant->name.'/resources/adjudicatorTSR/adjMoveTSRhelper.php'));   
                
                /* Remove invalid support-move orders 
                 * (has to be done first to avoid, that TSR moves get wrong supporters)
                 */
			$DB->sql_put(
					"UPDATE wD_Moves supportMove
					INNER JOIN wD_Moves supportMoved
						ON ( supportMove.fromTerrID = supportMoved.terrID )
					SET supportMove.moveType = 'Hold'
					WHERE supportMove.moveType = 'Support move'
						AND ( ( NOT supportMoved.moveType = 'Move' ) OR ( NOT supportMove.toTerrID = supportMoved.toTerrID ) )
						AND supportMove.gameID = ".$GLOBALS['GAMEID']." AND supportMoved.gameID = ".$GLOBALS['GAMEID']);
                
                /*
                 * Registrate (max one, only Russian) TSR-move and TSR-supports and save them 
                 * temporarily as normal move / supportMoves
                 * so they can slip through the control-functions in adjudicator.
                 * They will be changed to special TSR-moves when they were actually loaded.
                 * (adjMove, adjSupportMove)
                 */
                $tabl = $DB->sql_tabl(  "SELECT id, moveType, terrID, fromTerrID, toTerrID
                                        FROM wD_Moves 
                                        WHERE 
                                                (
                                                        moveType = 'Move'
                                                        AND viaConvoy = 'No'
                                                        AND countryID = '6'
                                                        AND terrID IN (".implode(',',ColonialVariant::$transSibTerritories).") 
                                                        AND toTerrID IN (".implode(',',ColonialVariant::$transSibTerritories).")
                                                        AND toTerrID = fromTerrID
                                                ) OR (
                                                        moveType = 'Support move'
                                                        AND fromTerrID IN (".implode(',',ColonialVariant::$transSibTerritories).") 
                                                        AND toTerrID IN (".implode(',',ColonialVariant::$transSibTerritories).")
                                                )
                                                AND gameID = ".$GLOBALS['GAMEID']);
                
                $TSRmoved = false;
                while( list($id, $moveType, $terrID, $fromTerrID, $toTerrID) = $DB->tabl_row($tabl)){
                        if($moveType == 'Support move') 
                                $this->TSRsupports[] = new TSRmove($id,$moveType,$terrID,$fromTerrID,$toTerrID);
                        
                        
                        if(!$TSRmoved && $moveType == 'Move'){ //only one unit is allowed use the TSR at once
                                $this->TSRmove = new TSRmove($id,$moveType,$terrID,$fromTerrID,$toTerrID);
                                $TSRmoved = true;
                        }
                }
                
                /*
                 * TSRsupports should only contain the supports for the TSRmove
                 */
                if(isset($this->TSRmove))
                        foreach ($this->TSRsupports as $index => $support){
                                if($support->toTerrID != $this->TSRmove->toTerrID || $support->fromTerrID != $this->TSRmove->terrID)
                                        unset($this->TSRsupports[$index]);
                        }
                else
                        $this->TSRsupports = array();
                
                foreach ($this->TSRsupports as $TSRsupport){
                                $DB->sql_put(   "UPDATE wD_Moves
                                                        SET toTerrID = '".$TSRsupport->tempToTerrID."'
                                                        WHERE id = '".$TSRsupport->id."' AND gameID = ".$GLOBALS['GAMEID']);
                }
                
                if(isset($this->TSRmove))
                        $DB->sql_put(   "UPDATE wD_Moves
                                                        SET toTerrID = '".$this->TSRmove->tempToTerrID."'
                                                        WHERE id = '".$this->TSRmove->id."' AND gameID = ".$GLOBALS['GAMEID']);
                
                //----------------------------
                
                $standoffs =  parent::adjudicate();
                
                foreach($this->TSRsupports as $TSRsupport){
                        $DB->sql_put(   "UPDATE wD_Moves
                                                        SET toTerrID = '".$TSRsupport->toTerrID."'
                                                        WHERE id = '".$TSRsupport->id."' AND gameID = ".$GLOBALS['GAMEID']);
                }
                
                return $standoffs;
        }
        
        function adjLoadUnits(array &$units, $moveType, $objectName, $targetQuery = '', $multiTarget = false) {
                
                if($objectName == 'adjHeadToHeadMove' && isset($this->TSRmove)){
                        //HeadToHead moves do not need an extra TSR-adjucation (only Russian units can be ignored on TSR) //!!except support //handled in adjSupport
                        $targetQuery .= " AND NOT ( ( me.id = ".$this->TSRmove->id." OR target.id = ".$this->TSRmove->id." )
                                                AND me.countryID = '6' AND target.countryID = '6' ) ";
                        
                        parent::adjLoadUnits($units, $moveType, $objectName, $targetQuery, $multiTarget);

                        $this->loadTSRmove($units);

                }elseif($multiTarget == 'preventers' && isset($this->TSRunit)){
                        //exclude TSRorder
                        $targetQuery .= " AND NOT me.id = ".$this->TSRmove->id." AND NOT target.id = ".$this->TSRmove->id;
                        
                        parent::adjLoadUnits($units, $moveType, $objectName, $targetQuery, $multiTarget);
                        
                        $this->loadTSRpreventers($units);
                        
                }elseif($multiTarget == 'attackers' && isset($this->TSRunit)){
                        
                        $targetQuery .= " AND me.id != ".$this->TSRmove->id;
                        
                        parent::adjLoadUnits($units, $moveType, $objectName, $targetQuery, $multiTarget);
                  
                }elseif($moveType == 'Support move' && !$multiTarget){        
                        
                        parent::adjLoadUnits($units, $moveType, $objectName, $targetQuery, $multiTarget);
                        
                        if(!isset($this->TSRunit) && count($this->TSRsupports) > 0){
                                /*
                                 * We have TSR supporters but a HeadToHead-TSR-Move that is adjudicated as a normal move.
                                 * We have to make sure that our supporters do not support this HeadToHead-Move as TSR-attacks
                                 * are not allowed.
                                 */
                                
                                foreach($this->TSRsupports as $supporter){
                                        $units[$supporter->id]->supporting = $supporter->id; //the unit is not supporting the TSR-unit any more as the TSR does not reach its initial target
                                }
                        }
                        
                }elseif($moveType == 'Support move' && $multiTarget == 'supporters'){
                        
                        if(!isset($this->TSRunit) && count($this->TSRsupports) > 0){
                                $supportIDs = array();
                                
                                foreach($this->TSRsupports as $supporter){
                                        $supportIDs[] = $supporter->id;
                                }
                               
                                if(count($supportIDs)>0)
                                        $targetQuery .= " AND NOT me.id IN (".implode(',',$supportIDs).")";
                        }
                        
                        parent::adjLoadUnits($units, $moveType, $objectName, $targetQuery, $multiTarget);
                        
                }else{
                
                        parent::adjLoadUnits($units, $moveType, $objectName, $targetQuery, $multiTarget);
                }
        }
        
        private $TSRunit;
        function loadTSRmove(array &$units){          
                parent::adjLoadUnits($units, "Move' AND me.id = '".$this->TSRmove->id, 'adjMoveTSR');
                
                foreach($units as &$unit){
                        if($unit instanceof adjMoveTSR){
                                //gets the path of the TSRmove with all targets and preventers on the route
                                //$unit->TSRpath = $this->TSRmove->getTSRpath();
                                $this->TSRunit = $unit;
                                break;
                        }
                } 
                
                //if there is a HeadToHeadMove on the TSR it can be handled as normal move, so we do not have an extra adjMoveTSR object with helpers
                if(!isset($this->TSRunit))
                        return;
                
                $TSRpath = $this->TSRmove->getTSRpath();
                $pathLength = count($TSRpath);
                
                foreach($TSRpath as $index=>$territory){
                        if($index < $pathLength - 1){
                                //helpers get negative ids that surely will not appear in the database, so there will not be any other units having their id
                                //$id = 'TSRhelper_'.$index;
                                $id = $index * -1 - 1;
                                $unit = $this->loadTSRhelper($id);
                        }else{
                                $id = $this->TSRunit->id;
                                $unit = $this->TSRunit;
                        }
                        
                        if(isset($previous)){
                                $unit->moveTSRhelper = $previous->id; 
                                
                                $previous->nextTSRmove = $unit->id;
                        }
                        
                        //preventers and defenders for TSRmove and TSRhelper
                        //attackers and supporters are set directly for TSRmove (not available for helpers)
                        $unit->defender = $territory['target'];
                        $unit->preventers = $territory['preventers'];     
                        
                        $unit->TSRmove = $this->TSRmove;
                        
                        $units[$id] = $unit;
                        
                        $previous = $unit;
                }       
        }
        
        function loadTSRhelper($id){
                global $Game;
                
                return $Game->Variant->adjMoveTSRhelper($id, 6);
        }
        
        function loadTSRpreventers(array &$units){
                if(!isset($this->TSRunit))
                        return;
                
                //set TSRunit as preventer for all of its preventers at target territory
                foreach($this->TSRunit->preventers as $preventer){
                        $units[$preventer]->preventers[] = $this->TSRunit->id;
                        //no need to set as attacker as TSRunits never attack!
                }
                
                $index = -1;
                while(isset($units[$index])){
                        $unit = $units[$index];
                        foreach($unit->preventers as $preventer){
                                $units[$preventer]->preventers[] = $unit->id;
                        }
                        $index--;
                }
        }
}


/*
 * During the adjudication the Move-via-Suez-Order will be first set to a Convoy-Order
 * as the unit should move to a non-adjacent territory. As there won't be a Convoy-Chain
 * for a fleet, the order will be set to hold.
 * 
 * To make it a regular move again, it has to be registered before the adjudication
 * (similar to the TSR-moves) and will be changed to a normal move again, when adjLoadUnits()
 * gets called the first time ($moveType == 'Hold) 
 */
class SuezCanal_adjudicatorDiplomacy extends TSR_adjudicatorDiplomacy {
        
        protected $suezMove;
        
        function adjudicate() {
                global $DB;
                
                ini_set('memory_limit', '64M');
        
                $this->suezMove = $DB->sql_row(  "SELECT suezMove.id
                                        FROM wD_Moves suezMove
                                                INNER JOIN wD_Moves suezOrder ON (
                                                        suezOrder.terrID = 126
                                                        AND suezOrder.toTerrID = suezMove.terrID
                                                        AND suezOrder.gameID = ".$GLOBALS['GAMEID']."
                                                )
                                        WHERE                                                
                                                suezMove.moveType = 'Move'
                                                AND suezMove.toTerrID IN (99,101)
                                                AND NOT suezOrder.id IS NULL
                                                AND suezMove.gameID = ".$GLOBALS['GAMEID']);
                
                if(!isset($this->suezMove[0]))
				{
					list($suezOrder) = $DB->sql_row("SELECT moveType FROM wD_Moves
												WHERE terrID = 126
												AND gameID = ".$GLOBALS['GAMEID']);
					$DB->sql_put("UPDATE wD_Moves SET moveType = 'Hold' 
									WHERE terrID = 126 AND  gameID = ".$GLOBALS['GAMEID']);
				}
				
                $ret = parent::adjudicate();
				
                if(!isset($this->suezMove[0]))
					$DB->sql_put("UPDATE wD_Moves SET moveType = '".$suezOrder."' 
									WHERE terrID = 126 AND  gameID = ".$GLOBALS['GAMEID']);
				
				return $ret;
        }
        
        function adjLoadUnits(array &$units, $moveType, $objectName, $targetQuery = '', $multiTarget = false){ 
                global $DB;
                
                if($moveType == 'Hold') {
                        if(isset($this->suezMove[0]))
                                $DB->sql_put("UPDATE wD_Moves
                                        SET viaConvoy = 'No', success = 'Undecided'
                                        WHERE id = ".$this->suezMove[0]);
                }
                
                parent::adjLoadUnits($units, $moveType, $objectName, $targetQuery, $multiTarget);
        }
}

class ColonialVariant_adjudicatorDiplomacy extends SuezCanal_adjudicatorDiplomacy {}

?>
