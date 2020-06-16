--INI--
track_errors=0
--FILE--
<?php

require \implode(\DIRECTORY_SEPARATOR, array(__DIR__, '..', '..', 'vendor', 'autoload.php'));

class DebugException extends \Exception
{
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        echo "[DEBUG] exception '{$message}' has been created\n";
    }
}

function throwExceptions()
{
    defer($_, function () {
        throw new DebugException('Deferred exception #1');
    });

    defer($_, function () {
        // the following exception is skipped
        throw new DebugException('Deferred exception #2');
    });

    // the following exception is skipped
    throw new DebugException('Normal exception');
}

try {
    throwExceptions();
} catch (\DebugException $e) {
    echo "exception '{$e->getMessage()}' has been caught \n";
}

?>
--EXPECT--
[DEBUG] exception 'Normal exception' has been created
[DEBUG] exception 'Deferred exception #2' has been created
[DEBUG] exception 'Deferred exception #1' has been created
exception 'Deferred exception #1' has been caught
