# WordPress Format Utilities

Display formatting utilities for WordPress with full internationalization support. Provides clean, consistent formatting for numbers, dates, text, and data presentation across WordPress themes and plugins.

## Features

* 🌍 **Internationalization Ready**: Full i18n support using WordPress translation functions
* 📊 **Number Formatting**: Currency, percentages, file sizes with WordPress standards
* 📅 **Date/Time Formatting**: WordPress-compatible date formatting with timezone support
* 📝 **Text Formatting**: HTML processing, truncation, and label generation
* ✅ **Boolean Formatting**: Translatable yes/no, on/off, true/false strings
* 📞 **Contact Formatting**: Phone numbers and email links with international support
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
Format::binary( true );              // '1' (not translated - universal)

// Perfect for admin interfaces and form display
$enabled = get_option( 'feature_enabled' );
echo Format::yes_no( $enabled, true ); // Shows 'Yes' or 'No' in user's language
```

### Number Formatting

```php
// WordPress i18n number formatting
Format::numeric( 1234.56 );         // '1,234.56' (respects locale)
Format::numeric( 1234.56, 0 );      // '1,235' (no decimals)

// Custom number formatting
Format::number( 1234.56, 2 );       // '1,234.56'
Format::percentage( 0.75 );         // '75%'
Format::currency( 99.99 );          // '$99.99'
Format::currency( 99.99, '€' );     // '€99.99'

// File sizes (uses WordPress core function)
Format::file_size( 1048576 );       // '1 MB' (translatable units)
```

### Date and Time Formatting

```php
// WordPress i18n date formatting
Format::date( '2024-01-15' );                    // 'January 15, 2024' (translated)
Format::date( '2024-01-15', 'Y-m-d' );          // '2024-01-15'
Format::date( time(), 'F j, Y', false );        // English only

// Time formatting
Format::time_ago( '2024-01-01' );               // '2 weeks ago'
Format::duration( 3661 );                       // '1 hour 1 minute'
Format::duration( 3661, true );                 // '1h 1m' (abbreviated)
```

### Text Formatting

```php
// WordPress text processing
Format::html( "Hello\n\nWorld" );               // '<p>Hello</p><p>World</p>'
Format::label( 'first_name' );                  // 'First Name'
Format::excerpt( $long_text, 100 );             // 'Truncated text...'

// Empty value handling
Format::maybe_dash( '' );                       // '&mdash;'
Format::maybe_dash( 'Hello' );                  // 'Hello'
Format::maybe_dash( '', false );                // Returns string instead of echoing
```

### Contact Information

```php
// Phone number formatting with international support
Format::phone( '1234567890' );                  // '(123) 456-7890' (US default)
Format::phone( '1234567890', 'us' );            // '(123) 456-7890'
Format::phone( '01234567890', 'uk' );           // '0123 456 7890'
Format::phone( '+44 123 456 7890' );            // '+44 123 456 7890' (international preserved)

// Email formatting
Format::email_link( 'user@example.com' );       // '<a href="mailto:user@example.com">user@example.com</a>'
Format::email_link( 'user@example.com', 'Contact Us' ); // '<a href="mailto:user@example.com">Contact Us</a>'
```

### List Formatting with Overflow

```php
$products = ['Theme A', 'Plugin B', 'Template C', 'Widget D', 'Block E'];

// Natural language lists
Format::list( ['Apple', 'Banana'] );            // 'Apple and Banana'
Format::list( ['A', 'B', 'C'] );               // 'A, B, and C'

// Smart overflow handling
Format::list_with_overflow( $products, 3 );     // 'Theme A, Plugin B, and 3 more'
Format::list_with_overflow( $products, 2 );     // 'Theme A and 4 more'

// Custom separators and overflow text
Format::list_with_overflow( 
    $products, 
    3, 
    ' | ',           // separator
    ' & ',           // last separator  
    '%d others'      // overflow text
); // 'Theme A | Plugin B & 3 others'
```

### WordPress-Specific Formatting

```php
// Ratings with WordPress i18n
Format::rating( 5 );                            // '5 Stars' (translatable)
Format::rating( 1 );                            // '1 Star' (singular, translatable)
Format::rating( 0 );                            // 'No Rating' (translatable)
```

## API Reference

### Boolean Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `yes_no($value, $title_case = false)` | Convert to translatable yes/no | `string` |
| `on_off($value, $title_case = false)` | Convert to translatable on/off | `string` |
| `binary($value)` | Convert to 1/0 string | `string` |
| `true_false($value, $title_case = false)` | Convert to translatable true/false | `string` |

### Number Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `numeric($value, $decimals = 0)` | WordPress i18n number formatting | `string` |
| `number($number, $decimals = 2, $dec_point = '.', $thousands_sep = ',')` | Custom number formatting | `string` |
| `percentage($value, $decimals = 0)` | Format as percentage | `string` |
| `currency($amount, $currency = '$', $decimals = 2)` | Format as currency | `string` |
| `file_size($bytes, $decimals = 0)` | Human readable file size | `string` |

### Date/Time Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `date($date, $format = 'F j, Y', $translate = true)` | WordPress i18n date formatting | `string` |
| `duration($seconds, $abbreviated = false)` | Human readable duration | `string` |
| `time_ago($date)` | Relative time formatting | `string` |

### Text Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `html($text)` | WordPress text processing | `string` |
| `label($key)` | Convert key to label | `string` |
| `excerpt($text, $length = 150, $suffix = '...')` | Smart text truncation | `string` |
| `maybe_dash($value, $echo = true)` | Em dash fallback | `string\|null` |

### Contact Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `phone($phone, $country = 'us')` | International phone formatting | `string` |
| `email_link($email, $text = '')` | Mailto link generation | `string` |

### List Formatting Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `list($items, $separator = ', ', $last_sep = ' and ')` | Natural language lists | `string` |
| `list_with_overflow($items, $limit = 3, $separator = ', ', $last_sep = ' and ', $more_text = '%d more')` | Lists with overflow handling | `string` |

### WordPress-Specific Methods

| Method | Description | Returns |
|--------|-------------|---------|
| `rating($rating)` | Translatable star rating | `string` |

## Common Use Cases

### Template Display

```php
// Product information display
$price = get_post_meta( $product_id, 'price', true );
$rating = get_post_meta( $product_id, 'rating', true );
$featured = get_post_meta( $product_id, 'featured', true );

