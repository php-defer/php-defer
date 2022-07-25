# PHP Defer

[![Coverage Status](https://coveralls.io/repos/github/php-defer/php-defer/badge.svg?branch=5.0)](https://coveralls.io/github/php-defer/php-defer?branch=5.0)
[![Tests status](https://github.com/php-defer/php-defer/actions/workflows/tests.yml/badge.svg)](https://github.com/php-defer/php-defer/actions/workflows/tests.yml?query=branch%3A5.0)

The [defer statement](https://blog.golang.org/defer-panic-and-recover) originally comes from Golang. This library allows you to use the defer functionality in your PHP code.

## Usage

```php
<?php

defer($context, $callback);
```

`defer` requires two parameters: `$context` and `$callback`.

1. `$context` - unused in your app, required to achieve the "defer" effect. I recommend to use `$_` always.
2. `$callback` - a callback which is executed after the surrounding function returns.

## Examples

### Defer the execution of a code

```php
<?php

function helloGoodbye()
{
    defer($_, function () {
        echo "goodbye\n";
    });

    defer($_, function () {
        echo "...\n";
    });

    echo "hello\n";
}

echo "before hello\n";
helloGoodbye();
echo "after goodbye\n";

// Output:
//
// before hello
// hello
// ...
// goodbye
// after goodbye
```

### Defer and exceptions

```php
<?php

function throwException()
{
    defer($_, function () {
        echo "after exception\n";
    });

    echo "before exception\n";

    throw new \Exception('My exception');
}

try {
    throwException();
} catch (\Exception $e) {
    echo "exception has been caught\n";
}

// Output:
//
// before exception
// after exception
// exception has been caught
```

## Installation

PHP Defer supports all PHP versions from `^5.3` to `^8.0`.
The following command will install the latest possible version of PHP Defer for your PHP interpreter.

```
composer require "php-defer/php-defer:^3.0|^4.0|^5.0"
```

## Credits

This library is inspired by [mostka/defer](https://github.com/tito10047/php-defer).
