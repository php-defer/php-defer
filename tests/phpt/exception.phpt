--INI--
track_errors=0
--FILE--
<?php

require \implode(\DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'vendor', 'autoload.php']);

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

?>
--EXPECT--
before exception
after exception
exception has been caught
