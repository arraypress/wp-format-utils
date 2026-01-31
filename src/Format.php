<?php
/**
 * Format Utility Class
 *
 * Provides utility functions for formatting various types of data for display
 * in WordPress applications with internationalization support.
 *
 * @package     ArrayPress\FormatUtils
 * @copyright   Copyright (c) 2025, ArrayPress Limited
 * @license     GPL-2.0-or-later
 * @since       1.0.0
 */

declare( strict_types=1 );

namespace ArrayPress\FormatUtils;

/**
 * Format Class
 *
 * Utility functions for formatting data for display.
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
			return $title_case ? __( 'Yes', 'arraypress' ) : __( 'yes', 'arraypress' );
		}

		return $title_case ? __( 'No', 'arraypress' ) : __( 'no', 'arraypress' );
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
			return $title_case ? __( 'On', 'arraypress' ) : __( 'on', 'arraypress' );
		}

		return $title_case ? __( 'Off', 'arraypress' ) : __( 'off', 'arraypress' );
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
			return $title_case ? __( 'True', 'arraypress' ) : __( 'true', 'arraypress' );
		}

		return $title_case ? __( 'False', 'arraypress' ) : __( 'false', 'arraypress' );
	}

	/** Number Formatting *******************************************************/

	/**
	 * Format numeric values with WordPress i18n support.
	 *
	 * Returns em dash for non-numeric values.
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
	 * Format a number as a percentage.
	 *
	 * @param float $value    The value to format (0.75 = 75%).
	 * @param int   $decimals Number of decimal places.
	 *
	 * @return string Formatted percentage.
	 */
	public static function percentage( float $value, int $decimals = 0 ): string {
		return number_format( $value * 100, $decimals ) . '%';
	}

	/** Date/Time Formatting ****************************************************/

	/**
	 * Format a date with WordPress i18n support.
	 *
	 * Accepts timestamps, date strings, or DateTime objects.
	 *
	 * @param mixed  $date      The date (timestamp, string, or DateTime).
	 * @param string $format    The format for the date.
	 * @param bool   $translate Whether to translate the date string.
	 *
	 * @return string Formatted date string or em dash on failure.
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
	 * @param bool $abbreviated Whether to use abbreviated units (s, m, h, d).
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
	 * @return string Relative time string or em dash on failure.
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

		return human_time_diff( $timestamp, current_time( 'timestamp' ) ) . ' ' . __( 'ago', 'arraypress' );
	}

	/** Text Formatting *********************************************************/

	/**
	 * Format text as HTML with WordPress processing.
	 *
	 * Applies wptexturize and wpautop for proper typography and paragraphs.
	 *
	 * @param string $text The text to format.
	 *
	 * @return string Formatted HTML.
	 */
	public static function html( string $text ): string {
		return wpautop( wptexturize( $text ) );
	}

	/**
	 * Format a key into a human-readable label.
	 *
	 * Converts underscores and hyphens to spaces and capitalizes words.
	 *
	 * @param string $key The key to format (e.g., 'my_field_name').
	 *
	 * @return string Formatted label (e.g., 'My Field Name').
	 */
	public static function label( string $key ): string {
		return ucwords( str_replace( [ '_', '-' ], ' ', $key ) );
	}

	/**
	 * Truncate text with ellipsis.
	 *
	 * Strips HTML tags and truncates with multibyte support.
	 *
	 * @param string $text   The text to truncate.
	 * @param int    $length Maximum length including suffix.
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

	/**
	 * Format an email as a mailto link.
	 *
	 * @param string $email The email address.
	 * @param string $text  Optional link text (defaults to email).
	 *
	 * @return string Formatted mailto link or original string if invalid.
	 */
	public static function email_link( string $email, string $text = '' ): string {
		if ( ! is_email( $email ) ) {
			return $email;
		}

		$text = $text ?: $email;

		return sprintf( '<a href="mailto:%s">%s</a>', esc_attr( $email ), esc_html( $text ) );
	}

	/**
	 * Format a rating as a string with proper pluralization.
	 *
	 * @param int $rating The rating value.
	 *
	 * @return string Formatted rating string (e.g., '4 Stars').
	 */
	public static function rating( int $rating ): string {
		if ( empty( $rating ) ) {
			return __( 'No Rating', 'arraypress' );
		}

		/* translators: %s: rating value */

		return sprintf( _n( '%s Star', '%s Stars', $rating, 'arraypress' ), $rating );
	}

	/** Utility Methods *********************************************************/

	/**
	 * Return a value or em dash if empty.
	 *
	 * Useful for admin tables and data displays.
	 *
	 * @param mixed $value The value to process.
	 *
	 * @return string The value or em dash.
	 */
	public static function maybe_dash( $value ): string {
		return ! empty( $value ) ? (string) $value : self::MDASH;
	}

	/**
	 * Format an array as a natural language list.
	 *
	 * Examples:
	 * - ['A'] => 'A'
	 * - ['A', 'B'] => 'A and B'
	 * - ['A', 'B', 'C'] => 'A, B and C'
	 *
	 * @param array  $items     The array to format.
	 * @param string $separator The separator between items.
	 * @param string $last_sep  The separator before the last item.
	 *
	 * @return string Formatted list.
	 */
	public static function list( array $items, string $separator = ', ', string $last_sep = ' and ' ): string {
		if ( empty( $items ) ) {
			return '';
		}

		$items = array_values( $items );

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
	 * Examples:
	 * - 3 items, limit 5 => 'A, B and C'
	 * - 5 items, limit 3 => 'A, B and 3 more'
	 *
	 * @param array  $items     The array to format.
	 * @param int    $limit     Maximum items to show before overflow.
	 * @param string $separator The separator between items.
	 * @param string $last_sep  The separator before the last item/overflow.
	 * @param string $more_text The text template for overflow (use %d for count).
	 *
	 * @return string Formatted list with overflow handling.
	 */
	public static function list_with_overflow(
		array $items,
		int $limit = 3,
		string $separator = ', ',
		string $last_sep = ' and ',
		string $more_text = '%d more'
	): string {
		if ( empty( $items ) ) {
			return '';
		}

		$total = count( $items );

		if ( $total <= $limit ) {
			return self::list( $items, $separator, $last_sep );
		}

		$visible_items = array_slice( $items, 0, $limit - 1 );
		$remaining     = $total - ( $limit - 1 );

		if ( $more_text === '%d more' ) {
			/* translators: %d: number of additional items */
			$overflow_text = sprintf( __( '%d more', 'arraypress' ), $remaining );
		} else {
			$overflow_text = sprintf( $more_text, $remaining );
		}

		$visible_items[] = $overflow_text;

		return self::list( $visible_items, $separator, $last_sep );
	}

}
