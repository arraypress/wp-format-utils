<?php
/**
 * Format Utility Class
 *
 * Provides utility functions for formatting various types of data for display
 * in WordPress applications with internationalization support.
 *
 * @package ArrayPress\FormatUtils
 * @since   1.0.0
 * @author  ArrayPress
 * @license GPL-2.0-or-later
 */

declare( strict_types=1 );

namespace ArrayPress\FormatUtils;

/**
 * Format Class
 *
 * Core operations for formatting data for display.
 */
class Format {

	/**
	 * The HTML entity for an em dash.
	 */
	public const MDASH = '&mdash;';

	/** Boolean Formatting ******************************************************/

	/**
	 * Convert any value to a 'yes' or 'no' string.
	 *
	 * @param mixed $value      The value to convert.
	 * @param bool  $title_case Whether to return the result in title case.
	 *
	 * @return string 'yes'/'Yes' for truthy values, 'no'/'No' for falsy values.
	 */
	public static function yes_no( $value, bool $title_case = false ): string {
		if ( $value ) {
			$result = $title_case ? __( 'Yes', 'arraypress' ) : __( 'yes', 'arraypress' );
		} else {
			$result = $title_case ? __( 'No', 'arraypress' ) : __( 'no', 'arraypress' );
		}

		return $result;
	}

	/**
	 * Convert any value to an 'on' or 'off' string.
	 *
	 * @param mixed $value      The value to convert.
	 * @param bool  $title_case Whether to return the result in title case.
	 *
	 * @return string 'on'/'On' for truthy values, 'off'/'Off' for falsy values.
	 */
	public static function on_off( $value, bool $title_case = false ): string {
		if ( $value ) {
			$result = $title_case ? __( 'On', 'arraypress' ) : __( 'on', 'arraypress' );
		} else {
			$result = $title_case ? __( 'Off', 'arraypress' ) : __( 'off', 'arraypress' );
		}

		return $result;
	}

	/**
	 * Convert any value to a '1' or '0' string.
	 *
	 * @param mixed $value The value to convert.
	 *
	 * @return string '1' for truthy values, '0' for falsy values.
	 */
	public static function binary( $value ): string {
		return $value ? '1' : '0';
	}

	/**
	 * Convert any value to a 'true' or 'false' string.
	 *
	 * @param mixed $value      The value to convert.
	 * @param bool  $title_case Whether to return the result in title case.
	 *
	 * @return string 'true'/'True' for truthy values, 'false'/'False' for falsy values.
	 */
	public static function true_false( $value, bool $title_case = false ): string {
		if ( $value ) {
			$result = $title_case ? __( 'True', 'arraypress' ) : __( 'true', 'arraypress' );
		} else {
			$result = $title_case ? __( 'False', 'arraypress' ) : __( 'false', 'arraypress' );
		}

		return $result;
	}

	/** Number Formatting *******************************************************/

	/**
	 * Format numeric values with WordPress i18n support.
	 *
	 * @param mixed $value    The value to be formatted.
	 * @param int   $decimals The number of decimal points.
	 *
	 * @return string The formatted numeric value or em dash.
	 */
	public static function numeric( $value, int $decimals = 0 ): string {
		if ( is_numeric( $value ) ) {
			return number_format_i18n( (float) $value, $decimals );
		}

		return self::MDASH;
	}

	/**
	 * Format a number with grouped thousands.
	 *
	 * @param float  $number        The number to format.
	 * @param int    $decimals      Number of decimal points.
	 * @param string $dec_point     Decimal point character.
	 * @param string $thousands_sep Thousands separator character.
	 *
	 * @return string Formatted number.
	 */
	public static function number( float $number, int $decimals = 2, string $dec_point = '.', string $thousands_sep = ',' ): string {
		return number_format( $number, $decimals, $dec_point, $thousands_sep );
	}

	/**
	 * Format a number as a percentage.
	 *
	 * @param float $value    The value to format (0.75 = 75%).
	 * @param int   $decimals Number of decimal places.
	 *
	 * @return string Formatted percentage.
	 */
	public static function percentage( float $value, int $decimals = 0 ): string {
		return self::number( $value * 100, $decimals ) . '%';
	}

	/**
	 * Format a number as currency.
	 *
	 * @param float  $amount   The amount to format.
	 * @param string $currency Currency symbol.
	 * @param int    $decimals Number of decimal places.
	 *
	 * @return string Formatted currency.
	 */
	public static function currency( float $amount, string $currency = '$', int $decimals = 2 ): string {
		return $currency . self::number( $amount, $decimals );
	}

