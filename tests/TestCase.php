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

/**
 * @internal
 */
abstract class TestCase extends BaseTestCase
{
    /**
     * @param string $class
     */
    public function expectException_($class)
    {
        if (\method_exists($this, 'expectException')) {
            $this->expectException($class);

            return;
        }
        $this->setProperty('expectedException', $class);
    }

    /**
     * @param string $message
     */
    public function expectExceptionMessage_($message)
    {
        if (\method_exists($this, 'expectExceptionMessage')) {
            $this->expectExceptionMessage($message);

            return;
        }
        $this->setProperty('expectedExceptionMessage', $message);
    }

    /**
     * @param string $name
     * @param $value
     */
    private function setProperty($name, $value)
    {
        $reflection = new \ReflectionProperty('PHPUnit_Framework_TestCase', $name);
        $reflection->setAccessible(true);
        $reflection->setValue($this, $value);
    }
}
