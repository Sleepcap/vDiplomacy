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

interactiveMap.visibleMap = new Object();
interactiveMap.visibleMap.mainLayer = new Object();
interactiveMap.visibleMap.greyOutLayer = new Object();
interactiveMap.visibleMap.selectLayer = new Object();

interactiveMap.greyOut = new Object();

/*
 * creates the canvas elements and loads the image of the map
 * replaces the image element
 */
interactiveMap.visibleMap.load = function() {
    if (interactiveMap.activated) {
        if (interactiveMap.visibleMap.mainLayer.canvasElement == null) {
			// create div container for interactive map
			interactiveMap.visibleMap.element = new Element("div", {'id': 'mapCanDiv', 'style': 'margin:0 auto;position:relative'});
            interactiveMap.visibleMap.oldMap.replace(interactiveMap.visibleMap.element);
            interactiveMap.interface.options.updateScrollbars(); //adds or removes scrollbars to visibleMap.element
				
			/* create canvas/div elements for 
			 * - mainLayer -> actual map (rel. z-Index 0)
			 * - greyOutLayer -> canvas for territory grey out (rel. z-Index 1)
			 * - selectLayer -> element for selecting territories (rel. z-Index 3)
			 * 
			 * z-Index 2 is left open so a 'deactived' order-Menu can take this place
			 * so only click events from selectLayer are fired if desired.
			 */
            var mapProps = {
				'width': interactiveMap.hiddenMap.canvasElement.width,
				'height': interactiveMap.hiddenMap.canvasElement.height, 
				'style': 'left:0px;top:0px;position:absolute;width:'+interactiveMap.hiddenMap.canvasElement.width+'px;height:'+interactiveMap.hiddenMap.canvasElement.height+'px;'
			};
			
			// main layer
			interactiveMap.visibleMap.mainLayer.canvasElement = new Element("canvas", {'id': 'mapCanvas'}).writeAttribute(mapProps).insert("<p>Your Browser does not support Canvas! You can not use InteractiveMap! Please Reload the page!</p>");
            interactiveMap.visibleMap.element.appendChild(interactiveMap.visibleMap.mainLayer.canvasElement);
			
			// grey out layer
			interactiveMap.visibleMap.greyOutLayer.canvasElement = new Element("canvas", {'id': 'mapCanvasGreyOut'}).writeAttribute(mapProps);
            interactiveMap.visibleMap.greyOutLayer.canvasElement.setStyle({
                zIndex: interactiveMap.visibleMap.mainLayer.canvasElement.style.zIndex + 1.0
            });
			interactiveMap.visibleMap.element.appendChild(interactiveMap.visibleMap.greyOutLayer.canvasElement);
			
			// select layer
			interactiveMap.visibleMap.selectLayer.element = new Element("div", {'id': 'selectLayer'}).writeAttribute(mapProps);
			interactiveMap.visibleMap.selectLayer.element.setStyle({
                zIndex: interactiveMap.visibleMap.mainLayer.canvasElement.style.zIndex + 3.0
            });
			interactiveMap.visibleMap.selectLayer.element.observe('click', interactiveMap.onClick);
			interactiveMap.visibleMap.element.appendChild(interactiveMap.visibleMap.selectLayer.element);


			// initialize canvas content
            if (interactiveMap.visibleMap.mainLayer.canvasElement.getContext) {
                interactiveMap.visibleMap.mainLayer.context = interactiveMap.visibleMap.mainLayer.canvasElement.getContext('2d');
                interactiveMap.visibleMap.greyOutLayer.context = interactiveMap.visibleMap.greyOutLayer.canvasElement.getContext('2d');

                interactiveMap.visibleMap.image = new Image();
                interactiveMap.visibleMap.image.observe('load', function() {
                    interactiveMap.draw();
                });
                interactiveMap.visibleMap.mainLayer.context.fillText("Loading ...", 1, 20);
                var link;
                if ((typeof interactiveMap.fog.variantName == 'undefined') || (typeof interactiveMap.fog.verify == 'undefined'))
                    link = 'map.php?gameID=' + context.gameID + '&turn=' + context.turn + '&hideMoves';
                else
                    link = 'variants/' + interactiveMap.fog.variantName + '/resources/fogmap.php?verify=' + interactiveMap.fog.verify + '&gameID=' + context.gameID + '&turn=' + context.turn + '&hideMoves';
                interactiveMap.visibleMap.image.src = link;

            }
        } else {
            interactiveMap.visibleMap.oldMap.replace(interactiveMap.visibleMap.element);
        }
		
		
		// Load territory positions for terrGreyOut
		if (typeof interactiveMap.TerritoryPositions == "undefined") {
			interactiveMap.greyOut.requested = false;
			
            new Ajax.Request('interactiveMap/php/IAgetMapTerrDat.php', {
                parameters: {"gameID": context.gameID},
                onSuccess: function(response) {
                    interactiveMap.TerritoryPositions = response.responseJSON;
					if(interactiveMap.greyOut.requested) // initiate greyOut by calling resetOrder when grey out was called at least one time during loading.
						if(typeof interactiveMap.TerritoryPositions !== "undefined") 
							interactiveMap.resetOrder();
                },
                onFailure: function() {
                    var alertWindow = window.open('interactiveMap/php/IAgetMapTerrDat.php?gameID=' + context.gameID, '', 'height=100, width=500, scrollbars=yes');
                    alertWindow.focus();
                }
            });
            return;
    }
		
    } else {
        interactiveMap.visibleMap.element.replace(interactiveMap.visibleMap.oldMap);
    }
};

