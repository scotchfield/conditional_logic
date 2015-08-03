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

		add_shortcode( 'is', array( $this, 'shortcode_is' ) );
	}

	public function shortcode_is( $atts, $content ) {
		if ( isset( $atts[ 'user_id' ] ) ) {
			$id = intval( $atts[ 'user_id' ] );

			if ( get_current_user_id() == $id ) {
				return do_shortcode( $content );
			}
		}

		return '';
	}

}

$wp_conditional_logic = new WP_ConditionalLogic();
