<?php

class TestConditionalLogic extends WP_UnitTestCase {

	public function testClassExists() {
		$plugin = WP_ConditionalLogic::get_instance();

		$this->assertNotNull( $plugin );
	}

}
