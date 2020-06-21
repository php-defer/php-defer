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

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @covers \defer
 */
final class DeferTest extends TestCase
{
    public function testDefer(): void
    {
        $sentence = new Sentence();
        $this->appendOneTwoThree($sentence);
        $this->assertSame('one two three', $sentence->getSentence());
    }

    public function testThrowException(): void
    {
        $sentence = new Sentence();

        try {
            $this->throwExceptionAndDefer($sentence);
        } catch (DeferException $e) {
        }

        $this->assertSame('before exception ... after exception', $sentence->getSentence());
    }

    public function testThrowExceptionInDefer()
    {
        $expectedOutput = <<<'EXPECTED'
before exception
throwing deferred exception

EXPECTED;

        $this->expectOutputString($expectedOutput);
        $this->expectException(DeferException::class);
        $this->expectExceptionMessage('deferred');

        defer($_, function () {
            echo "throwing deferred exception\n";

            throw new DeferException('deferred');
        });

        echo "before exception\n";
    }

    public function testMultipleContexts(): void
    {
        $sentence = new Sentence();
        $this->appendOneTwoThreeInMultipleContexts($sentence);
        $this->assertSame('one two three', $sentence->getSentence());
    }

    public function testUnsetContext(): void
    {
        $s1 = new Sentence();
        $s2 = new Sentence();

        defer($ctx1, function () use ($s1) {
            $s1->append('defer');
        });
        defer($ctx2, function () use ($s2) {
            $s2->append('defer');
        });

        $this->assertSame('', $s1->getSentence());
        $this->assertSame('', $s2->getSentence());

        unset($ctx2);
        $this->assertSame('', $s1->getSentence());
        $this->assertSame('defer', $s2->getSentence());
    }

    public function testOrder(): void
    {
        $min = 1;
        $max = 1000;
        $expected = \range($min, $max);

        for ($i = 0; $i < 100; ++$i) {
            $this->range($range, $min, $max);
            $this->assertSame($expected, $range);
        }
    }

    private function appendOneTwoThreeInMultipleContexts(Sentence $sentence): void
    {
        defer($ctx1, function () use ($sentence) {
            $sentence->append('two');
        });

        defer($ctx2, function () use ($sentence) {
            $sentence->append('three');
        });

        $sentence->append('one');
    }

    private function range(&$arr, $min, $max): void
    {
        $arr = [];
        for ($i = $max; $i >= $min; --$i) {
            defer($_, function () use (&$arr, $i) {
                $arr[] = $i;
            });
        }
    }

    private function appendOneTwoThree(Sentence $sentence): void
    {
        defer($_, function () use ($sentence) {
            $sentence->append('three');
        });
        defer($_, function () use ($sentence) {
            $sentence->append('two');
        });

        $sentence->append('one');
    }

    /**
     * @throws DeferException
     */
    private function throwExceptionAndDefer(Sentence $sentence): void
    {
        defer($_, function () use ($sentence) {
            $sentence->append('after exception');
        });

        $sentence->append('before exception');
        $sentence->append('...');

        throw new DeferException();
    }
}
