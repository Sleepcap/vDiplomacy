<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TSRmove
 *
 * @author tobi
 */
class TSRmove {
        
        public $id;
        
        public $moveType;
        
        public $terrID;
        
        public $fromTerrID;
        
        public $toTerrID;
        
        public $tempToTerrID;
        
        public $reachedTerritory;
        
        public function __construct($id,$moveType,$terrID,$fromTerrID,$toTerrID) {
                $this->id = $id;
                $this->moveType = $moveType;
                $this->terrID = $terrID;
                $this->fromTerrID = ($moveType == 'Move')?$terrID:$fromTerrID;
                $this->toTerrID = $toTerrID;
                
                $this->tempToTerrID = $this->getTempToTerrID();
                
                $this->reachedTerritory = $toTerrID;
        }
        
        public function getTempToTerrID(){
                
                if(     ($this->moveType == 'Move' 
                                && in_array($this->terrID, ColonialVariant::$transSibTerritories))
                        || ($this->moveType == 'Support move'
                                && in_array($this->fromTerrID, ColonialVariant::$transSibTerritories)) 
                        && in_array($this->toTerrID, ColonialVariant::$transSibTerritories)){
                        
                        return $this->getNextTerrID($this->fromTerrID,$this->toTerrID);
                }else{
                        return $this->toTerrID;
                }
                
        }
        
        public function getNextTerrID($from, $to){
                $fromKey = array_search($from, ColonialVariant::$transSibTerritories);
                $toKey = array_search($to, ColonialVariant::$transSibTerritories);
                
                if($fromKey == $toKey || $fromKey == $toKey+1 || $fromKey == $toKey-1) //same or adjacent
                        return $to;
                elseif ($fromKey>$toKey) //return next adjacent on TSR-route
                        return ColonialVariant::$transSibTerritories[$fromKey-1];
                elseif ($fromKey<$toKey)
                        return ColonialVariant::$transSibTerritories[$fromKey+1];
        }
        
        public $route;
        
        public function getRoute(){
                if(!isset($this->route)){
                        $this->route = array();
                        $current = $this->fromTerrID;
                        $target = $this->toTerrID;
                        while($current != $target){
                                $current = $this->getNextTerrID($current, $target);
                                $this->route[] = $current;
                        }
                }
                return $this->route;
        }
        
        public $path;
        
        public function getTSRpath(){
                if(isset($this->path))
                        return $this->path;
                
                $route = $this->getRoute();
                
                $path = array();
                
                foreach($route as $terrID){
                        $current = array('terrID'=>$terrID);
                        
                        $current['target'] = $this->loadTargetUnit($terrID);
                        $current['preventers'] = $this->loadPreventers($terrID);
                        
                        $path[]=$current;
                }
                
                $this->path = $path;
                return $this->path;
        }
        
        public function loadTargetUnit($terrID){
                global $DB;
                
                $query = "SELECT id FROM wD_Moves WHERE terrID = ".$terrID." AND gameID=".$GLOBALS['GAMEID'];
                
                $tabl = $DB->sql_tabl($query);
		if(list($id) = $DB->tabl_row($tabl))
                        return $id;
                else
                        return NULL;       
        }
        
        public function loadPreventers($terrID){
                global $DB;
                
                $preventers = array();
                
                $query = "SELECT id FROM wD_Moves WHERE toTerrID = ".$terrID." AND moveType = 'Move' AND id != ".$this->id." AND gameID=".$GLOBALS['GAMEID'];
                
                $tabl = $DB->sql_tabl($query);
                while(list($id) = $DB->tabl_row($tabl))
                        $preventers[] = $id;
                
                return $preventers;
        }
}

?>
