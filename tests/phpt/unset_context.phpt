--INI--
track_errors=0
--FILE--
<?php

require \implode(\DIRECTORY_SEPARATOR, array(__DIR__, '..', '..', 'vendor', 'autoload.php'));

function helloGoodbye()
{
    defer($_, function () {
        echo "hello\n";
    });
    // context is being destroyed here, it triggers callback immediately
    unset($_);

    defer($_, function () {
        echo "goodbye\n";
    });

    defer($_, function () {
        echo "...\n";
    });
}

echo "before hello\n";
helloGoodbye();
echo "after goodbye\n";

?>
--EXPECT--
before hello
hello
...
goodbye
after goodbye