	/** Date/Time Formatting ****************************************************/

	/**
	 * Format a date with WordPress i18n support.
	 *
	 * @param mixed  $date      The date (timestamp, string, or DateTime).
	 * @param string $format    The format for the date.
	 * @param bool   $translate Whether to translate the date string.
	 *
	 * @return string Formatted date string.
	 */
	public static function date( $date, string $format = 'F j, Y', bool $translate = true ): string {
		if ( is_numeric( $date ) ) {
			$timestamp = (int) $date;
		} else {
			$timestamp = strtotime( (string) $date );
		}

		if ( $timestamp === false ) {
			return self::MDASH;
		}

		return $translate ? date_i18n( $format, $timestamp ) : date( $format, $timestamp );
	}

	/**
	 * Format a time duration in seconds to human readable format.
	 *
	 * @param int  $seconds     The duration in seconds.
	 * @param bool $abbreviated Whether to use abbreviated units.
	 *
	 * @return string Formatted duration.
	 */
	public static function duration( int $seconds, bool $abbreviated = false ): string {
		if ( $seconds < 60 ) {
			return $abbreviated ? $seconds . 's' : $seconds . ' seconds';
		}

		$minutes = floor( $seconds / 60 );
		if ( $minutes < 60 ) {
			return $abbreviated ? $minutes . 'm' : $minutes . ' minutes';
		}

		$hours             = floor( $minutes / 60 );
		$remaining_minutes = $minutes % 60;

		if ( $hours < 24 ) {
			$result = $abbreviated ? $hours . 'h' : $hours . ' hours';
			if ( $remaining_minutes > 0 ) {
				$result .= $abbreviated ? ' ' . $remaining_minutes . 'm' : ' ' . $remaining_minutes . ' minutes';
			}

			return $result;
		}

		$days            = floor( $hours / 24 );
		$remaining_hours = $hours % 24;

		$result = $abbreviated ? $days . 'd' : $days . ' days';
		if ( $remaining_hours > 0 ) {
			$result .= $abbreviated ? ' ' . $remaining_hours . 'h' : ' ' . $remaining_hours . ' hours';
		}

		return $result;
	}

	/**
	 * Format a relative time (time ago).
	 *
	 * @param mixed $date The date to compare against current time.
	 *
	 * @return string Relative time string.
	 */
	public static function time_ago( $date ): string {
		if ( is_numeric( $date ) ) {
			$timestamp = (int) $date;
		} else {
			$timestamp = strtotime( (string) $date );
		}

		if ( $timestamp === false ) {
			return self::MDASH;
		}

		return human_time_diff( $timestamp, current_time( 'timestamp' ) ) . ' ago';
	}

	/** Text Formatting *********************************************************/

	/**
	 * Format text as HTML with WordPress processing.
	 *
	 * @param string $text The text to format.
	 *
	 * @return string Formatted HTML.
	 */
	public static function html( string $text ): string {
		return wpautop( wptexturize( $text ) );
	}

	/**
	 * Format a column key into a human-readable label.
	 *
	 * @param string $key The column key to format.
	 *
	 * @return string Formatted label.
	 */
	public static function label( string $key ): string {
		return ucwords( str_replace( [ '_', '-' ], ' ', $key ) );
	}

	/**
	 * Truncate text with ellipsis.
	 *
	 * @param string $text   The text to truncate.
	 * @param int    $length Maximum length.
	 * @param string $suffix Suffix to append if truncated.
	 *
	 * @return string Truncated text.
	 */
	public static function excerpt( string $text, int $length = 150, string $suffix = '...' ): string {
		$text = wp_strip_all_tags( $text );

		if ( mb_strlen( $text ) <= $length ) {
			return $text;
		}

		return mb_substr( $text, 0, $length - mb_strlen( $suffix ) ) . $suffix;
	}

	/** Contact Information *****************************************************/

