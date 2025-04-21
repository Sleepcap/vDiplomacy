/*
    Copyright (C) 2004-2009 Kestas J. Kuliukas

	This file is part of webDiplomacy.

    webDiplomacy is free software: you can redistribute it and/or modify
    it under the terms of the GNU Affero General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    webDiplomacy is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU Affero General Public License
    along with webDiplomacy.  If not, see <http://www.gnu.org/licenses/>.
 */
// See doc/javascript.txt for information on JavaScript in webDiplomacy

// Current turn, -2 is undefined, -1 is pre-game
var turn=-2;

var noMoves=(useroptions.showMoves =='No'?'&hideMoves':'');
var preview='';

// Toggle the display of the Move arrows.
function toggleMoves(verify, gameID, currentTurn) {
	if (noMoves == '') {
		noMoves = '&hideMoves';
		$('NoMoves').src = "images/historyicons/showmoves.png";
	} else {
		noMoves = '';
		$('NoMoves').src = "images/historyicons/hidemoves.png";
	}
	loadMapStep(verify, gameID, currentTurn, 0)	
	loadMap(verify, gameID, currentTurn, turn)
}
// Toggle the display of the Move arrows.
function togglePreview_fog(verify, gameID, currentTurn) {
	turn = currentTurn
	if (preview == '') {
		preview = '&preview&noCache=' + Math.floor((Math.random()*10000)+1); ;
		$('Start').up().style.visibility    = 'hidden';
		$('Backward').up().style.visibility = 'hidden';
        if($('NoMoves')) { // NoMoves might not exist on the map
          $('NoMoves').up().style.visibility  = 'hidden';
        }
		$('Forward').up().style.visibility  = 'hidden';
		$('End').up().style.visibility      = 'hidden';
	} else {
		preview = '';
		$('Start').up().style.visibility    = 'visible';
		$('Backward').up().style.visibility = 'visible';
		if($('NoMoves')) {
          $('NoMoves').up().style.visibility  = 'visible';
		}
        $('Forward').up().style.visibility  = 'visible';
		$('End').up().style.visibility      = 'visible';
	}
	loadMapStep(verify,gameID, currentTurn, 0)	
	loadMap(verify, gameID, currentTurn, turn)
}

// Increment or decrement the turn safely, factoring in the limits, then load the new turn
function loadMapStep(verify, gameID, currentTurn, step)
{
	var oldTurn = turn;
	
	if( turn==-2 ) turn=currentTurn; // Initializing, display current turn
	
	turn += step;
	
	// Respect limits
	if ( turn < -1 )
		turn = -1;
	else if ( turn > currentTurn )
		turn = currentTurn;
	
	// Turn has changed
	if( oldTurn != turn )
		loadMap(verify, gameID, currentTurn, turn);
}

// Update the map arrows for the new turn, making the disabled arrows gray
function mapArrows(currentTurn, newTurn)
{
	if ( newTurn == -1 )
	{
		$('Start').src = l_s("images/historyicons/Start_disabled.png");
		$('Backward').src = l_s("images/historyicons/Backward_disabled.png");
	}
	else
	{
		$('Start').src = l_s("images/historyicons/Start.png");
		$('Backward').src = l_s("images/historyicons/Backward.png");
	}
	
	// Draw the greyed icons if the user can go no further forward
	if ( newTurn == currentTurn )
	{
		$('Forward').src = l_s("images/historyicons/Forward_disabled.png");
		$('End').src = l_s("images/historyicons/End_disabled.png");
	}
	else
	{
		$('Forward').src = l_s("images/historyicons/Forward.png");
		$('End').src = l_s("images/historyicons/End.png");
	}
}
turnToText='';//() { return ''; }

// Load the map for the specified turn, refresh arrows. Assumes newTurn is valid, sets turn=newTurn
function loadMap(verify, gameID, currentTurn, newTurn)
{
	turn=newTurn;
	
	// Draw the greyed icons if the user can go no further back
	mapArrows(currentTurn, newTurn);
	
	// Display the current date being viewed
	if( turn == currentTurn )
		$('History').hide(); // .. if viewing an old turn
	else
	{
		$('History').innerHTML = turnToText(turn);
		
		$('History').show();
	}
        
	// Add the Hide parameter if we have HideMoves activated
	newTurn = newTurn + noMoves
	
	// Add the Preview parameter if we have Preview activated
	newTurn = newTurn + preview
	
	// Add the colorCorrect Prameter if set
	if(window.colorCorrect !== undefined)
		newTurn = newTurn + colorCorrect
		
	// Add the colorCorrect Prameter if set
	if(window.showCountryNamesMap !== undefined)
		newTurn = newTurn + "&countryNames"
	
	// Update the link to the large map
	$('LargeMapLink').innerHTML = 
			'<a id="LargeMapLink" href="variants/ClassicFog/resources/fogmap.php?gameID='+gameID+'&turn='+newTurn+'&verify='+verify+'&mapType=large'
				+'" target="blank" class="light">'+'<div class="button">'
				+'<img src="images/historyicons/bigmap.png"> Big map</div> </a>';
	
	// Update the source for the map image
	$('mapImage').src = 'variants/ClassicFog/resources/fogmap.php?verify='+verify+'&gameID='+gameID+'&turn='+newTurn;
}

function recolorMap() 
{
	if ($('mapImage').complete && useroptions.colourblind != 'No' && $('mapImage').src.substring(0,4) == 'http' ) {
	        Color.Vision.Daltonize($('mapImage'),
				{'type':useroptions.colourblind,
				'callback': function (c) {$('mapImage').src = c.toDataURL();}
				});
	}
}

recolorMap();
Event.observe($('mapImage'),'load',recolorMap);

// Try to relad the map 5 times bevore showing an error.
$('mapImage').addEventListener("error", loadImgFail);
function loadImgFail()
{
	if (($('mapImage').src.match(/X/g)||[]).length < 5) 
		$('mapImage').src = $('mapImage').src + 'X';
	else
		$('mapImage').src = 'images/icons/alert.png';
}
