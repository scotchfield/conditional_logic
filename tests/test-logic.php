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

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_capability_subscriber() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'subscriber' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$plugin = WP_ConditionalLogic::get_instance();

		$content = 'correct';

		$this->assertEquals( $content, $plugin->shortcode_is( array( 'user_can' => 'read' ), $content ) );
		$this->assertEquals( '', $plugin->shortcode_is( array( 'user_can' => 'edit_posts' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_capability_contributor() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'contributor' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$plugin = WP_ConditionalLogic::get_instance();

		$content = 'correct';

		$this->assertEquals( $content, $plugin->shortcode_is( array( 'user_can' => 'edit_posts' ), $content ) );
		$this->assertEquals( '', $plugin->shortcode_is( array( 'user_can' => 'publish_posts' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_capability_author() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'author' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$plugin = WP_ConditionalLogic::get_instance();

		$content = 'correct';

		$this->assertEquals( $content, $plugin->shortcode_is( array( 'user_can' => 'publish_posts' ), $content ) );
		$this->assertEquals( '', $plugin->shortcode_is( array( 'user_can' => 'publish_pages' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_capability_editor() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'editor' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$plugin = WP_ConditionalLogic::get_instance();

		$content = 'correct';

		$this->assertEquals( $content, $plugin->shortcode_is( array( 'user_can' => 'publish_pages' ), $content ) );
		$this->assertEquals( '', $plugin->shortcode_is( array( 'user_can' => 'manage_options' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_capability_administrator() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'administrator' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$plugin = WP_ConditionalLogic::get_instance();

		$content = 'correct';

		$this->assertEquals( $content, $plugin->shortcode_is( array( 'user_can' => 'manage_options' ), $content ) );
		$this->assertEquals( '', $plugin->shortcode_is( array( 'user_can' => 'abcdefghijklmnopqrstuvwxyz' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_meta_key_does_not_exist() {
		$user = new WP_User( $this->factory->user->create() );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$plugin = WP_ConditionalLogic::get_instance();

		$meta_key = 'unknown_meta_key_test';
		$atts = array( 'user_meta_key' => $meta_key );
		$content = 'correct';

		$this->assertEquals( '', $plugin->shortcode_is( $atts, $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_meta_key_does_exist() {
		$user = new WP_User( $this->factory->user->create() );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$plugin = WP_ConditionalLogic::get_instance();

		$meta_key = 'unknown_meta_key_test';
		$meta_value = 'unknown_meta_key_test';
		$atts = array( 'user_meta_key' => $meta_key );
		$content = 'correct';

		update_user_meta( $user->ID, $meta_key, $meta_value );

		$this->assertEquals( $content, $plugin->shortcode_is( $atts, $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_meta_key_does_exist_bad_value() {
		$user = new WP_User( $this->factory->user->create() );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$plugin = WP_ConditionalLogic::get_instance();

		$meta_key = 'unknown_meta_key_test';
		$meta_value = 'unknown_meta_key_test';
		$atts = array(
			'user_meta_key' => $meta_key,
			'user_meta_value' => 'bad_meta_value'
		);
		$content = 'correct';

		update_user_meta( $user->ID, $meta_key, $meta_value );

		$this->assertEquals( '', $plugin->shortcode_is( $atts, $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 */
	public function test_is_meta_key_does_exist_correct_value() {
		$user = new WP_User( $this->factory->user->create() );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$plugin = WP_ConditionalLogic::get_instance();

		$meta_key = 'unknown_meta_key_test';
		$meta_value = 'unknown_meta_key_test';
		$atts = array(
			'user_meta_key' => $meta_key,
			'user_meta_value' => $meta_value
		);
		$content = 'correct';

		update_user_meta( $user->ID, $meta_key, $meta_value );

		$this->assertEquals( $content, $plugin->shortcode_is( $atts, $content ) );

		wp_set_current_user( $old_user_id );
	}

}