echo Format::currency( $price );           // '$29.99'
echo Format::rating( $rating );            // '4 Stars'
echo Format::yes_no( $featured, true );    // 'Yes' or 'No'
```

### Admin Interface

```php
// Settings page display
function display_settings_table( $options ) {
    foreach ( $options as $key => $value ) {
        echo '<tr>';
        echo '<td>' . Format::label( $key ) . '</td>';
        
        if ( is_bool( $value ) ) {
            echo '<td>' . Format::yes_no( $value, true ) . '</td>';
        } elseif ( is_numeric( $value ) ) {
            echo '<td>' . Format::numeric( $value ) . '</td>';
        } else {
            echo '<td>' . Format::maybe_dash( $value, false ) . '</td>';
        }
        
        echo '</tr>';
    }
}
```

### Content Lists

```php
// Category display with overflow
$categories = wp_get_post_categories( $post_id, ['fields' => 'names'] );
echo 'Categories: ' . Format::list_with_overflow( $categories, 3 );
// Output: "Categories: WordPress, PHP, and 2 more"

// Tag cloud with natural language
$tags = get_the_tags( $post_id );
$tag_names = wp_list_pluck( $tags, 'name' );
echo 'Tagged: ' . Format::list( $tag_names );
// Output: "Tagged: Development, Tutorial, and Advanced"
```

### User Profile Display

```php
// User information formatting
$user_meta = get_user_meta( $user_id );

echo 'Phone: ' . Format::phone( $user_meta['phone'][0] ?? '', 'us' );
echo 'Email: ' . Format::email_link( $user->user_email );
echo 'Joined: ' . Format::date( $user->user_registered );
echo 'Premium: ' . Format::yes_no( $user_meta['is_premium'][0] ?? false, true );
```

### Dashboard Statistics

```php
// Statistics dashboard
$stats = get_site_statistics();

echo 'Total Users: ' . Format::numeric( $stats['users'] );
echo 'Storage Used: ' . Format::file_size( $stats['storage_bytes'] );
echo 'Uptime: ' . Format::duration( $stats['uptime_seconds'] );
echo 'Success Rate: ' . Format::percentage( $stats['success_rate'] );
echo 'Last Updated: ' . Format::time_ago( $stats['last_update'] );
```

### Multilingual Sites

```php
// All formatting respects WordPress locale
switch_to_locale( 'es_ES' );
echo Format::yes_no( true, true );     // 'Sí'
echo Format::date( time() );           // 'enero 15, 2024'
echo Format::rating( 5 );              // '5 Estrellas'
restore_current_locale();
```

### Custom Post Type Display

```php
// Event post type
$event_meta = get_post_meta( $event_id );

echo 'Date: ' . Format::date( $event_meta['event_date'][0], 'F j, Y' );
echo 'Duration: ' . Format::duration( $event_meta['duration_seconds'][0] );
echo 'Price: ' . Format::currency( $event_meta['price'][0] );
echo 'Categories: ' . Format::list_with_overflow( 
    explode( ',', $event_meta['categories'][0] ), 
    2 
);
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

### Usage in Themes/Plugins

Load translations in your theme or plugin:

```php
// In your theme's functions.php or plugin's main file
load_textdomain( 'arraypress', '/path/to/translations/arraypress-' . get_locale() . '.mo' );
```

## Key Benefits

- **WordPress-Native**: Uses core WordPress functions for consistency
- **Internationalization**: Full i18n support for global WordPress sites
- **Template-Ready**: Perfect for theme and plugin template files
- **Admin-Friendly**: Ideal for admin interfaces and settings pages
- **Performance**: Leverages WordPress core functions for optimal performance
- **Extensible**: Clean API for custom formatting needs
- **Type-Safe**: Strict typing with predictable return values

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