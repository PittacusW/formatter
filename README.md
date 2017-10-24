# PHP Formatter

A library for formatting php code.


## Features

- K&R style
- New lines
- Indentation (on curly braces only)
- Equals align
- Array nested

## Getting started

### Installation

Via composer:

```
composer require contal/formatter
```

### From Code

This simple code snippet is all you need:

```php
use Contal\Formatter;

$clean = Formatter::format($uglyCode);
```
