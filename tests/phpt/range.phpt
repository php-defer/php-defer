--INI--
track_errors=0
--FILE--
<?php

require \implode(\DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'vendor', 'autoload.php']);

function defer_range(&$arr, $min, $max)
{
    $arr = [];
    for ($i = $max; $i >= $min; --$i) {
        defer($_, function () use (&$arr, $i) {
            $arr[] = $i;
        });
    }
}

defer_range($range, 1, 3);
\print_r($range);

?>
--EXPECT--
Array
(
    [0] => 1
    [1] => 2
    [2] => 3
)
