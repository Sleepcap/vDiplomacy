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
    
	function getTSRroute(from, to) {
		// get indices of TSR territory array as indicator of the direction the TSR moves
		var fromIdx = transSibTerritories.indexOf(from);
		var toIdx = transSibTerritories.indexOf(to);
		
		var eastwards = (toIdx > fromIdx);
		
		var route = new Array(from);
		
		var currentIdx = fromIdx;
		
		while(currentIdx != toIdx){
			if(eastwards)
				currentIdx++;
			else
				currentIdx--;
			
			route.push(transSibTerritories[currentIdx]);
		}
		
		return route;
	}
	
	function getTSRdrawingCoordinates(coordinates){
		var y0 = 7;
		
		return {x: coordinates.x, y:y0};
	}
	
	function drawTSRarrow(from, to, initial, final){
		var fromTSR = getTSRdrawingCoordinates(from);
		var toTSR = getTSRdrawingCoordinates(to);
		
		drawOrderArrow(from, fromTSR, (initial)?'MoveTSRwithoutArrow':'MoveTSRwithoutArrowSmall');
		drawOrderArrow(fromTSR, toTSR, 'MoveTSR');
		drawOrderArrow(toTSR, to, (final)?'MoveTSRwithoutArrow':'MoveTSRwithoutArrowSmall');
	}
	
    function drawTSR(fromTerrID, toTerrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawTSR, 'tsr', fromTerrID, toTerrID);
        }

        if (complete) {
			var route = getTSRroute(fromTerrID, toTerrID);
		
			var routeA = route.pop();
			var routeB;
			while(route.length > 0){
				routeB = routeA;
				routeA = route.pop();
			
				drawTSRarrow(
					{x: terrTable[routeA].smallMapX, y: terrTable[routeA].smallMapY},
					{x: terrTable[routeB].smallMapX, y: terrTable[routeB].smallMapY},
					(routeA == fromTerrID),
					(routeB == toTerrID));
			
			}
        }
    }
	
	function drawSupportTSR(terrID, TSRorder, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawSupportTSR, 'supportTSR', terrID, TSRorder);
        }
		
		if (complete) {
			// just draw support for last segment where it has value
			var route = getTSRroute(TSRorder.Unit.terrID, TSRorder.toTerrID);
			var last = route.pop();
			var slast = route.pop();
			
			var from = getTSRdrawingCoordinates({x: terrTable[slast].smallMapX, y:terrTable[slast].smallMapY});
			var to = getTSRdrawingCoordinates({x: terrTable[last].smallMapX, y:terrTable[last].smallMapY});
			
            var fromX = from.x;
            var fromY = from.y;
            var toX = to.x;
            var toY = to.y;

            toX -= (toX - fromX) / 3;
            toY -= (toY - fromY) / 3;

            var start = {x: terrTable[terrID].smallMapX, y: terrTable[terrID].smallMapY};
            var end = {x: toX, y: toY};
            drawOrderArrow(start, end, 'Support move');
        }
    }
    
    function drawMoveSuez(fromTerrID, toTerrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawMoveSuez, 'moveSuez', fromTerrID, toTerrID);
        }

        if (complete) {
            var start = {x: terrTable[fromTerrID].smallMapX, y: terrTable[fromTerrID].smallMapY};
            var suez = {x: terrTable['126'].smallMapX, y: terrTable['126'].smallMapY};
            var end = {x: terrTable[toTerrID].smallMapX, y: terrTable[toTerrID].smallMapY};
            drawOrderArrow(start, suez, 'MoveSuez');
            drawOrderArrow(suez, end, 'Move');
        }    
    }
    
    function drawFakedMoveSuez(fromTerrID, toTerrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawFakedMoveSuez, 'moveSuez', fromTerrID, toTerrID);
        }

        if (complete) {
            var start = {x: terrTable[fromTerrID].smallMapX, y: terrTable[fromTerrID].smallMapY};
            var suez = {x: terrTable['126'].smallMapX, y: terrTable['126'].smallMapY};
            var end = {x: terrTable[toTerrID].smallMapX, y: terrTable[toTerrID].smallMapY};
            drawOrderArrow(start, suez, 'fakedMoveSuez');
            drawOrderArrow(suez, end, 'fakedMove');
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
    
    function drawSuezPermission(terrID, skipExt) {
        var terrTable = Territories.toObject();
        var complete = true;

        if ((typeof extension == 'function') && !skipExt) {
            complete = extension(drawSuezPermission, 'suezPermission', terrID);
        }

        if (complete) {
            if(terrID == '101') terrID = '99';
            else if(terrID = '99') terrID = '101';
                
            var fromX = new Number(terrTable['126'].smallMapX);
            var fromY = new Number(terrTable['126'].smallMapY);
                
            var start = {x: fromX, y: fromY};
            var end = {x: fromX+(terrTable[terrID].smallMapX-fromX)/20, y: fromY+(terrTable[terrID].smallMapY-fromY)/20};
            drawOrderArrow(start, end, 'SuezPermission');  
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
        'MoveTSR': {'color': new Array(255, 156, 0), //0, 153, 2),//
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.4,
            'headLength': new Array(12, 30),
            'border': new Array(0, 0)
        },
		'MoveTSRwithoutArrow': {'color': new Array(255, 156, 0), //0, 153, 2),//
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.4,
            'headLength': new Array(0, 0),
            'border': new Array(0, 0)
        },
		'MoveTSRwithoutArrowSmall': {'color': new Array(255, 156, 0), //0, 153, 2),//
            'thickness': new Array(1, 2),
            'headAngle': Math.PI / 7,
            'headStart': 0.4,
            'headLength': new Array(0, 0),
            'border': new Array(0, 0)
        },
        'SuezPermission': {'color': new Array(4,113,160),
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.1,
            'headLength': new Array(12, 30),
            'border': new Array(0, 0)
        }, 
        'MoveSuez': {'color': new Array(196, 32, 0),
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.1,
            'headLength': new Array(0, 0),
            'border': new Array(0, 0)
        },
        'fakedMoveSuez': {'color': new Array(100, 100, 100),
            'thickness': new Array(2, 4),
            'headAngle': Math.PI / 7,
            'headStart': 0.1,
            'headLength': new Array(0, 0),
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
                    if(orders[i].isTransSibOrder())
                        drawTSR(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    else if((orders[i].Unit.terrID == '99' && orders[i].toTerrID == '101') || (orders[i].Unit.terrID == '101' && orders[i].toTerrID == '99'))  
                        drawMoveSuez(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    else
                        drawMove(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    break;
                case "Faked Move":
                    if((orders[i].Unit.terrID == '99' && orders[i].toTerrID == '101') || (orders[i].Unit.terrID == '101' && orders[i].toTerrID == '99'))  
                        drawFakedMoveSuez(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    else
                        drawFakedMove(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    break;
                case "Support hold":
                    if(orders[i].Unit.terrID == "126")
                        drawSuezPermission(orders[i].toTerrID, false);
                    else
                        drawSupportHold(orders[i].Unit.terrID, orders[i].toTerrID, false);
                    break;
                case "Support move":
                    if((orders[i].fromTerrID == '99' && orders[i].toTerrID == '101') || (orders[i].fromTerrID == '101' && orders[i].toTerrID == '99'))
                        drawSupportMove(orders[i].Unit.terrID, '126', orders[i].toTerrID, false);
                    else {
						var transSibOrder = MyOrders.find(function(o){return o.isTransSibOrder();});
						if(!Object.isUndefined(transSibOrder) && orders[i].fromTerrID == transSibOrder.Unit.terrID && orders[i].toTerrID == transSibOrder.toTerrID)
							drawSupportTSR(orders[i].Unit.terrID, transSibOrder, false);
						else	
							drawSupportMove(orders[i].Unit.terrID, orders[i].fromTerrID, orders[i].toTerrID, false);
					}
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


