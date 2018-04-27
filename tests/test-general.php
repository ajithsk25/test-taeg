<?php

use MotorK\{ Rate, Tae, Taeg };

class TestTaeg extends \PHPUnit\Framework\TestCase {
	
	/**
	 * Example from http://www.calcolatoremutui.it/tan-e-taeg/
	 */
	public function test_tae() {
		$obj = Tae::init( 5, 12 );

		$this->assertEquals( 5.116, round( $obj->calculate(), 3 ) );

		$this->expectOutputString( '5.116 %' );
		echo $obj;
	}

	/**
	 * Example from http://www.calcolatoremutui.it/tan-e-taeg/
	 */
	public function test_rate() {
		$obj = Rate::init( 100000, 5, 12, 20 );

		$this->assertEquals( 659.96, round( $obj->calculate(), 2 ) );

		$this->expectOutputString( '659.96 €' );
		echo $obj;
	}

	/**
	 * Example from http://www.calcolatoremutui.it/tan-e-taeg/
	 */
	public function test_taeg() {
		$obj = Taeg::init( 99000, 5, 661.96, 12, 20 );

		$this->assertEquals( 5.281, round( $obj->calculate(), 3 ) );

		$this->expectOutputString( '5.281 %' );
		echo $obj;
	}
}
