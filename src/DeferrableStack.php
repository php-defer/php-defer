<?php

/*
 * This file is part of the php-defer/php-defer package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
