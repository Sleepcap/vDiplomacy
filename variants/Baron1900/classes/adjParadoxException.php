<?php
/*
	Copyright (C) 2016 Tobias Florin

	This file is part of the 1900 variant for webDiplomacy

	The 1900 variant for webDiplomacy is free software: you can
	redistribute it and/or modify it under the terms of the GNU Affero General
	Public License as published by the Free Software Foundation, either version
	3 of the License, or (at your option) any later version.

	The 1900 variant for webDiplomacy is distributed in the hope
	that it will be useful, but WITHOUT ANY WARRANTY; without even the implied
	warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
	See the GNU General Public License for more details.

	You should have received a copy of the GNU Affero General Public License
	along with webDiplomacy. If not, see <http://www.gnu.org/licenses/>.

*/

defined('IN_CODE') or die('This script can not be run by itself.');

	/* 
	 * Attacks from units moving around South Africa are only included if they
	 * dislodge our unit.
	 * 
	 * This enables a possibility for a new Paradox, which can be seen as an 
	 * extension of DATC case 6.F.14. It is caused by a supported bounce in Egypt 
	 * (with the Med attacker being supreme because of the half attack strength 
	 * of MAO), that prevents a MAO unit to enter egypt and therefor prevents it
	 * to cut a support of unit holding in Egypt. If the Med attacker is moving
	 * by himself, he would just take Egypt as he should.
	 * However, if Egypt is attacked by a convoyed army, but is supporting a 
	 * successfull attack against the unit convoying, things get complicated. By 
	 * default the adjudicator resolves this by setting the convoyed attack to hold, 
	 * meaning, that the Egypt would be able to support the attack against the 
	 * convoying fleet (since MAO doesn't cut support if Egypt is not dislodged).
	 * However, if the convoyed attack is no longer available, there is no bounce
	 * and Egypt would be dislodged by a supported attack from MAO, meaning that
	 * it's support for the convoying fleet is cut, meaning the convoy can take
	 * place, meaning there is the bounce again.
	 * 
	 * Example: Egypt being attacked by MAO with
	 * support from Hejaz and by Gre via EAM with support from Cyr. Egypt supports
	 * Dam -> EAM
	 * 
	 * Result without MAO: F Dam forces EAM to retreat -> Egypt successfully holds
	 * 
	 * Problem with F MAO: F Mao would succeed, since Gre doesn't attack anymore
	 * -> F MAO successfull -> F Egypt dislodged -> F Dam unsuccessfull -> A Gre successfull
	 * -> F MAO unsuccessfull -> ... -> A Gre unsuccessfull -> F MAO successfull -> ... 
	 * 
	 * If no adjustments to the adjudicator are made to resolve it, the adjudicator
	 * would identify the paradox as a classical convoy paradaox and would force
	 * all convoys to hold to resolve the paradox. This means, that MAO would be
	 * able to take Egypt or Egypt wouldn't be dislodged. 
	 * Since rule (4) and all other rules in that section are designed
	 * to weaken the move from MAO, not strenghten it, and the default 
	 * resolution for such an attack on Egypt without these rules would be a bounce
	 * in Egypt with no dislodgment (since Egypts support would be cut in any way
	 * by MAO), I much prefer an all-holds solution with MAO bouncing with Med attacker,
	 * but also cutting Egypts support, so the Med attackers last convoying fleet
	 * is not dislodged; so the paradox needs to be handled specially in this class.
	 * 
	 * Note, that rule (4) only applies to attacks from MAO, not to convoys via 
	 * MAO (in contrast to rule (2)/(5)), making this problem a bit easier.
	 * 
	 * ----
	 * 
	 * How to detect the paradox?
	 * 
	 * To resolve a paradox, the default adjudicator checks for MoveChains first 
	 * by checking if all included units are moves. This doesn't interfere with
	 * our paradox.
	 * Next, the adjudicator checks for Convoy-Paradoxes by checking if there is
	 * at least one convoyed move and a potential convoying unit as holding unit.
	 * This check will detect our Egypt-Paradox, as well, and sets convoys to hold,
	 * that is in our interest. So it is enough to detect the Egypt-Paradox 
	 * after that and setting the additional move of MAO to hold after that
	 * (but before the paradox is readjudicated with the adjusted values)
	 * 
	 * The characteristic, that distincts the Egypt-Paradox from an classical
	 * Convoy-Paradox is the MAO attacking unit, that is included in this paradox.
	 * Since Convoy-Paradoxes are caused by convoyed armies and defenders supporting
	 * attacks on the convoying fleets (or support a convoying fleet as in second
	 * order paradoxes), only other attacks on sea territories can be included 
	 * in the classical paradoxes. If there would be any coast territories with
	 * units supporting and a fleet would attacking those, it will always cut
	 * supports and won't interrupt any convoy and therefore cannot be included 
	 * in the paradox because of an undecided resolution.
	 * So checking for an unit in the dependency list, that is attacking from MAO to
	 * egypt, is enough to make sure, we have an Egypt-Paradox.
	 */

