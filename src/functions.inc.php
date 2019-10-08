<?php

/*
 * This file is part of the php-defer/php-defer package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @param null|array $context
 * @param callable   $callback
 */
function defer(&$context, $callback)
{
    if (!\is_array($context) && null !== $context) {
        throw new \InvalidArgumentException(\sprintf(
            'Function %s expects argument $context of type array or null, %s given',
            __FUNCTION__,
            \is_object($callback) ? \get_class($callback) : \gettype($callback)
        ));
    }
    if (!\is_callable($callback)) {
        throw new \InvalidArgumentException(\sprintf(
            'Function %s expects argument $callable of type callable, %s given',
            __FUNCTION__,
            \is_object($callback) ? \get_class($callback) : \gettype($callback)
        ));
    }
    $context[] = new \PhpDefer\Defer($callback);
}
