# PHP Formatter

A library for formatting php code.


## Features

- Whitespace
- New lines
- Indentation (on curly braces only)
- Blanks (partial support)

-> [Wishlist](https://github.com/gossi/php-code-formatter/labels/feature-request)

## Getting started

### Installation

Via composer:

```
composer require gossi/php-code-formatter
```

### From Code

This simple code snippet is all you need:

```php
use gossi\formatter\Formatter;

$formatter = new Formatter();
$beautifulCode = $formatter->format($uglyCode);
```

### From CLI

A bare cli version is available:

```
vendor/bin/phormat path/to/file.php
```

will output the formatted source code to stdout


## Development

php code formatter is not yet finished (see [Wishlist](https://github.com/gossi/php-code-formatter/labels/feature-request)). Please help the development, by picking one of the open issues or implement your own rules. See the wiki on [creating your own rules](https://github.com/gossi/php-code-formatter/wiki/creating-your-own-Rules).

Psr-2? Spaces suck, deal with it :p Once [Version 1.0](https://github.com/gossi/php-code-formatter/milestones/Version%201.0) is reached, a psr-2 profile will be shipped.
