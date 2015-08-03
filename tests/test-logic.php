<?php

class TestConditionalLogic extends WP_UnitTestCase {

	/**
	 * @covers WP_ConditionalLogic::get_instance
	 */
	public function test_class_exists() {
		$plugin = WP_ConditionalLogic::get_instance();

		$this->assertNotNull( $plugin );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_user_id_correct_user() {
		$plugin = WP_ConditionalLogic::get_instance();

		$atts = array( 'user_id' => get_current_user_id() );
		$content = 'correct';

		$this->assertEquals( $content, $plugin->shortcode_is( $atts, $content ) );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_user_id_incorrect_user() {
		$plugin = WP_ConditionalLogic::get_instance();

		$atts = array( 'user_id' => get_current_user_id() + 1 );
		$content = 'correct';

		$this->assertEquals( '', $plugin->shortcode_is( $atts, $content ) );
	}

}
