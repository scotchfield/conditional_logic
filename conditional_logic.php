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

		add_action( 'init', array( $this, 'init' ) );
	}

	public static function get_instance() {
		return self::$instance;
	}

	public function init() {
		add_shortcode( 'is', array( $this, 'shortcode_is' ) );
	}

	public function shortcode_is( $atts, $content ) {
		$result = true;

		if ( isset( $atts[ 'user_id' ] ) ) {
			if ( get_current_user_id() != intval( $atts[ 'user_id' ] ) ) {
				$result = false;
			}
		}

		if ( isset( $atts[ 'user_can' ] ) ) {
			if ( ! current_user_can( $atts[ 'user_can' ] ) ) {
				$result = false;
			}
		}

		if ( isset( $atts[ 'user_meta_key' ] ) ) {
			$meta = get_user_meta( get_current_user_id(), $atts[ 'user_meta_key' ], true );

			if ( false == $meta ) {
				$result = false;
			} else {
				if ( isset( $atts[ 'user_meta_value' ] ) &&
						$meta != $atts[ 'user_meta_value' ] ) {
					$result = false;
				}
			}
		}

		if ( isset( $atts[ 'user_logged_in' ] ) ) {
			if ( ! boolval( $atts[ 'user_logged_in' ] ) != is_user_logged_in() ) {
				$result = false;
			}
		}

		if ( ! $result ) {
			$content = '';
		}

		return do_shortcode( $content );
	}

}

$wp_conditional_logic = new WP_ConditionalLogic();
