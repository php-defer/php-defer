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
function defer(?array &$context, callable $callback)
{
    $context[] = new class($callback) {
        private $callback;

        public function __construct($callback)
        {
            $this->callback = $callback;
        }

        public function __destruct()
        {
            \call_user_func($this->callback);
        }
    };
}

/**
 * @param null|array $context
 * @param callable   $callback
 */
function go_defer(?array &$context, callable $callback)
{
    $context = $context ?? [];
    array_unshift($context, new class($callback) {
        private $callback;

        public function __construct($callback)
        {
            $this->callback = $callback;
        }

        public function __destruct()
        {
            \call_user_func($this->callback);
        }
    });
}
