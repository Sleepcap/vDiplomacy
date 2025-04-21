<p>The Colonial Diplomacy variant of diplomacy follows the most of the rules as standard diplomacy with a modified map, and with 5 additional rules. The game is the first commercially published true simple Diplomacy variant.</p>
     
<p><b>Rules (version 2.2):</b></p>

<ol>
<li>Except as noted below, the standard rules of play for Diplomacy apply.</li>
<li>Cebu looks like a body of water with islands inside of it, which is accurate.  It is analogous to Kiel, Constantinople or Denmark in the standard diplomacy.</li>
<li>The Caspian Sea, Lake Baykal and any other unnamed space is not passable.</li>
<li>This version of Colonial is based on the Moulmein Convention, so land bridges have been added between Otaru and Akita, and Sakalin and Otaru.</li>
<li>Hong Kong is a British home supply center. It counts as a supply center for any country except China. If China is in possession of Hong Kong, it counts as a non-supply neutral territory. </li>
<li>There are neutral SC's on the starting map on coloured territories. These SC's are marked with a neutral coloured box (<img id="NSC" src="variants/Colonial/resources/sc_small.png"/>) drawn around them. Once occupied they count as any other SC.</li>
<li><b>The Suez-Canal:</b><br>
The player who controls Egypt with a unit can move an own or foreign fleet directly from the Mediterranean to the Red Sea (and vice versa), through the Suez Canal. Therefore the player can set an extra order to accord permission to a fleet in the Mediterranean or Red Sea.<br>
A fleet can only move directly between the Mediterranean and Red Sea if it gains permission by the player of the unit that occupies Egypt. If there is no unit in Egypt, there is no way to use the Suez Canal.<br>
On the map the given permission will be symbolized by a blue arrow around the Suez Canal pointing into that direction, a fleet could pass the Suez Canal (e.g. pointing towards the Red Sea, if the fleet in the Mediterranean Sea is allowed to use the Suez Canal).<br>
<b>Important:</b> Unlike stated in the original rules, you do not have to hold Egypt for the whole turn to be able to use the Suez Canal in this implementation. This decision was made to avoid tons of paradoxes which could occur with such conditional moves.<br>
Apart from this rule Egypt is analogous to Kiel, Constantinople or Denmark in the standard diplomacy.</li>
<li><b>The Trans-Siberian Railroad (TSR):</b><br>
	<div style="text-align:center"><img src="variants/Colonial/resources/rulesImg/TSR.png"></div>
	The Trans-Siberian Railroad (TSR) runs from Moscow to Vladivostok and may be used by the Russian player to quickly move units across his empire. But of course there are some rules that specify how this movement is happening:<br>
	<ul>
		<li>Only the Russian player may use the TSR.</li>
		<li>Only one unit may use the TSR per turn.</li>
		<li>A unit using the TSR can move from one territory with access to the TSR to any other territory along the TSR. However, foreign powers can block parts of the route by occupying the territories along them (check out <a href="#v12.TSR.B1a">v12.TSR.B1a</a> and follow-up for examples).</li>
		<li>A move with TSR may never be used to attack a unit. It is therefore impossible to cut supports with the TSR move or dislodge a unit (<a href='#v12.TSR.C2'>v12.TSR.C2</a>). Though one can still try to enter a territory with unit speculating that it will leave the territory in the same turn (<a href='#v12.TSR.C4'>v12.TSR.C4</a>). Or one can try to bounce units out (<a href='#v12.TSR.C5'>v12.TSR.C5</a>).</li>
		<li>If a territory on the route is blocked, the TSR move will not have any effects on territories beyond the blockade. Instead the TSR unit will travel as soon as it gets and stops before the blockade (possibly queueing behind other Russian units; <a href='#v12.TSR.D1'>v12.TSR.D1</a>). The same happens, if just the destination is blocked by an occupier or a standoff.</li>
		<li>Russian units along the route do not block the TSR.</li>				
		<li>Though a foreign unit will block the route if 
			<ul>
				<li>it actively holds (i.e. hold or support order) on a territory on the route (even if dislodged in the turn), </li>
				<li>it moves along the route (from a territory on route to a territory on route),</li>
				<li>it moves into a territory and is not held out by a standoff with a third unit, a holding unit or is blocked half way by a head-to-head move,</li>
				<li>it tries to enter the territory and is only hold back by a returning unit (i.e. it is possible to block the TSR move with move chains).</li>
			</ul>
			However, a foreign unit that at least tries to leave a territory of the TSR and is not moving along the route is not blocking route. Instead it leaves the track temporary open as it tries to enter the new territory (no matter if successful or not). For examples check out <a href="#v12.TSR.B1a">v12.TSR.B1a</a> and follow-up.
		</li>
		<li>If a foreign units try to enter a territory on the route and the TSR is not blocked before, it will be bounced back by the TSR move and the TSR unit stops before the standoff territory (<a href='#v12.TSR.C5'>v12.TSR.C5</a>).</li>
		<li>It is possible to support a TSR move. Although the support only counts for the final destination and it will not be used to dislodge a unit. However, it can be used to hold back an opposing party. This is even possible, if the destination is occupied as the support will be used to prevent third units from entering but not to attack the occupier (<a href="#v12.TSR.C9a">v12.TSR.C9a</a>).</li>
		<li>The TSR can be used for neighboring territories on the route, as well. As Russian units do not block the route it is possible to swap to Russian units with the help of the TSR (<a href="#v12.TSR.E2">v12.TSR.E2</a>).</li>
		<li>Special care should be taken, if the other unit in such a Head-To-Head-Move is non-Russian. In this case a swapping is not possible. Instead the route is blocked even half way between the territories. That also means that no supports for the TSR move will have any effect (<a href="#v12.TSR.E3">v12.TSR.E3</a> and follow-up).</li>
	</ul>
	TSR orders are set like convoy orders but with 'via TSR' instead of 'via convoy' in the order interface. If there is already one TSR order set, the Russian player will not be able to set another move via TSR unless he unsets the first one.<br>
	TSR moves are displayed as orange arrows on the top of the map. They will always display the initial target and the actual destination of a (blocked) TSR move. <br>
