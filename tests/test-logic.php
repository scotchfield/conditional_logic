<?php

class TestConditionalLogic extends WP_UnitTestCase {

	public function setUp() {
		parent::setUp();

		$this->plugin = new WP_ConditionalLogic();
	}

	public function tearDown() {
		$this->plugin->destroy();
		unset( $this->plugin );

		parent::tearDown();
	}

	/**
	 * @covers WP_ConditionalLogic::__construct
	 */
	public function test_new() {
		$this->assertNotNull( $this->plugin );
	}

	/**
	 * @covers WP_ConditionalLogic::init
	 */
	public function test_init() {
		$this->plugin->init();

		$this->assertTrue( shortcode_exists( 'is' ) );
	}

	/**
	 * @covers WP_ConditionalLogic::init
	 * @covers WP_ConditionalLogic::destroy
	 */
	public function test_destroy() {
		$this->plugin->init();
		$this->plugin->destroy();

		$this->assertFalse( shortcode_exists( 'is' ) );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_user_id_correct_user() {
		$atts = array( 'user_id' => get_current_user_id() );
		$content = 'correct';

		$this->assertEquals( $content, $this->plugin->shortcode_is( $atts, $content ) );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_user_id_incorrect_user() {
		$atts = array( 'user_id' => get_current_user_id() + 1 );
		$content = 'correct';

		$this->assertEquals( '', $this->plugin->shortcode_is( $atts, $content ) );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_capability_subscriber() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'subscriber' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$content = 'correct';

		$this->assertEquals( $content, $this->plugin->shortcode_is( array( 'user_can' => 'read' ), $content ) );
		$this->assertEquals( '', $this->plugin->shortcode_is( array( 'user_can' => 'edit_posts' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_capability_contributor() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'contributor' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$content = 'correct';

		$this->assertEquals( $content, $this->plugin->shortcode_is( array( 'user_can' => 'edit_posts' ), $content ) );
		$this->assertEquals( '', $this->plugin->shortcode_is( array( 'user_can' => 'publish_posts' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_capability_author() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'author' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$content = 'correct';

		$this->assertEquals( $content, $this->plugin->shortcode_is( array( 'user_can' => 'publish_posts' ), $content ) );
		$this->assertEquals( '', $this->plugin->shortcode_is( array( 'user_can' => 'publish_pages' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_capability_editor() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'editor' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$content = 'correct';

		$this->assertEquals( $content, $this->plugin->shortcode_is( array( 'user_can' => 'publish_pages' ), $content ) );
		$this->assertEquals( '', $this->plugin->shortcode_is( array( 'user_can' => 'manage_options' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_capability_administrator() {
		$user = new WP_User( $this->factory->user->create( array( 'role' => 'administrator' ) ) );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$content = 'correct';

		$this->assertEquals( $content, $this->plugin->shortcode_is( array( 'user_can' => 'manage_options' ), $content ) );
		$this->assertEquals( '', $this->plugin->shortcode_is( array( 'user_can' => 'abcdefghijklmnopqrstuvwxyz' ), $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_meta_key_does_not_exist() {
		$user = new WP_User( $this->factory->user->create() );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$meta_key = 'unknown_meta_key_test';
		$atts = array( 'user_meta_key' => $meta_key );
		$content = 'correct';

		$this->assertEquals( '', $this->plugin->shortcode_is( $atts, $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_meta_key_does_exist() {
		$user = new WP_User( $this->factory->user->create() );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$meta_key = 'unknown_meta_key_test';
		$meta_value = 'unknown_meta_key_test';
		$atts = array( 'user_meta_key' => $meta_key );
		$content = 'correct';

		update_user_meta( $user->ID, $meta_key, $meta_value );

		$this->assertEquals( $content, $this->plugin->shortcode_is( $atts, $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_meta_key_does_exist_bad_value() {
		$user = new WP_User( $this->factory->user->create() );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$meta_key = 'unknown_meta_key_test';
		$meta_value = 'unknown_meta_key_test';
		$atts = array(
			'user_meta_key' => $meta_key,
			'user_meta_value' => 'bad_meta_value'
		);
		$content = 'correct';

		update_user_meta( $user->ID, $meta_key, $meta_value );

		$this->assertEquals( '', $this->plugin->shortcode_is( $atts, $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_meta_key_does_exist_correct_value() {
		$user = new WP_User( $this->factory->user->create() );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$meta_key = 'unknown_meta_key_test';
		$meta_value = 'unknown_meta_key_test';
		$atts = array(
			'user_meta_key' => $meta_key,
			'user_meta_value' => $meta_value
		);
		$content = 'correct';

		update_user_meta( $user->ID, $meta_key, $meta_value );

		$this->assertEquals( $content, $this->plugin->shortcode_is( $atts, $content ) );

		wp_set_current_user( $old_user_id );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_user_logged_in_false() {
		$atts = array(
			'user_logged_in' => 'true',
		);
		$content = 'correct';

		$this->assertEquals( '', $this->plugin->shortcode_is( $atts, $content ) );
	}

	/**
	 * @covers WP_ConditionalLogic::shortcode_is
	 * @covers WP_ConditionalLogic::is_user
	 */
	public function test_is_user_logged_in_true() {
		$user = new WP_User( $this->factory->user->create() );
		$old_user_id = get_current_user_id();
		wp_set_current_user( $user->ID );

		$atts = array(
			'user_logged_in' => 'true',
		);
		$content = 'correct';

		$this->assertEquals( $content, $this->plugin->shortcode_is( $atts, $content ) );

		wp_set_current_user( $old_user_id );
	}

}
