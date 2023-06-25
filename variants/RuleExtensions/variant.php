<?php
/**
 * RuleExtensionsVariant lets you easily add standard rules to your variant.
 * 
 * Enable one of the following rules by adding the following line in your variants constructor before calling the parent::__construct().
 * 
 * $this->rules[<rule_string_constant>] = true;
 * 
 * And make sure that your variant class and all its class extensions extend the respective RuleExtensionClasses (e.g. 'YourVariant extends RuleExtensionsVariant').
 */

/**
 * CustomMap rule enables you to add a custom map to your variant. 
 * 
 * In your variant provide the following files
 * 	classes/adjudicatorPreGame.php
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
 * In your variant provide the following icon files per country in the numeric order you defined them in your variant class.
 * 	resources/
 * 		smallarmy_0.png
 * 		smallarmy_1.png
 * 		...
 * 		smallfleet_0.png
 * 		smallfleet_1.png
 * 		...
 * 		army_0.png
 * 		army_1.png
 * 		...
 * 		fleet_0.png
 * 		fleet_1.png
 * 
 * CustomIconsPerCountry and CustomIcons can not be activated in parallel.
 */
const RULE_CUSTOM_ICONS_PER_COUNTRY = 'CustomIconsPerCountry';
/**
 * BuildAnywhere lets players build on every open SC they own.
 */
const RULE_BUILD_ANYHWERE = 'BuildAnywhere';

// by default we extend the classic variant for loading resources and map and turn numbers
abstract class RuleExtensionsVariant extends ClassicVariant {

	private static $ruleExtensionVariantName = 'RuleExtensions';

	/**
	 * Array of the potential rules to be activated. Will be adjusted by the variant specific constructor of the variant utilizing RuleExtensions.
	 */
	public $rules = array(
		RULE_CUSTOM_MAP => false,
		RULE_CUSTOM_ICONS => false,
		RULE_CUSTOM_ICONS_PER_COUNTRY => false,
		RULE_BUILD_ANYHWERE => false,
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

		if($this->rules[RULE_BUILD_ANYHWERE]) {
			// Order validation code, changed to validate builds on non-home SCs
			$this->variantClasses['userOrderBuilds'] = self::$ruleExtensionVariantName;
			// Count all free SCs and not just the home SCs.
			$this->variantClasses['processOrderBuilds'] = self::$ruleExtensionVariantName;
			// Order interface/generation code, changed to add javascript in resources which makes non-home SCs an option
			$this->variantClasses['OrderInterface'] = self::$ruleExtensionVariantName;

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
}

?>