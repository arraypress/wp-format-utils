<?php
/**
 * Boolean rendering tests.
 *
 * @package ArrayPress\FormatUtils
 */

declare( strict_types=1 );

namespace ArrayPress\FormatUtils\Tests;

use ArrayPress\FormatUtils\Booleans;
use ArrayPress\FormatUtils\Text;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Reading a boolean out of a value that has been through a database and a
 * form, and putting a word on screen for it.
 *
 * The reading is the part with a trap in it. Meta comes back as the string
 * "0", a checkbox posts "on", and a setting that has been JSON encoded and
 * decoded carries "false" -- which PHP reads as true, being a non-empty
 * string.
 */
final class BooleansTest extends TestCase {

	/**
	 * What counts as true.
	 *
	 * @param mixed $value  The value.
	 * @param bool  $expect What it should be read as.
	 */
	#[DataProvider( 'truthyProvider' )]
	public function test_what_counts_as_true( mixed $value, bool $expect ): void {
		$this->assertSame( $expect, Booleans::truthy( $value ) );
	}

	/**
	 * @return array<string, array{0: mixed, 1: bool}>
	 */
	public static function truthyProvider(): array {
		return array(
			'true'              => array( true, true ),
			'one'               => array( 1, true ),
			'the string one'    => array( '1', true ),
			'yes'               => array( 'yes', true ),
			'on, from a tickbox'=> array( 'on', true ),
			'any other word'    => array( 'enabled', true ),

			'false'             => array( false, false ),
			'zero'              => array( 0, false ),
			'the string zero'   => array( '0', false ),
			'null'              => array( null, false ),
			'empty'             => array( '', false ),
			'whitespace'        => array( '   ', false ),
			'an empty array'    => array( array(), false ),

			// The one that catches people out: a non-empty string that PHP
			// reads as true.
			'the string false'  => array( 'false', false ),
			'the string no'     => array( 'no', false ),
			'the string off'    => array( 'off', false ),
			'the string null'   => array( 'null', false ),
			'and in caps'       => array( 'FALSE', false ),
		);
	}

	/**
	 * Each pair renders both ways round.
	 *
	 * @param string $method The renderer.
	 * @param string $yes    What true looks like.
	 * @param string $no     What false looks like.
	 */
	#[DataProvider( 'pairProvider' )]
	public function test_each_pair_renders_both_ways( string $method, string $yes, string $no ): void {
		$this->assertSame( $yes, Booleans::$method( true ) );
		$this->assertSame( $no, Booleans::$method( false ) );

		// And through the same reading as truthy(), not PHP's cast.
		$this->assertSame( $no, Booleans::$method( 'false' ) );
	}

	/**
	 * @return array<string, array{0: string, 1: string, 2: string}>
	 */
	public static function pairProvider(): array {
		return array(
			'yes/no'           => array( 'yes_no', 'Yes', 'No' ),
			'enabled/disabled' => array( 'enabled', 'Enabled', 'Disabled' ),
			'on/off'           => array( 'on_off', 'On', 'Off' ),
			'active/inactive'  => array( 'active', 'Active', 'Inactive' ),
		);
	}

	/**
	 * Nothing renders as a dash, and zero does not.
	 *
	 * A blank cell in a list table reads as a column that failed to load. Zero
	 * is a value: a count of nought and a price of nought belong on screen as
	 * numbers.
	 */
	public function test_nothing_renders_as_a_dash(): void {
		$dash = "\u{2014}";

		$this->assertSame( $dash, Text::dash( null ) );
		$this->assertSame( $dash, Text::dash( '' ) );
		$this->assertSame( $dash, Text::dash( array() ) );
		$this->assertSame( $dash, Text::dash( false ) );

		$this->assertSame( '0', Text::dash( 0 ) );
		$this->assertSame( '0', Text::dash( '0' ) );
		$this->assertSame( '0.00', Text::dash( '0.00' ) );
		$this->assertSame( 'value', Text::dash( 'value' ) );
	}
}
