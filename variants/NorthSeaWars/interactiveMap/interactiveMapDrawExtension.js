/*
	Copyright (C) 2013 Tobias Florin

	This file is part of the InterActive-Map mod for webDiplomacy

	The InterActive-Map mod for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.

	The InterActive-Map mod for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.
*/
var tradeSCs = new Array(
                'grains', 'wood', 'iron','Central North Sea'
        );

function extension(drawFunction, order, fromTerrID, toTerrID, terrID){ 
    switch (order){
        case 'destroy':
            // Add 2nd destroyed-icon for the underworld gateways:
            //if (in_array($this->territoryNames[$terrID].' (Underworld)' ,$this->territoryNames))
            //parent::drawDestroyedUnit(array_search($this->territoryNames[$terrID].' (Underworld)',$this->territoryNames));
            if(Territories.any(function(terr){return terr[1].name === Territories.get(fromTerrID).name+' (2)';}))
                drawFunction(Territories.find(function(terr){return terr[1].name === Territories.get(fromTerrID).name+' (2)';})[1].id);
            
            //parent::drawDestroyedUnit($terrID);
            return true;
            
        case 'move':
        case 'retreat':
        case 'supportHold':
            var adjusted = adjustArrows(fromTerrID, toTerrID);
            drawFunction(adjusted[0],adjusted[1],true);
            return false;
            
        case 'supportMove':
            var adjusted = adjustArrows(fromTerrID, toTerrID, terrID);
            drawFunction(terrID, adjusted[0],adjusted[1],true);
            return false;
    }
    
    return true;
}
    
//documentation in drawMap.php
function adjustArrows(fromID, toID, terrID){
    terrID = (typeof terrID == 'undefined')?0:terrID;
    
    var fromName = Territories.get(fromID).name;
    var toName = Territories.get(toID).name;
    
    if(terrID > 0)
        var terrName = Territories.get(terrID).name;
    
    if(terrID != 0)
    {
        if(( in_array(terrName, tradeSCs) && !in_array(fromName, tradeSCs) && in_array(toName,tradeSCs)) ||
            (!in_array(terrName, tradeSCs) && in_array(fromName, tradeSCs) && in_array(toName,tradeSCs)))
        {
            if(in_array(terrName, tradeSCs))
            {
                toName = toName+' (2)';
                var toTerrPair = Territories.find(function(terr){return terr[1].name === toName;});
				
				if (toTerrPair != undefined)
					toID = toTerrPair[1].id;
				else
					return new Array(fromID, toID)
            }
			return new Array(toID, toID);
		}   
    }
    
    if(in_array(fromName, tradeSCs) && in_array(toName, tradeSCs))
    {
        if(in_array(fromName+' (2)', Territories.pluck(1).pluck('name')))
        {
            fromName = fromName+' (2)';
            fromID = Territories.find(function(terr){return terr[1].name === fromName;})[1].id;           
        }
        if(in_array(toName+' (2)', Territories.pluck(1).pluck('name')))
        {
            toName = toName+' (2)';
            toID = Territories.find(function(terr){return terr[1].name === toName;})[1].id;
        }
    }
    
    return new Array(fromID, toID);
}

function in_array(needle, haystack){
    return haystack.any(function(e){if(typeof e==='Array') e = e[1]; return e===needle;});
}