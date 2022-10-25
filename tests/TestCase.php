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

use PHPUnit\Framework\TestCase as BaseTestCase;

if (\method_exists('PHPUnit\Framework\TestCase', 'expectException')) {
    /**
     * @internal
     */
    abstract class TestCase extends BaseTestCase {
    }
} else {
    /**
     * @internal
     */
    abstract class TestCase extends BaseTestCase {
        /**
         * @param string $class
         */
        public function expectException($class)
        {
            $this->setProperty('expectedException', $class);
        }

        /**
         * @param string $message
         */
        public function expectExceptionMessage($message)
        {
            $this->setProperty('expectedExceptionMessage', $message);
        }

        /**
         * @param string $name
         * @param $value
         */
        private function setProperty($name, $value)
        {
            $reflection = new \ReflectionProperty('PHPUnit\Framework\TestCase', $name);
            $reflection->setAccessible(true);
            $reflection->setValue($this, $value);
        }
    }
}