class Baron1900Variant_adjParadoxException extends adjParadoxException
{
	/**
	 * The default resolve()-method detects the Egypt-Paradox as Convoy-Paradox
	 * so all Convoys are already set to hold. We have to make sure,
	 * if we have a Egypt-Paradox by checking moving unit in MAO and set MAO hold.
	 * However, since we don't know if MAO isn't just part of a move chain (we 
	 * don't know the result of parent::resolve()) we have to check that, as well.
	 * 
	 * Unfortunately, all internal methods of adjParadoxException are set to
	 * private not protected, so some of the functions have to be copied to this
	 * class, as they are used in our resolve()-method.
	 */
	public function resolve()
	{
		global $Game;
		
		parent::resolve();
		
		//load our units again
		$units = $this->dependencyChainToUnitChain();
		
		//check for egypt paradox
		if ( $this->isEgyptParadox($units)){
			
			//set MAO to hold
			foreach( $units as $unit )
			{
				if ( $unit->id == $Game->Variant->maoUnitAttacking )
				{
					$unit->paradoxForce('attackStrength', array('max'=>0,'min'=>0));
					$unit->paradoxForce('success', false);
					$unit->paradoxForce('preventStrength', array('max'=>0,'min'=>0));
					
					//set Egypt to hold
					$unit->defender->paradoxForce('success', false);
					
					return;
				}
			}
		}
	}
	
	private function isEgyptParadox(array $units){
		global $Game;
		
		if($this->isMoveChain($units))
			return false;
		
		if(!isset($Game->Variant->maoUnitAttacking))
			return false;
		
		foreach($units as $unit)
		{
			if ( $unit->id == $Game->Variant->maoUnitAttacking )
			{
				return true;
			}
		}
		return false;
	}
	
	//copied private functions
	//------------------------
	private function isMoveChain(array $units)
	{
		foreach($units as $unit)
		{
			if ( ! ( $unit instanceof adjMove ) )
			{
				return false;
			}
		}
		return true;
	}
	
	private function dependencyChainToUnitChain()
	{
		$units = array();
		
		foreach($this->dependencyChain as $pair)
		{
			list($unit, $decision) = $pair;
			
			if ( ! isset($units[$unit->id]) )
				$units[$unit->id] = $unit;
		}
		
		return $units;
	}
	
	private $dependencyChain;
	
	public function __construct( adjDependencyNode $start, $decision )
	{
		parent::__construct($start, $decision);
		
		$this->dependencyChain = array(array($start, $decision));
	}
	
	public function addDependency( adjDependencyNode $node, $decision )
	{
		parent::addDependency($node, $decision);
		
		list($startNode, $startDecision) = $this->dependencyChain[0];
		 
		if ( $startNode->id == $node->id and $startDecision == $decision)
		{
			//$this->complete = true;//done in parent
		}
		else
		{
			$this->dependencyChain[] = array($node, $decision);
		}
	}
}

