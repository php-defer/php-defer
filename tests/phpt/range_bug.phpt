--INI--
track_errors=0
--FILE--
<?php

require \implode(\DIRECTORY_SEPARATOR, array(__DIR__, '..', '..', 'vendor', 'autoload.php'));

function defer_range(&$arr, $min, $max)
{
    $arr = array();
    for ($i = $max; $i >= $min; --$i) {
        // $i is a reference, value of given reference is being changed after each iteration
        defer($_, function () use (&$arr, &$i) {
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
    [0] => 0
    [1] => 0
    [2] => 0
)
