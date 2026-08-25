<?php
/**
 * PHPUnit bootstrap.
 *
 * @package ArrayPress\FormatUtils
 */

declare( strict_types=1 );

if ( ! function_exists( '__' ) ) {
	/**
	 * Translation stub.
	 *
	 * @param string $text   Text.
	 * @param string $domain Text domain.
	 *
	 * @return string
	 */
	function __( string $text, string $domain = 'default' ): string {
		return $text;
	}
}

require_once dirname( __DIR__ ) . '/vendor/autoload.php';
