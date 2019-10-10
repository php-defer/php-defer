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
 * @param null|SplStack $context
 * @param callable      $callback
 */
function defer(?SplStack &$context, callable $callback)
{
    $context = $context ?? new SplStack();

    $context->push(
        new class($callback) {
            private $callback;

            public function __construct($callback)
            {
                $this->callback = $callback;
            }

            public function __destruct()
            {
                \call_user_func($this->callback);
            }
        }
    );
}
