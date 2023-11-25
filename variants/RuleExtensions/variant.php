<?php
/**
 * RuleExtensionsVariant lets you easily add standard rules to your variant.
 * 
 * Enable one of the following rules by adding the following line in your variants constructor before calling the parent::__construct().
 * 
 * $this->rules[<rule_string_constant>] = true;
 * 
 * And make sure that your variant class and all its class extensions extend the respective RuleExtensionClasses (e.g. 'YourVariant extends RuleExtensionsVariant').
 * 
 * If RuleExtensionsVariant should also control interactive map behavior, make sure to add class in interactiveMap/interactiveMap.php that extends the respective class in RuleExtensions variant.
 */

/**
 * CustomMap rule enables you to add a custom map to your variant. 
 * 
 * In your variant provide the following files
 * 	classes/
 * 		adjudicatorPreGame.php -> custom starting positions
 * 		drawMap.php -> custom country colors
 * 	resources/
 * 		smallmap.png
 * 		smallmapNames.png
 * 		map.png
 * 		mapNames.png
 *  interactiveMap/IA_smallmap.png
 *  install.php
*/
const RULE_CUSTOM_MAP = 'CustomMap';
/**
 * CustomIcons rule enables you to replace the classic unit icon set by a custom unit icon set.
 * 
 * In your variant provide the following files
 * 	resources/
 * 		army.png
 * 		fleet.png
 * 		smallarmy.png
 * 		smallfleet.png
 */
const RULE_CUSTOM_ICONS = 'CustomIcons';
/**
 * CustomIconsPerCountry enables you to define country specific unit icons.
 * 
 * In your variant provide the following icon files per country in your variant class.
 * 	resources/
 * 		smallarmy<countryName1>.png
 * 		smallarmy<countryName2>.png
 * 		...
 * 		smallfleet<countryName1>.png
 * 		smallfleet<countryName2>.png
 * 		...
 * 		army<countryName1>.png
 * 		army<countryName2>.png
 * 		...
 * 		fleet<countryName1>.png
 * 		fleet<countryName2>.png
 * 
 * CustomIconsPerCountry and CustomIcons can not be activated in parallel.
 */
const RULE_CUSTOM_ICONS_PER_COUNTRY = 'CustomIconsPerCountry';
/**
 * BuildAnywhere lets players build on every open SC they own.
 */
const RULE_BUILD_ANYHWERE = 'BuildAnywhere'; // legacy typo kept for compatibility
const RULE_BUILD_ANYWHERE = 'BuildAnywhere';
/**
 * Tranform lets players transform armies to fleets and vice versa as additional order 
 * on empty  supply centers. Transformation succeeds when units are not attacked.
 */
const RULE_TRANSFORM = 'Transform';

// by default we extend the classic variant for loading resources and map and turn numbers
abstract class RuleExtensionsVariant extends ClassicVariant {

	private static $ruleExtensionVariantName = 'RuleExtensions';

	public $ruleExtensionVersion = '0.9.1';

	/**
	 * Array of the potential rules to be activated. Will be adjusted by the variant specific constructor of the variant utilizing RuleExtensions.
	 */
	public $rules = array(
		RULE_CUSTOM_MAP => false,
		RULE_CUSTOM_ICONS => false,
		RULE_CUSTOM_ICONS_PER_COUNTRY => false,
		RULE_BUILD_ANYHWERE => false,
		RULE_TRANSFORM => false
	);

	/**
	 * Array of all the extended classes due to rule additions. 
	 * Will be filled by the constructor and used for autoloading class files.
	 */
	public $ruleExtensionClasses = array();

	public function __construct() {
		// rule validation
		if($this->rules[RULE_CUSTOM_ICONS] && $this->rules[RULE_CUSTOM_ICONS_PER_COUNTRY])
			throw new Exception('RuleExtensions: CustomIcons and CustomIconsPerCountry cannot be run together');

		$this->concatRuleExtensionVersionToCodeVersion();

		parent::__construct();

		// default: load Classic map and resources
		$this->variantClasses['drawMap'] = self::$ruleExtensionVariantName;
		// default: load Classic map's starting positions
		$this->variantClasses['adjudicatorPreGame'] = self::$ruleExtensionVariantName;

		if($this->rules[RULE_CUSTOM_MAP]) {
			// Load variant specific map files
			$this->variantClasses['drawMap'] = self::$ruleExtensionVariantName;
		}

		if($this->rules[RULE_CUSTOM_ICONS]) {
			// Load variant specific icons
			$this->variantClasses['drawMap'] = self::$ruleExtensionVariantName;
			// Adjust variant specific icons in board interface
			$this->variantClasses['OrderInterface'] = self::$ruleExtensionVariantName;
		}

		if($this->rules[RULE_CUSTOM_ICONS_PER_COUNTRY]) {
			// Load variant and country specific icons
			$this->variantClasses['drawMap'] = self::$ruleExtensionVariantName;
			// Adjust variant and country specific icons in board interface
			$this->variantClasses['OrderInterface'] = self::$ruleExtensionVariantName;
		}

		if($this->rules[RULE_BUILD_ANYHWERE]) {
			// Order validation code, changed to validate builds on non-home SCs
			$this->variantClasses['userOrderBuilds'] = self::$ruleExtensionVariantName;
			// Count all free SCs and not just the home SCs.
			$this->variantClasses['processOrderBuilds'] = self::$ruleExtensionVariantName;
			// Order interface/generation code, changed to add javascript in resources which makes non-home SCs an option
			$this->variantClasses['OrderInterface'] = self::$ruleExtensionVariantName;
		}

		if($this->rules[RULE_TRANSFORM]) {
			// Add code for drawing the transform command
			$this->variantClasses['drawMap'] = self::$ruleExtensionVariantName;
			// Add code for displaying the transform command in the order archive
			$this->variantClasses['OrderArchiv'] = self::$ruleExtensionVariantName;
			// Order interface/generation code, changed to add javascript in resources to support transform command in front-end controls
			$this->variantClasses['OrderInterface'] = self::$ruleExtensionVariantName;
			// Add code for correctly transforming units on successfull transform commands
			$this->variantClasses['processOrderDiplomacy'] = self::$ruleExtensionVariantName;
			// Order validation code, changed to correctly handle encoded transform commands
			$this->variantClasses['userOrderDiplomacy'] = self::$ruleExtensionVariantName;
		}

		// Store all the classes pointing to ruleExtensions to inject $Variant arg needed in those extensions.
		// Variants extending RuleExtensions class files may additionally expand this array.
		foreach(array_keys( $this->variantClasses ) as $className){
			$this->ruleExtensionClasses[$className] = true;
		}
	}

	// Override call method used for variant class calls to always attach current variant object for further references.
	public function __call($name, $args){
		if( isset($this->ruleExtensionClasses[$name]) ){
			array_unshift($args, $this);
		}

		return parent::__call($name, $args);
	}

	public function __sleep()
	{
		$cachedProps = parent::__sleep();

		$cachedProps[] = 'ruleExtensionClasses';
		$cachedProps[] = 'rules';

		return $cachedProps;
	}

	public function __wakeup() {
		$this->concatRuleExtensionVersionToCodeVersion();

		parent::__wakeup();
	}

	// For auto-update of variants on rule extension updates, concatenate the rule extension version to the code version
	private function concatRuleExtensionVersionToCodeVersion() {
		$this->codeVersion .= '.'.$this->ruleExtensionVersion;
	}
}

?>