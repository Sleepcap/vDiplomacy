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
var underworld = new Array(
                'Cave of Ordeals', 'Dragons Teeth Mtns', 'Hoarluk','Mount Nimro', 'Nowwhat', 'Hidden Grotto',
		'Tymwyvenne', 'Yggdrasil', 'Khaz Modan', 'Knurremurre', 'Carpantha', 'Hollow Earth',
		'Undermountain', 'Hall of Echoes', 'Twisted Tunnels', 'Caverns of the Snow Witch',
		'Spirit Pond', 'Venatori Umbrarum', 'Ancient Necropolis', 'Temple of Doom', 'Diamond Mines'
        );

var warp_A = new Array(
                'Abby Normal', 'Allerleirauh', 'Bikini Bottom', 'Cave of Ordeals', 'High Seas', 'Thon Thalas',
		'Way the Heck', 'Ancient Necropolis', 'Hall of Echoes', 'Anvard', 'Crystal Lake', 'Immoren',
		'Cave of Ordeals (Underworld)', 'Yggdrasil'
        );
        
var warp_B = new Array(
                'Grief Reef', 'Riku', 'Mermaids Lagoon', 'Far Far Away', 'Never Never Land', 'Newa River',
		'Sea Of Fallen Stars', 'Enchanted Isles', 'Babel Beach', 'Fjord', 'Magrathea', 'Venatori Umbrarum',
		'Cathal', 'Arctic Barrens', 'Sleepy Hollow', 'Hidden Grotto'
        );

function extension(drawFunction, order, fromTerrID, toTerrID, terrID){ 
    switch (order){
        case 'destroy':
            // Add 2nd destroyed-icon for the underworld gateways:
            //if (in_array($this->territoryNames[$terrID].' (Underworld)' ,$this->territoryNames))
            //parent::drawDestroyedUnit(array_search($this->territoryNames[$terrID].' (Underworld)',$this->territoryNames));
            if(Territories.any(function(terr){return terr[1].name === Territories.get(fromTerrID).name+' (Underworld)';}))
                drawFunction(Territories.find(function(terr){return terr[1].name === Territories.get(fromTerrID).name+' (Underworld)';})[1].id);
            
            //parent::drawDestroyedUnit($terrID);
            return true;
            
        case 'move':
        case 'retreat':
        case 'supportHold':
            var adjusted = adjustArrows(fromTerrID, toTerrID);
            drawFunction(adjusted[0],adjusted[1],true);
            if(adjusted[2]!=0)
                drawFunction(adjusted[2],adjusted[3],true);
            return false;
            
        case 'supportMove':
            var adjusted = adjustArrows(fromTerrID, toTerrID, terrID);
            drawFunction(terrID, adjusted[0],adjusted[1],true);
            if(adjusted[2]!=0)
                drawFunction(adjusted[2], adjusted[3],adjusted[3],true);
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
        if(( in_array(terrName, underworld) && !in_array(fromName, underworld) && in_array(toName,underworld)) ||
            (!in_array(terrName, underworld) && in_array(fromName, underworld) && in_array(toName,underworld)))
        {
            if(in_array(terrName, underworld))
            {
                toName = toName+' (Underworld)';
                toID = Territories.find(function(terr){return terr[1].name === toName;})[1].id;
            }
            
            var terrID2 = 0;
            var toID2 = 0;
            
            if((in_array(terrName, warp_A) && in_array(toName, warp_B)) || (in_array(terrName, warp_B) && in_array(toName, warp_A)))
            {
                toID2 = toID;
                toID = Territories.find(function(terr){return terr[1].name === toName+' (2)';})[1].id;
                terrID2 = Territories.find(function(terr){return terr[1].name === terrName+' (2)';})[1].id;
            }
            
            return new Array(toID, toID, terrID2, toID2);
        }
    }
    
    if(in_array(fromName, underworld) && in_array(toName, underworld))
    {
        if(in_array(fromName+' (Underworld)', Territories.pluck(1).pluck('name')))
        {
            fromName = fromName+' (Underworld)';
            fromID = Territories.find(function(terr){return terr[1].name === fromName;})[1].id;           
        }
        if(in_array(toName+' (Underworld)', Territories.pluck(1).pluck('name')))
        {
            toName = toName+' (Underworld)';
            toID = Territories.find(function(terr){return terr[1].name === toName;})[1].id;
        }
    }
    
    var fromID2 = 0;
    var toID2 = 0;
    
    if ((in_array(fromName, warp_A) && in_array(toName, warp_B)) || (in_array(fromName, warp_B) && in_array(toName, warp_A)))
    {
	toID2 = toID;
	toID = Territories.find(function(terr){return terr[1].name === toName+' (2)';})[1].id;
	fromID2 = Territories.find(function(terr){return terr[1].name === fromName+' (2)';})[1].id;
			
	if ( terrID !=0 )
	{
            var x = Territories.get(terrID).smallMapX;
            var y = Territories.get(terrID).smallMapY;
            
            var fromx1 = Territories.get(fromID).smallMapX;
            var fromy1 = Territories.get(fromID).smallMapY;
            
            var tox1 = Territories.get(toID).smallMapX;
            var toy1 = Territories.get(toID).smallMapY;
            
            var fromx2 = Territories.get(fromID2).smallMapX;
            var fromy2 = Territories.get(fromID2).smallMapY;
            
            var tox2 = Territories.get(toID2).smallMapX;
            var toy2 = Territories.get(toID2).smallMapY;
            
            var diff1 = Math.abs(x-fromx1)+Math.abs(y-fromy1)+Math.abs(x-tox2)+Math.abs(y-toy2);
            var diff2 = Math.abs(x-fromx2)+Math.abs(y-fromy2)+Math.abs(x-tox1)+Math.abs(y-toy1);
            
            if(diff1 < diff2)
            {
                fromID = fromID2;
                toID = toID2;
            }
	}
    }		
	
    return new Array(fromID, toID, fromID2, toID2);
}

function in_array(needle, haystack){
    return haystack.any(function(e){if(typeof e==='Array') e = e[1]; return e===needle;});
}