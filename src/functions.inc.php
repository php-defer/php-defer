<?php

/*
 * This file is part of the php-defer/php-defer package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpDefer\DeferrableStack;

/**
 * @param DeferrableStack|null $context  the context must be an optional parameter, that is the only way to make it nullable in older PHP versions
 * @param callable             $callback
 */
function defer(DeferrableStack &$context = null, $callback)
{
    if (!\is_callable($callback)) {
        throw new \InvalidArgumentException(\sprintf(
            'Function %s expects argument $callable of type callable, %s given',
            __FUNCTION__,
            \is_object($callback) ? \get_class($callback) : \gettype($callback)
        ));
    }

    $context = $context ?: new DeferrableStack();
    $context->push($callback);
}
