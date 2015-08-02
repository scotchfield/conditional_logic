<?php
/**
 * Plugin Name: Conditional Logic
 * Plugin URI: http://scootah.com/
 * Description: Add conditional logic to your WordPress pages
 * Version: 1.0
 * Author: Scott Grant
 * Author URI: http://scootah.com/
 */
class WP_ConditionalLogic {

	/**
	 * Store reference to singleton object.
	 */
	private static $instance = null;

	/**
	 * The domain for localization.
	 */
	const DOMAIN = 'wp-conditional-logic';

	/**
	 * Instantiate, if necessary, and add hooks.
	 */
	public function __construct() {
		if ( isset( self::$instance ) ) {
			return;
		}

		self::$instance = $this;
	}

}

$wp_conditional_logic = new WP_ConditionalLogic();
