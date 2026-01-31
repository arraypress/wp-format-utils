# WordPress Format Utilities

Display formatting utilities for WordPress with full internationalization support. Provides clean, consistent formatting for numbers, dates, text, and data presentation across WordPress themes and plugins.

## Features

* 🌍 **Internationalization Ready**: Full i18n support using WordPress translation functions
* 📊 **Number Formatting**: Percentages and numeric values with WordPress standards
* 📅 **Date/Time Formatting**: WordPress-compatible date formatting with timezone support
* 📝 **Text Formatting**: HTML processing, truncation, and label generation
* ✅ **Boolean Formatting**: Translatable yes/no, on/off, true/false strings
* 📋 **List Formatting**: Smart lists with overflow handling and natural language conjunctions
* 🛡️ **WordPress-Native**: Leverages WordPress core functions for consistency

## Requirements

* PHP 7.4 or later
* WordPress 5.0 or later

## Installation

```bash
composer require arraypress/wp-format-utils
```

## Basic Usage

### Boolean Formatting

```php
use ArrayPress\FormatUtils\Format;

// Translatable boolean strings
Format::yes_no( true );              // 'yes' (translatable)
Format::yes_no( true, true );        // 'Yes' (translatable, title case)
Format::on_off( false );             // 'off' (translatable)
Format::true_false( true );          // 'true' (translatable)

// Perfect for admin interfaces and form display
$enabled = get_option( 'feature_enabled' );
echo Format::yes_no( $enabled, true ); // Shows 'Yes' or 'No' in user's language
```

### Number Formatting

```php
// WordPress i18n number formatting (returns em dash for non-numeric)
Format::numeric( 1234.56 );         // '1,234.56' (respects locale)
Format::numeric( 1234.56, 0 );      // '1,235' (no decimals)
Format::numeric( 'invalid' );       // '—' (em dash)

// Percentage formatting
Format::percentage( 0.75 );         // '75%'
Format::percentage( 0.756, 1 );     // '75.6%'
```

### Date and Time Formatting

```php
// WordPress i18n date formatting (accepts timestamp, string, or DateTime)
Format::date( '2024-01-15' );                    // 'January 15, 2024' (translated)
Format::date( '2024-01-15', 'Y-m-d' );          // '2024-01-15'
Format::date( time(), 'F j, Y', false );        // English only

// Duration formatting
Format::duration( 90 );                         // '1 minutes'
Format::duration( 3661 );                       // '1 hours 1 minutes'
Format::duration( 3661, true );                 // '1h 1m' (abbreviated)
Format::duration( 90061 );                      // '1 days 1 hours'

// Relative time
Format::time_ago( strtotime( '-2 hours' ) );    // '2 hours ago'
```

### Text Formatting

```php
// WordPress text processing
Format::html( "Hello\n\nWorld" );               // '<p>Hello</p><p>World</p>'

// Key to label conversion
Format::label( 'first_name' );                  // 'First Name'
Format::label( 'user-profile-id' );             // 'User Profile Id'

// Multibyte-safe truncation
Format::excerpt( $long_text, 100 );             // 'First 97 chars...'
Format::excerpt( $text, 50, '…' );              // 'First 49 chars…'

// Email link generation
Format::email_link( 'user@example.com' );       
// '<a href="mailto:user@example.com">user@example.com</a>'

Format::email_link( 'user@example.com', 'Contact Us' ); 
// '<a href="mailto:user@example.com">Contact Us</a>'

// Rating with proper pluralization
Format::rating( 1 );                            // '1 Star'
Format::rating( 4 );                            // '4 Stars'
Format::rating( 0 );                            // 'No Rating'
```

### Utility Methods

```php
// Em dash fallback for empty values
Format::maybe_dash( 'value' );                  // 'value'
Format::maybe_dash( '' );                       // '—'
Format::maybe_dash( null );                     // '—'

// Natural language lists
Format::list( ['Apple'] );                      // 'Apple'
Format::list( ['Apple', 'Banana'] );            // 'Apple and Banana'
Format::list( ['A', 'B', 'C'] );               // 'A, B and C'
Format::list( ['A', 'B', 'C'], ', ', ' or ' ); // 'A, B or C'

// Lists with overflow handling
$items = ['Theme A', 'Plugin B', 'Template C', 'Widget D', 'Block E'];

Format::list_with_overflow( $items, 3 );        // 'Theme A, Plugin B and 3 more'
Format::list_with_overflow( $items, 2 );        // 'Theme A and 4 more'

// Custom overflow text
Format::list_with_overflow( 
    $items, 
    3, 
    ' | ',           // separator
    ' & ',           // last separator  
    '%d others'      // overflow text
); // 'Theme A | Plugin B & 3 others'
```

