<?php

/*
 * This file is part of the php-defer/php-defer package.
 *
 * (c) Bartłomiej Krukowski <bartlomiej@krukowski.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function defer(?SplStack &$context, callable $callback): SplStack
{
    $context = $context ?? new class extends SplStack {
        public function __destruct() {
            $this->rewind();
            while ($this->count() > 0) {
                \call_user_func($this->pop());
            }
        }
    };

    $context->push($callback);
    
    return $context;
}