</li>
</ol>

<p><b>Rules revision (version 2.2):</b></p>

<p>The rule about temporary left territories along the TSR route was altered in the
following way: A non-Russian unit
that tries to leave a territory on the TSR is not considered to unblock the TSR
in all cases anymore. Unlike before a move <i>along</i> the route now blocks the free
movement of a TSR move.<br>
Additionally, a unit that tries to enter the route and is only bounced out by a returning
unit does indeed block the route. So a move chain can now actually block the route even
if it is not successfull.
</p>

<p><b>Reasoning for deviation from original rules:</b><br>
The rule of temporary unblocked TSR-territories can be found in a 
<a href="http://grognard.com/ah/errata/coloe.html">clarification</a> by Avalon Hill 
(The General, Vol 30, No 4) and was further discussed in 
<a href="https://web.archive.org/web/20150506124910/http://www.diplom.org/Zine/S1997M/Schwarz/Paradox.html">this</a> article by Andy Schwarz.<br>
This clarification was probably introduced to avoid paradoxes that can occure
if an army is blocked by a unit that bounces the TSR unit at a later stage of the route.
If the TSR move would be blocked by the first army that cannot leave, there would
be no bounce with the second unit at the later section of the TSR. 
This, however, means that the first army will leave
resulting in the bounce at the later section, resulting in the blocked route again. 
A classical Diplomacy Paradox.<br>
The "temporary leave rule" solves this problem by letting the TSR unit skip through
the territory which is temporary free. Because of this interpretation of the 
initial rules there will always be a bounce with the second unit at the later stage
and the paradox is avoided.<br>
The clarification by Avalon Hill, however, emphasizes in the given example that 
this rule does not only apply to units that are temporarily leaving the route but 
also to those that are moving along. As pointed out by Schwarz this cannot only cause
a bounce behind the blocking unit but can also be used to pass by a blocking unit if
the TSR unit received the needed support.<br>
While this resolution seems to be rediculous and unnecessary for avoiding paradoxes, 
one can even get a step further and consider two non-Russian armies on the TSR
e.g. in Perm and Krasnoyarsk that bounce Omsk. According to Avalon Hills clarification
a TSR move from Moscow to Irkutsk would be successfull as both enemy armies temporary
leave the territories on the route and Omsk is staying free because of the bounce
of those two armys. So even though all action takes place along the route, the 
Russian unit can pass by without any interference. On the other hand the Russian unit 
would have been blocked, if one of the two non-Russian units had moved somewhere 
else and the other would only bounce with the TSR move. One of the two units
might even be Russian and the Russian player could actively unblock the TSR by
seeking the bounce with the foreign unit.<br>
As this feels rather unintuitive and much too powerful for Russia in my opinion,
this might frustrate the players who did not thought about all quirks of the TSR 
rules. So I decided to restrict the "temporary leave rule" just to units that really 
try to leave the TSR route (which is needed to avoid paradoxes). After all this 
means that the TSR move above will be unsuccessfull while e.g. a situation with 
armies in Perm and Omsk bouncing Orenburg will not block the route. A TSR unit 
can pass by while those armies confront each other offside the railroad in Orenburg
(which would also be the case if there was only one unit that is not bounced back,
so the Russian player cannot activly improve the chances of success for the TSR 
apart from supports in the targetted territory).
</p>

<?php include_once('resources/rulesImg/resultDATC.php')?>

