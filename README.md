# WordPress Format Utilities

Turn raw values into something a person can read.

## What it does

Every admin screen ends up with the same handful of formatters written inline:
bytes as `2.4 MB`, a duration as `1:23:45` or "about 2 hours", a boolean as
Yes/No, a big number as `1.2k`, a list as "red, green and blue".

They are each small, which is exactly why they get rewritten per screen and
drift apart. This is one copy of each, so a file size looks the same wherever
it appears.

## Features

- Format bytes as KB/MB/GB, and parse `512M` back into a number
- Show a duration as a clock, a compact string, or words
- Yes/No, Enabled/Disabled, On/Off and Active/Inactive from any truthy value
- Compact large numbers (`1.2k`, `3.4M`) and format percentages
- Join a list into "red, green and blue", with an optional cut-off
- Pluralise against a count, and add ordinal suffixes
- Describe a file by kind — "Image", "Spreadsheet" — rather than by MIME type
- Fall back to an em dash for anything empty, instead of a blank cell
- Generate a coloured initials avatar as an SVG, with the colour derived from the name

## Installation

```bash
composer require arraypress/wp-format-utils
```

## Quick start

```php
use ArrayPress\FormatUtils\Bytes;
use ArrayPress\FormatUtils\Duration;
use ArrayPress\FormatUtils\Numbers;
use ArrayPress\FormatUtils\Text;

Bytes::format( 2517834 );          // 2.4 MB
Bytes::parse( '512M' );            // 536870912

Duration::clock( 4530 );           // 1:15:30
Duration::words( 4530 );           // about 1 hour

Numbers::compact( 12400 );         // 12.4k
Text::list( [ 'red', 'green', 'blue' ] );  // red, green and blue
Text::plural( $count, 'order' );   // "1 order" / "4 orders"
Text::dash( $maybe_empty );        // the value, or an em dash
```

## Requirements

* PHP 8.3 or later
* WordPress 7.1 or later

## License

GPL-2.0-or-later