	/**
	 * Format a phone number with basic international support.
	 *
	 * @param string $phone   The phone number to format.
	 * @param string $country Country code (us, uk, etc.) for formatting.
	 *
	 * @return string Formatted phone number.
	 */
	public static function phone( string $phone, string $country = 'us' ): string {
		$phone = preg_replace( '/[^0-9+]/', '', $phone );

		// Handle international format with +
		if ( str_starts_with( $phone, '+' ) ) {
			return $phone; // Return as-is for international format
		}

		switch ( strtolower( $country ) ) {
			case 'us':
			case 'ca': // US/Canada format
				if ( strlen( $phone ) === 10 ) {
					return preg_replace( '/(\d{3})(\d{3})(\d{4})/', '($1) $2-$3', $phone );
				}
				break;
			case 'uk':
				if ( strlen( $phone ) === 11 && str_starts_with( $phone, '0' ) ) {
					return preg_replace( '/(\d{4})(\d{3})(\d{4})/', '$1 $2 $3', $phone );
				}
				break;
		}

		return $phone; // Return unformatted if no pattern matches
	}

	/**
	 * Format an email as a mailto link.
	 *
	 * @param string $email The email address.
	 * @param string $text  Optional link text (defaults to email).
	 *
	 * @return string Formatted mailto link.
	 */
	public static function email_link( string $email, string $text = '' ): string {
		if ( ! is_email( $email ) ) {
			return $email;
		}

		$text = $text ?: $email;

		return sprintf( '<a href="mailto:%s">%s</a>', esc_attr( $email ), esc_html( $text ) );
	}

	/** WordPress Specific ******************************************************/

	/**
	 * Format the rating as a string with WordPress i18n.
	 *
	 * @param int $rating The rating value.
	 *
	 * @return string Formatted rating string.
	 */
	public static function rating( int $rating ): string {
		if ( empty( $rating ) ) {
			return __( 'No Rating', 'arraypress' );
		}

		/* translators: %s: rating value */

		return sprintf( _n( '%s Star', '%s Stars', $rating, 'arraypress' ), $rating );
	}

	/**
	 * Format file size in human readable format.
	 *
	 * @param int $bytes    The size in bytes.
	 * @param int $decimals Number of decimal places.
	 *
	 * @return string Formatted file size.
	 */
	public static function file_size( int $bytes, int $decimals = 0 ): string {
		return size_format( $bytes, $decimals );
	}

	/** Utility Methods *********************************************************/

	/**
	 * Output or return a value, using em dash if the value is empty.
	 *
	 * @param mixed $value The value to process.
	 * @param bool  $echo  Whether to echo the value.
	 *
	 * @return string|null The processed value if $echo is false, otherwise null.
	 */
	public static function maybe_dash( $value, bool $echo = true ): ?string {
		$formatted = ! empty( $value ) ? (string) $value : self::MDASH;

		if ( $echo ) {
			echo $formatted;

			return null;
		}

		return $formatted;
	}

	/**
	 * Format an array as a comma-separated list.
	 *
	 * @param array  $items     The array to format.
	 * @param string $separator The separator to use.
	 * @param string $last_sep  The separator for the last item.
	 *
	 * @return string Formatted list.
	 */
	public static function list( array $items, string $separator = ', ', string $last_sep = ' and ' ): string {
		if ( empty( $items ) ) {
			return '';
		}

		if ( count( $items ) === 1 ) {
			return (string) $items[0];
		}

		if ( count( $items ) === 2 ) {
			return implode( $last_sep, $items );
		}

		$last_item = array_pop( $items );

		return implode( $separator, $items ) . $last_sep . $last_item;
	}

	/**
	 * Format an array as a list with overflow handling.
	 *
	 * @param array  $items     The array to format.
	 * @param int    $limit     Maximum items to show before "and X more".
	 * @param string $separator The separator to use.
	 * @param string $last_sep  The separator for the last item.
	 * @param string $more_text The text template for overflow (use %d for count).
	 *
	 * @return string Formatted list with overflow handling.
	 */
	public static function list_with_overflow( array $items, int $limit = 3, string $separator = ', ', string $last_sep = ' and ', string $more_text = '%d more' ): string {
		if ( empty( $items ) ) {
			return '';
		}

		$total = count( $items );

		// If within limit, use regular list formatting
		if ( $total <= $limit ) {
			return self::list( $items, $separator, $last_sep );
		}

		// Take the first items and add overflow text
		$visible_items = array_slice( $items, 0, $limit - 1 );
		$remaining     = $total - ( $limit - 1 );

		// Use default translatable text or custom text
		if ( $more_text === '%d more' ) {
			/* translators: %d: number of additional items */
			$overflow_text = sprintf( __( '%d more', 'arraypress' ), $remaining );
		} else {
			$overflow_text = sprintf( $more_text, $remaining );
		}

		$visible_items[] = $overflow_text;

		// Use regular list for the visible items + overflow
		return self::list( $visible_items, $separator, $last_sep );
	}

}