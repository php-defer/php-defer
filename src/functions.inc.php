<?php

use PhpDefer\Deferable;

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
 * @param null|Deferable $context
 * @param callable       $callback
 */
function rdefer(?Deferable &$context, callable $callback)
{
    $deferable = new class($callback) implements Deferable {
        /** @var callable */
        private $callback;
        /** @var Deferable */
        private $deferable;

        public function __construct($callback)
        {
            $this->callback = $callback;
        }

        public function __destruct()
        {
            if (isset($this->deferable)) {
                unset($this->deferable);
            }

            \call_user_func($this->callback);
        }

        public function append(Deferable $deferable)
        {
            if (isset($this->deferable)) {
                $this->deferable->append($deferable);
            } else {
                $this->deferable = $deferable;
            }
        }
    };

    if (! isset($context)) {
        $context = $deferable;
        return;
    }

    $context->append($deferable);
}