## API Reference

### Boolean Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `yes_no($value, $title_case = false)` | Convert to translatable yes/no | `string` |
| `on_off($value, $title_case = false)` | Convert to translatable on/off | `string` |
| `true_false($value, $title_case = false)` | Convert to translatable true/false | `string` |

### Number Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `numeric($value, $decimals = 0)` | WordPress i18n number formatting with em dash fallback | `string` |
| `percentage($value, $decimals = 0)` | Format as percentage | `string` |

### Date/Time Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `date($date, $format = 'F j, Y', $translate = true)` | WordPress i18n date formatting | `string` |
| `duration($seconds, $abbreviated = false)` | Human readable duration | `string` |
| `time_ago($date)` | Relative time formatting | `string` |

### Text Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `html($text)` | WordPress text processing (wpautop + wptexturize) | `string` |
| `label($key)` | Convert key to human-readable label | `string` |
| `excerpt($text, $length = 150, $suffix = '...')` | Multibyte-safe text truncation | `string` |
| `email_link($email, $text = '')` | Generate mailto link | `string` |
| `rating($rating)` | Translatable star rating with pluralization | `string` |

### Utility Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `maybe_dash($value)` | Return value or em dash if empty | `string` |
| `list($items, $separator = ', ', $last_sep = ' and ')` | Natural language list formatting | `string` |
| `list_with_overflow($items, $limit = 3, ...)` | List with overflow handling | `string` |

### Constants

| Constant | Value | Description |
|----------|-------|-------------|
| `Format::MDASH` | `'&mdash;'` | HTML em dash entity |

## Common Use Cases

### Admin Table Display

```php
// Settings page display
function display_settings_table( $options ) {
    foreach ( $options as $key => $value ) {
        echo '<tr>';
        echo '<td>' . esc_html( Format::label( $key ) ) . '</td>';
        
        if ( is_bool( $value ) ) {
            echo '<td>' . esc_html( Format::yes_no( $value, true ) ) . '</td>';
        } elseif ( is_numeric( $value ) ) {
            echo '<td>' . esc_html( Format::numeric( $value ) ) . '</td>';
        } else {
            echo '<td>' . esc_html( Format::maybe_dash( $value ) ) . '</td>';
        }
        
        echo '</tr>';
    }
}
```

### Content Lists

```php
// Category display with overflow
$categories = wp_get_post_categories( $post_id, ['fields' => 'names'] );
echo 'Categories: ' . esc_html( Format::list_with_overflow( $categories, 3 ) );
// Output: "Categories: WordPress, PHP and 2 more"

// Tag cloud with natural language
$tags = get_the_tags( $post_id );
$tag_names = wp_list_pluck( $tags, 'name' );
echo 'Tagged: ' . esc_html( Format::list( $tag_names ) );
// Output: "Tagged: Development, Tutorial and Advanced"
```

### Dashboard Statistics

```php
$stats = get_site_statistics();

echo 'Total Users: ' . esc_html( Format::numeric( $stats['users'] ) );
echo 'Uptime: ' . esc_html( Format::duration( $stats['uptime_seconds'] ) );
echo 'Success Rate: ' . esc_html( Format::percentage( $stats['success_rate'] ) );
echo 'Last Updated: ' . esc_html( Format::time_ago( $stats['last_update'] ) );
```

## Internationalization

All user-facing strings are translatable using WordPress i18n functions:

- **Text Domain**: `arraypress`
- **Translation Functions**: `__()`, `_n()` for plurals
- **Translator Comments**: Included for context

### Translatable Strings

- Yes/No, On/Off, True/False formatting
- "X more" overflow text
- Star rating text
- "No Rating" text
- "ago" text

## Requirements

- PHP 7.4+
- WordPress 5.0+

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## License

This project is licensed under the GPL-2.0-or-later License.

## Support

- [Documentation](https://github.com/arraypress/wp-format-utils)
- [Issue Tracker](https://github.com/arraypress/wp-format-utils/issues)
