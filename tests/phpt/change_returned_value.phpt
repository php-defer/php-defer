--FILE--
<?php

// example in GO https://play.golang.org/p/8RGPPPQLgQu

require \implode(\DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'vendor', 'autoload.php']);

/**
 * Function must return value by reference.
 */
function &foo()
{
    // variable $result must be passed to anonymous function by reference
    defer($_, function () use (&$result) {
        $result = 'Change World';
    });

    $result = 'Hello World';

    return $result;
}

echo foo();

?>
--EXPECT--
Change World
