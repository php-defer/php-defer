<?php

namespace PhpDefer;

/**
 * @internal
 */
final class DeferrableStack extends \SplStack
{
    public function __destruct()
    {
        while ($this->count() > 0) {
            \call_user_func($this->pop());
        }
    }
}
