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
final class Defer
{
    /**
     * @var callable callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    public function __destruct()
    {
        \call_user_func($this->callback);
    }
}