/*
 * draws the image with entered orders (all what is set in MyOrders)
 */
interactiveMap.draw = function() {
    function drawRetreatUnit(terrID, type, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawRetreatUnit, 'drawRetreatUnit', terrID);
        }

        if (complete) {
            var position = {x: terrTable[terrID].smallMapX, y: terrTable[terrID].smallMapY};

            interactiveMap.visibleMap.mainLayer.context.fillStyle = "rgb(255,255,255)";
            interactiveMap.visibleMap.mainLayer.context.fillRect(position.x - 5, position.y - 8, 7, 8);

            interactiveMap.visibleMap.mainLayer.context.fillStyle = "rgb(255,0,0)";
            if (type == "Fleet") {
                interactiveMap.visibleMap.mainLayer.context.fillText("F", position.x - 5, position.y);
            } else {
                interactiveMap.visibleMap.mainLayer.context.fillText("A", position.x - 5, position.y);
            }
        }
    }

    function drawMove(fromTerrID, toTerrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawMove, 'move', fromTerrID, toTerrID);
        }

        if (complete) {
            var start = {x: terrTable[fromTerrID].smallMapX, y: terrTable[fromTerrID].smallMapY};
            var end = {x: terrTable[toTerrID].smallMapX, y: terrTable[toTerrID].smallMapY};
            drawOrderArrow(start, end, 'Move');
        }
    }

    function drawFakedMove(fromTerrID, toTerrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawFakedMove, 'move', fromTerrID, toTerrID);
        }

        if (complete) {
            var start = {x: terrTable[fromTerrID].smallMapX, y: terrTable[fromTerrID].smallMapY};
            var end = {x: terrTable[toTerrID].smallMapX, y: terrTable[toTerrID].smallMapY};
            drawOrderArrow(start, end, 'fakedMove');
        }
    }

    function drawSupportHold(fromTerrID, toTerrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if (typeof terrTable[toTerrID].Unit !== 'undefined')
            toTerrID = terrTable[toTerrID].Unit.terrID;   //for units on coasts

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawSupportHold, 'supportHold', fromTerrID, toTerrID);
        }

        if (complete) {
            var start = {x: terrTable[fromTerrID].smallMapX, y: terrTable[fromTerrID].smallMapY};
            var end = {x: terrTable[toTerrID].smallMapX, y: terrTable[toTerrID].smallMapY};
            drawOrderArrow(start, end, 'Support hold');
        }
    }

    function drawRetreat(fromTerrID, toTerrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawRetreat, 'retreat', fromTerrID, toTerrID);
        }

        if (complete) {
            var start = {x: terrTable[fromTerrID].smallMapX, y: terrTable[fromTerrID].smallMapY};
            var end = {x: terrTable[toTerrID].smallMapX, y: terrTable[toTerrID].smallMapY};
            drawOrderArrow(start, end, 'Retreat');
        }
    }



    /*The following is translated (partly) from drawMap.php
     * 
     * first the function is shown as it is written in drawMap.php, then the translated
     */

    /*public function drawSupportMove($terrID, $fromTerrID, $toTerrID, $success)
     {
     if ( $this->smallmap and !$success ) return;
     
     // Our toX and toY are 1/3 of the way between the two territories
     list($fromX, $fromY) = $this->territoryPositions[$fromTerrID];
     list($toX, $toY) = $this->territoryPositions[$toTerrID];
     
     $toX -= ( $toX - $fromX ) / 3;
     $toY -= ( $toY - $fromY ) / 3;
     
     list($fromX, $fromY) = $this->territoryPositions[$terrID];
     
     $this->drawOrderArrow(array($fromX, $fromY), array($toX, $toY), 'Support move');
     
     if ( !$success ) $this->drawFailure(array($fromX, $fromY), array($toX, $toY));
     }*/

    function drawSupportMove(terrID, fromTerrID, toTerrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if (typeof terrTable[fromTerrID].Unit !== 'undefined') {
            var suppUnit = terrTable[fromTerrID].Unit;    //the supported Unit
            fromTerrID = suppUnit.terrID;   //for units on coasts
            if ((suppUnit.countryID == context.countryID) && ((suppUnit.Order.type == 'Move') && suppUnit.Order.isComplete)) {     //the following code would cause problems with foreign units as their order is not known
                var toTerrIDnew = suppUnit.Order.toTerrID;   //for units on coasts
                if ((toTerrIDnew == toTerrID) || (terrTable[toTerrIDnew].coastParentID == toTerrID)) {        //checks, if different ID is only caused by a coast
                    toTerrID = toTerrIDnew;
                }
            }
        }

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawSupportMove, 'supportMove', fromTerrID, toTerrID, terrID);
        }

        if (complete) {
            var fromX = terrTable[fromTerrID].smallMapX;
            var fromY = terrTable[fromTerrID].smallMapY;
            var toX = terrTable[toTerrID].smallMapX;
            var toY = terrTable[toTerrID].smallMapY;

            toX -= (toX - fromX) / 3;
            toY -= (toY - fromY) / 3;

            var start = {x: terrTable[terrID].smallMapX, y: terrTable[terrID].smallMapY};
            var end = {x: toX, y: toY};
            drawOrderArrow(start, end, 'Support move');
        }
    }

    /*public function drawConvoy($terrID, $fromTerrID, $toTerrID, $success)
     {
     if ( $this->smallmap and !$success ) return;
     
     // Our toX and toY are 1/3 of the way between the two territories
     list($fromX, $fromY) = $this->territoryPositions[$fromTerrID];
     list($toX, $toY) = $this->territoryPositions[$toTerrID];
     
     $toX -= ( $toX - $fromX ) / 3;
     $toY -= ( $toY - $fromY ) / 3;
     
     list($fromX, $fromY) = $this->territoryPositions[$terrID];
     
     $this->drawOrderArrow(array($fromX, $fromY), array($toX, $toY), 'Convoy');
     
     if ( !$success ) $this->drawFailure(array($fromX, $fromY), array($toX, $toY));
     }*/

    function drawConvoy(terrID, fromTerrID, toTerrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawConvoy, 'convoy', fromTerrID, toTerrID, terrID);
        }

        if (complete) {
            var fromX = terrTable[fromTerrID].smallMapX;
            var fromY = terrTable[fromTerrID].smallMapY;
            var toX = terrTable[toTerrID].smallMapX;
            var toY = terrTable[toTerrID].smallMapY;

            toX -= (toX - fromX) / 3;
            toY -= (toY - fromY) / 3;

            var start = {x: terrTable[terrID].smallMapX, y: terrTable[terrID].smallMapY};
            var end = {x: toX, y: toY};
            drawOrderArrow(start, end, 'Convoy');
        }
    }


    var orderArrows = {
        //array(0, 153, 2)
        'Move': {'color': new Array(196, 32, 0), //0, 153, 2),//
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.1,
            'headLength': new Array(12, 30),
            'border': new Array(0, 0)
        },
        'fakedMove': {'color': new Array(100, 100, 100),
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.1,
            'headLength': new Array(12, 30),
            'border': new Array(0, 0)
        },
        'Support hold': {'color': new Array(67, 206, 16),
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 2,
            'headStart': 0.2,
            'headLength': new Array(8, 24),
            'border': new Array(0, 0)
        },
        'Support move': {'color': new Array(249, 249, 47),
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.4,
            'headLength': new Array(12, 30),
            'border': new Array(0, 0)
        },
        'Convoy': {'color': new Array(4, 113, 160),
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.1,
            'headLength': new Array(0, 0),
            'border': new Array(0, 0)
        },
        'Retreat': {'color': new Array(198, 39, 159),
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.1,
            'headLength': new Array(12, 30),
            'border': new Array(0, 0)
        }
    };


    /*protected function drawOrderArrow(array $start, array $end, $moveType )
     {
     list($startX, $startY) = $start;
     list($endX, $endY) = $end;
     
     $this->updateBoundaries($startX, $startY);
     $this->updateBoundaries($endX, $endY);
     
     $params = &$this->orderArrows[$moveType];
     
     $borderColor = $this->colors['border'];
     
     // Some orders want to start the head further up the arrow ( support move)
     $headStartX = $endX-($endX-$startX)*$params['headStart'];
     $headStartY = $endY-($endY-$startY)*$params['headStart'];
     
     // Get the angle that the order arrow is pointing in, so that the head can be added that way
     $rad = $this->lineAngle($start, $end);
     
     /*
     * Now we construct the head facing to the right, so that it can be
     * aligned afterwards
     /
     $head = array(
     0,0,
     -1*$params['headLength'] * cos($params['headAngle']), -1*$params['headLength'] * sin($params['headAngle']),
     -1*$params['headLength'] * cos($params['headAngle']), $params['headLength'] * sin($params['headAngle']),
     );
     $head = self::rotate($head, array(0,0), $rad);
     
     // Rotation done, now move it into place
     for($i=0; $i<6; $i+=2)
     {
     $head[$i] += $headStartX;
     $head[$i+1] += $headStartY;
     }
     
     if ( $params['border'] != 0 )
     {
     // Borders are drawn first, so they don't overlap, and are made a little longer
     self::imagelinethick($this->map['image'], $startX, $startY, $endX, $endY, $borderColor, $params['border']);
     self::imagelinethick($this->map['image'], $head[2], $head[3], $headStartX, $headStartY, $borderColor, $params['border']);
     self::imagelinethick($this->map['image'], $head[4], $head[5], $headStartX, $headStartY, $borderColor, $params['border']);
     }
     
     self::imagelinethick($this->map['image'], $startX, $startY, $endX, $endY, $params['color'], $params['thickness']);
     self::imagelinethick($this->map['image'], $head[2], $head[3], $headStartX, $headStartY, $params['color'], $params['thickness']);
     self::imagelinethick($this->map['image'], $head[4], $head[5], $headStartX, $headStartY, $params['color'], $params['thickness']);
     }*/

    function drawOrderArrow(start, end, moveType) {
        var startX = start.x;
        var startY = start.y;
        var endX = end.x;
        var endY = end.y;
        var params = orderArrows[moveType];

        var headStartX = endX - (endX - startX) * params['headStart'];
        var headStartY = endY - (endY - startY) * params['headStart'];

        var rad = lineAngle(start, end);

        var head = new Array(
                0, 0,
                -1 * params['headLength'][0] * Math.cos(params['headAngle']), -1 * params['headLength'][0] * Math.sin(params['headAngle']),
                -1 * params['headLength'][0] * Math.cos(params['headAngle']), params['headLength'][0] * Math.sin(params['headAngle'])
                );

        head = rotate(head, new Array(0, 0), rad);

        for (var i = 0; i < 6; i += 2) {
            head[i] += headStartX;
            head[i + 1] += headStartY;
        }

        imageLine(startX, startY, endX, endY, params['color'], params['thickness']);
        imageLine(head[2], head[3], headStartX, headStartY, params['color'], params['thickness']);
        imageLine(head[4], head[5], headStartX, headStartY, params['color'], params['thickness']);
    }

    /*protected static function rotate(array $coordinates, array $rotateAround, $rotateBy)
     {
     /*
     * y gets lower as it goes up, which can cause confusion
     /
     $transformMatrix = array(
     array(cos($rotateBy),-1*sin($rotateBy)),
     array(sin($rotateBy),cos($rotateBy))
     );
     
     $coordNum = count($coordinates);
     for($i=0; $i<$coordNum; $i+=2)
     {
     $x = $coordinates[$i];
     $y = $coordinates[$i+1];
     
     $x -= $rotateAround[0];
     $y -= $rotateAround[1];
     
     $newX = $x*$transformMatrix[0][0] + $y*$transformMatrix[0][1];
     $y = $x*$transformMatrix[1][0] + $y*$transformMatrix[1][1];
     $x = $newX;
     
     $x += $rotateAround[0];
     $y += $rotateAround[1];
     
     $coordinates[$i] = $x;
     $coordinates[$i+1] = $y;
     }
     
     return $coordinates;
     }*/

    function rotate(coordinates, rotateAround, rotateBy) {
        var transformMatrix = new Array(
                new Array(Math.cos(rotateBy), -1 * Math.sin(rotateBy)),
                new Array(Math.sin(rotateBy), Math.cos(rotateBy))
                );
        var coordNum = coordinates.length;
        for (var i = 0; i < coordNum; i += 2) {
            var x = coordinates[i];
            var y = coordinates[i + 1];

            x -= rotateAround[0];
            y -= rotateAround[1];

            var newX = x * transformMatrix[0][0] + y * transformMatrix[0][1];
            y = x * transformMatrix[1][0] + y * transformMatrix[1][1];
            x = newX;

            x += rotateAround[0];
            y += rotateAround[1];

            coordinates[i] = x;
            coordinates[i + 1] = y;
        }
        return coordinates;
    }

    /*protected static function lineAngle(array $from, array $to)
     {
     $xdiff = $from[0] - $to[0];
     // xdiff > 0 = from > to = to on the left of from = rad between +/- PI and +/-1/2*PI
     
     $ydiff = $from[1] - $to[1];
     // ydiff > 0 = from > to = to above from = rad between -PI and 0
     
     if ( $ydiff > 0 ) // The radians angle is negative. The arrow points up
     {
     if ( $xdiff == 0 ) // Don't let this cause a div by zero error
     {
     return (-1/2)*M_PI;
     }
     elseif ( $xdiff > 0 )
     {
     /*
     * The arrow points left; the radians angle is between -PI and -(1/2)*PI
     /
     
     return ( M_PI + abs(atan(abs($ydiff/$xdiff))) );
     }
     else
     {
     // The radians angle is between 0 and -PI
     return -1*abs(atan(abs($ydiff/$xdiff)));
     }
     }
     else
     {
     // The value is positive
     if ( $xdiff == 0 )
     {
     return M_PI/2;
     }
     elseif ( $xdiff > 0 )
     {
     // The arrow points left; the radians angle is between PI and 2*PI
     return ( M_PI - abs(atan(abs($ydiff/$xdiff))) );
     }
     else
     {
     // The radians angle is between 0 and PI
     return abs(atan(abs($ydiff/$xdiff)));
     }
     }
     }*/

    function lineAngle(from, to) {
        var xdiff = from.x - to.x;
        var ydiff = from.y - to.y;

        if (ydiff > 0) {
            if (xdiff == 0) {
                return ((-1 / 2) * Math.PI);
            } else if (xdiff > 0) {
                return (Math.PI + Math.abs(Math.atan(Math.abs(ydiff / xdiff))));
            } else {
                return (-1 * Math.abs(Math.atan(Math.abs(ydiff / xdiff))));
            }
        } else {
            if (xdiff == 0) {
                return (Math.PI / 2);
            } else if (xdiff > 0) {
                return (Math.PI - Math.abs(Math.atan(Math.abs(ydiff / xdiff))));
            } else {
                return (Math.abs(Math.atan(Math.abs(ydiff / xdiff))));
            }
        }
    }


    /*protected static function imagelinethick($image, $x1, $y1, $x2, $y2, $color, $thick = 1)
     {
     imagesetthickness($image, $thick);
     imageline($image, $x1, $y1, $x2, $y2, $color);
     imagesetthickness($image, 1);
     }*/

    function imageLine(x1, y1, x2, y2, color, thick) {
        thick = (typeof thick !== 'undefined') ? thick[0] : 2;

        interactiveMap.visibleMap.mainLayer.context.beginPath();
        interactiveMap.visibleMap.mainLayer.context.moveTo(x1, y1);
        interactiveMap.visibleMap.mainLayer.context.lineTo(x2, y2);
        interactiveMap.visibleMap.mainLayer.context.strokeStyle = "rgb(" + color[0] + "," + color[1] + "," + color[2] + ")";
        interactiveMap.visibleMap.mainLayer.context.lineWidth = thick;
        interactiveMap.visibleMap.mainLayer.context.stroke();
    }

    /**
     * Return an array of coordinates for an n sided symmetrical polygon, which has 0,0 at its center.
     *
     * @param int $corners The number of corners in the polygon
     * @param int $outerRadius The distance to draw the outer-most points
     * @param int[optional] $innerRadius The option to draw half the points at a the given radius
     *
     * @return array x,y,x,y matrix of polygon coordinates
     *
     protected static function polygonMap($corners, $outerRadius, $innerRadius=false)
     {
     $coords = array();
     
     for($i=0;$i<$corners;$i++)
     {
     $coords[]=0; // x coord is 0
     
     if ( $i%2 != 0 and $innerRadius != false )
     $coords[]=$innerRadius;
     else
     $coords[]=$outerRadius;
     
     $coords = self::rotate($coords, array(0,0), (2*M_PI/$corners));
     }
     
     return $coords;
     }*/

    function polygonMap(corners, outerRadius, innerRadius) {
        innerRadius = (typeof innerRadius !== 'undefined') ? innerRadius : false;

        var coords = new Array();

        for (var i = 0; i < corners; i++) {
            coords.push(0);

            if ((i % 2 != 0) && (innerRadius != false)) {
                coords.push(innerRadius);
            } else {
                coords.push(outerRadius);
            }

            coords = rotate(coords, new Array(0, 0), (2 * Math.PI / corners));
        }

        return coords;
    }

    /**
     * Draws an orange explosion where a unit has been destroyed, intended to be drawn on-top of the unit
     *
     * @param string $terrID The territory to draw the destruction at
     */
    /*public function drawDestroyedUnit($terrID)
     {
     $size = $this->army['width']/2;
     
     if( !isset($this->destroyedPolygon) )
     {
     $blackInner = self::polygonMap(14, $size, $size/2);
     $blackOuter = self::polygonMap(14, ($size-1), ($size-1)/2);
     
     $inner = self::polygonMap(14, ($size-2), ($size-2)/2);
     $outer = self::polygonMap(14, ($size-3), ($size-3)/2);
     
     $outer = self::rotate($outer, array(0,0), M_PI/7);
     $blackOuter = self::rotate($blackOuter, array(0,0), M_PI/7);
     
     $this->destroyedPolygon=array(
     'blackInner'=>$blackInner,
     'blackOuter'=>$blackOuter,
     'inner'=>$inner,
     'outer'=>$outer,
     );
     }
     
     $position = $this->territoryPositions[$terrID];
     
     $this->drawPolygon($position, array(0,0,0), $this->destroyedPolygon['blackInner']);
     $this->drawPolygon($position, array(0,0,0), $this->destroyedPolygon['blackOuter']);
     $this->drawPolygon($position, array(255,100,0), $this->destroyedPolygon['inner']);
     $this->drawPolygon($position, array(255,0,0), $this->destroyedPolygon['outer']);
     }
     private $destroyedPolygon;*/

    var destroyedPolygon;
    function drawDestroyedUnit(terrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawDestroyedUnit, 'destroy', terrID);
        }

        if (complete) {
            var size = 7.5;    //army width (15) / 2

            if (typeof destroyedPolygon === 'undefined') {
                var blackInner = polygonMap(14, size, (size / 2));
                var blackOuter = polygonMap(14, (size - 1), ((size - 1) / 2));

                var inner = polygonMap(14, (size - 2), ((size - 2) / 2));
                var outer = polygonMap(14, (size - 3), ((size - 3) / 2));

                outer = rotate(outer, new Array(0, 0), (Math.PI / 7));
                blackOuter = rotate(blackOuter, new Array(0, 0), (Math.PI / 7));

                destroyedPolygon = {
                    'blackInner': blackInner,
                    'blackOuter': blackOuter,
                    'inner': inner,
                    'outer': outer};
            }

            var position = {x: terrTable[terrID].smallMapX, y: terrTable[terrID].smallMapY};

            drawPolygon(position, new Array(0, 0, 0), Object.create(destroyedPolygon['blackInner']));
            drawPolygon(position, new Array(0, 0, 0), Object.create(destroyedPolygon['blackOuter']));
            drawPolygon(position, new Array(255, 100, 0), Object.create(destroyedPolygon['inner']));
            drawPolygon(position, new Array(255, 0, 0), Object.create(destroyedPolygon['outer']));
        }
    }



    /**
     * Draws a small orange explosion where a unit has been dislodged, drawn to the upper right of the unit
     * which took the place of the dislodged unit
     *
     * @param string $terrID The territory to draw the dislodgement marker at
     */
    /*public function drawDislodgedUnit($terrID)
     {
     if ( $this->smallmap ) return;
     
     if( !isset($this->dislodgedPolygon) )
     {
     $size = $this->army['height']/2;
     
     $blackInner = self::polygonMap(10, $size, $size/2);
     $blackOuter = self::polygonMap(10, ($size-2), ($size-2)/2);
     
     $inner = self::polygonMap(10, ($size-3), ($size-3)/2);
     $outer = self::polygonMap(10, ($size-4), ($size-4)/2);
     
     $outer = self::rotate($outer, array(0,0), M_PI/5);
     $blackOuter = self::rotate($blackOuter, array(0,0), M_PI/5);
     
     $this->dislodgedPolygon=array(
     'blackInner'=>$blackInner,
     'blackOuter'=>$blackOuter,
     'inner'=>$inner,
     'outer'=>$outer,
     );
     }
     
     $position = $this->territoryPositions[$terrID];
     
     $this->drawPolygon($position, array(0,0,0), $this->dislodgedPolygon['blackInner'], true);
     $this->drawPolygon($position, array(0,0,0), $this->dislodgedPolygon['blackOuter'], true);
     $this->drawPolygon($position, array(255,150,0), $this->dislodgedPolygon['inner'], true);
     $this->drawPolygon($position, array(255,80,0), $this->dislodgedPolygon['outer'], true);
     }
     private $dislodgedPolygon;*/

    var dislodgedPolygon;
    function drawDislodgedUnit(terrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawDislodgedUnit, 'dislodge', terrID);
        }

        if (complete) {
            var size = 4.5;    //army hight (9) / 2

            if (typeof dislodgedPolygon === 'undefined') {
                var blackInner = polygonMap(10, size, (size / 2));
                var blackOuter = polygonMap(10, (size - 2), ((size - 2) / 2));

                var inner = polygonMap(10, (size - 3), ((size - 3) / 2));
                var outer = polygonMap(10, (size - 4), ((size - 4) / 2));

                outer = rotate(outer, new Array(0, 0), (Math.PI / 5));
                blackOuter = rotate(blackOuter, new Array(0, 0), (Math.PI / 5));

                dislodgedPolygon = {
                    'blackInner': blackInner,
                    'blackOuter': blackOuter,
                    'inner': inner,
                    'outer': outer};
            }

            var position = {x: terrTable[terrID].smallMapX, y: terrTable[terrID].smallMapY};

            drawPolygon(position, new Array(0, 0, 0), Object.create(dislodgedPolygon['blackInner']), true);
            drawPolygon(position, new Array(0, 0, 0), Object.create(dislodgedPolygon['blackOuter']), true);
            drawPolygon(position, new Array(255, 150, 0), Object.create(dislodgedPolygon['inner']), true);
            drawPolygon(position, new Array(255, 80, 0), Object.create(dislodgedPolygon['outer']), true);
        }
    }

    /**
     * Draw a polygon to a certain color
     * @param array $position x,y Coordinate of the center of the polygon
     * @param array $color The color to use for the polygon
     * @param array $polygon The x,y,x,y coordinate matrix for the polygon
     * @param bool[optional] $small True if drawing onto small map
     */
    /*protected function drawPolygon(array $position, array $color, array $polygon, $small=false)
     {
     list($x,$y) = $position;
     
     $corners = count($polygon) / 2;
     
     if ( $small )
     {
     $x += $this->army['width']/2;
     $y -= $this->army['height']/2;
     }
     
     for($i=0; $i<$corners*2; $i+=2)
     {
     $polygon[$i]+=$x;
     $polygon[$i+1]+=$y;
     }
     
     $color = $this->color($color);
     imagefilledpolygon($this->map['image'], $polygon, $corners, $color);
     }*/

    function drawPolygon(position, color, polygon, small) {
        small = (typeof small !== 'undefined') ? small : false;

        var x = new Number(position.x); //position.x was type String, which caused problems later
        var y = new Number(position.y);

        var corners = polygon.length;

        if (small) {
            x += 7.5;    //army width (15) / 2
            y -= 4.5;     //army height (9) / 2
        }

        for (var i = 0; i < corners * 2; i += 2) {
            polygon[i] += x;
            polygon[i + 1] += y;
        }

        interactiveMap.visibleMap.mainLayer.context.beginPath();
        interactiveMap.visibleMap.mainLayer.context.moveTo(polygon[0], polygon[1]);
        for (var i = 2; i < polygon.length; i += 2) {
            interactiveMap.visibleMap.mainLayer.context.lineTo(polygon[i], polygon[i + 1]);
        }
        interactiveMap.visibleMap.mainLayer.context.closePath();
        interactiveMap.visibleMap.mainLayer.context.fillStyle = "rgb(" + color[0] + "," + color[1] + "," + color[2] + ")";
        interactiveMap.visibleMap.mainLayer.context.fill();
    }

    /**
     * Draws a small star where a unit has been created, drawn to the upper right of the new unit
     *
     * @param string $terrID The territory to draw the creation marker at
     */
    /*public function drawCreatedUnit($terrID, $unitType)
     {
     $position = $this->territoryPositions[$terrID];
     
     $size = $this->army['height']/2;
     
     $blackStar = self::polygonMap(10, $size, $size/2);
     $blackStar = self::rotate($blackStar, array(0,0), M_PI);
     
     // Flip them the right way up
     $whiteStar = self::polygonMap(10, ($size-2), $size/2-1);
     $whiteStar = self::rotate($whiteStar, array(0,0), M_PI);
     
     
     // The outer black border star
     $this->drawPolygon($position, array(0,0,0), $blackStar, true);
     // The inner white star
     $this->drawPolygon($position, array(255,255,0), $whiteStar, true);
     
     $this->addUnit($terrID, $unitType);
     }*/
    function drawCreateUnit(terrID, type, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawCreateUnit, 'build', terrID);
        }

        if (complete) {
            var position = {x: terrTable[terrID].smallMapX, y: terrTable[terrID].smallMapY};

            var size = 7.5;    //army width (15) / 2

            var blackStar = polygonMap(10, size - 2, (size / 2));
            blackStar = rotate(blackStar, new Array(0, 0), Math.PI);

            var whiteStar = polygonMap(10, (size - 2), (size / 2 - 1));
            whiteStar = rotate(whiteStar, new Array(0, 0), Math.PI);

            drawPolygon(position, new Array(0, 0, 0), blackStar, true);

            drawPolygon(position, new Array(255, 255, 0), whiteStar, true);

            interactiveMap.visibleMap.mainLayer.context.fillStyle = "rgb(255,0,0)";
            if (type == "Build Fleet") {
                interactiveMap.visibleMap.mainLayer.context.fillText("F", position.x - 5, position.y);
            } else {
                interactiveMap.visibleMap.mainLayer.context.fillText("A", position.x - 5, position.y);
            }
        }
    }
    
    /*public function drawRedBox()
	{
		$red=$this->color(array(240,20,20),$this->map['image']);
		self::imagelinethick($this->map['image'],0, 0, 0, $this->map['height'], $red, 8);
		self::imagelinethick($this->map['image'],0, $this->map['height'], $this->map['width'], $this->map['height'], $red, 8);
		self::imagelinethick($this->map['image'],$this->map['width'], $this->map['height'], $this->map['width'], 0, $red, 8);
		self::imagelinethick($this->map['image'], $this->map['width'], 0, 0, 0, $red, 8);
	}*/
    
    function drawRedBox(){
        var red = new Array(240,20,20);
        var height = interactiveMap.visibleMap.mainLayer.canvasElement.height;
        var width = interactiveMap.visibleMap.mainLayer.canvasElement.width;
        
        imageLine(0,0,0,height, red, new Array(8,0));
        imageLine(0,height,width,height, red, new Array(8,0));
        imageLine(width,height,width,0, red, new Array(8,0));
        imageLine(width,0,0,0, red, new Array(8,0));
    }


    //draw function (main part)
    //
    //draw the basic map
    interactiveMap.visibleMap.mainLayer.context.drawImage(interactiveMap.visibleMap.image, 0, 0);

    //put regular and fake orders together so both are drawn onto the map
    var orders = MyOrders.select(function(o) {
        return o.isComplete;
    }).pluck("interactiveMap").invoke("fakeOrder").compact();
    for (var i = 0; i < MyOrders.length; i++) {
        orders.push(MyOrders[i]);
    }
    //check for each order, if setted and draws it if setted
    for (var i = 0; i < orders.length; i++) {
        if (orders[i].isComplete) {
            switch (orders[i].type) {       //type "Hold" not handled because nothing to draw
                case "Move":
                    drawMove(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    break;
                case "Faked Move":
                    drawFakedMove(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    break;
                case "Support hold":
                    drawSupportHold(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    break;
                case "Support move":
                    drawSupportMove(orders[i].Unit.terrID, orders[i].fromTerrID, orders[i].toTerrID, false);
                    break;
                case "Convoy":
                    drawConvoy(orders[i].Unit.terrID, orders[i].fromTerrID, orders[i].toTerrID, false);
                    break;
                case "Destroy":
                    drawDestroyedUnit(orders[i].toTerrID, false);
                    break;
                case "Build Fleet":
                case "Build Army":
                    drawCreateUnit(orders[i].toTerrID, orders[i].type, false);
                    break;
                case "Disband":
                    drawDislodgedUnit(orders[i].Unit.terrID), false;
                    break;
                case "Retreat":
                    drawRetreat(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    break;
            }
        }
        //for the retreat-phase, the retreating units of the player are drawn, as they are not on the basic map
        if (context.phase == "Retreats") {
            drawRetreatUnit(orders[i].Unit.terrID, orders[i].Unit.type, false);
        }
    }
    
    
    //some info-text
    interactiveMap.visibleMap.mainLayer.context.font = "20px bold";
    interactiveMap.visibleMap.mainLayer.context.fillStyle = "rgb(255,0,0)";
    interactiveMap.visibleMap.mainLayer.context.fillText('InteractiveMap - PREVIEW'+((OrdersHTML.ordersChanged)?' - NOT SAVED':''),5,20);
    interactiveMap.visibleMap.mainLayer.context.fillText('Click on an own unit to enter its orders',5,40);
    interactiveMap.visibleMap.mainLayer.context.font = "10px sans-serif";
    
    if(OrdersHTML.ordersChanged)
        //red box around the map
        drawRedBox();
};

/*
 * colors the territories according to the terrChoices
 */
interactiveMap.greyOut.draw = function(terrChoices) {
    
    function floodfillTransp(startX, startY, imgData) {
        var pixelPos = ((startY * width) + startX) * 4;
        var IAcolor = interactiveMap.hiddenMap.imageData.data[pixelPos] + "," + interactiveMap.hiddenMap.imageData.data[pixelPos + 1] + "," + interactiveMap.hiddenMap.imageData.data[pixelPos + 2] + "," + interactiveMap.hiddenMap.imageData.data[pixelPos + 3];

        var pixelStack = [[startX, startY]];

        while (pixelStack.length) {
            var newPos = pixelStack.pop();
            var x = newPos[0];
            var y = newPos[1];
            pixelPos = ((y * width) + x) * 4;
            while ((y-- >= 0) && matchColor(IAcolor, pixelPos))
                pixelPos -= width * 4;
            pixelPos += width * 4;
            y++;
            var reachLeft = false;
            var reachRight = false;
            while (y++ < height - 1 && matchColor(IAcolor, pixelPos))
            {
                imgData.data[pixelPos + 3] = 0;

                if (x > 0)
                {
                    if ((imgData.data[pixelPos - 4 + 3] != 0) && matchColor(IAcolor, pixelPos - 4))
                    {
                        if (!reachLeft) {
                            pixelStack.push([x - 1, y]);
                            reachLeft = true;
                        }
                    }
                    else if (reachLeft)
                    {
                        reachLeft = false;
                    }
                }

                if (x < width - 1)
                {
                    if ((imgData.data[pixelPos + 4 + 3] != 0) && matchColor(IAcolor, pixelPos + 4))
                    {
                        if (!reachRight)
                        {
                            pixelStack.push([x + 1, y]);
                            reachRight = true;
                        }
                    }
                    else if (reachRight)
                    {
                        reachRight = false;
                    }
                }

                pixelPos += width * 4;
            }

        }

        return imgData;
    }

    function matchColor(IAcolor, pixelPos) {
        return IAcolor == interactiveMap.hiddenMap.imageData.data[pixelPos] + "," + interactiveMap.hiddenMap.imageData.data[pixelPos + 1] + "," + interactiveMap.hiddenMap.imageData.data[pixelPos + 2] + "," + interactiveMap.hiddenMap.imageData.data[pixelPos + 3]
    }
	
	if (typeof interactiveMap.TerritoryPositions == "undefined") {
		// GreyOut is not ready yet as territory data still loading
		// -> request a redraw once loading is finished
		
		interactiveMap.greyOut.requested = true;
		return;
	}

    var width = interactiveMap.hiddenMap.imageData.width;
    var height = interactiveMap.hiddenMap.imageData.height;
    interactiveMap.visibleMap.greyOutLayer.context.clearRect(0, 0, width, height);
    if (interactiveMap.options.greyOut) {
        if (interactiveMap.options.unitGreyOut && (typeof terrChoices == 'undefined')) {
            terrChoices = MyUnits.pluck("terrID");
        }
        if (typeof terrChoices != 'undefined') {
			interactiveMap.visibleMap.greyOutLayer.context.fillStyle = "rgba(0,0,0," + interactiveMap.options.greyOutIntensity + ")";
			interactiveMap.visibleMap.greyOutLayer.context.fillRect(0, 0, width, height);

			var imgData = interactiveMap.visibleMap.greyOutLayer.context.getImageData(0, 0, width, height);

			for (var i = 0; i < terrChoices.length; i++) {
				var terrID = Territories.get(terrChoices[i]).coastParentID;
				for (var j = 0; j < interactiveMap.TerritoryPositions[terrID].length; j++) {
					imgData = floodfillTransp(interactiveMap.TerritoryPositions[terrID][j][0], interactiveMap.TerritoryPositions[terrID][j][1], imgData);
				}
			}
			interactiveMap.visibleMap.greyOutLayer.context.putImageData(imgData, 0, 0);
        }
    }
};