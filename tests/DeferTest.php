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
 *
 * @covers \defer
 * @covers \PhpDefer\Defer
 */
final class DeferTest extends TestCase
{
    public function testDefer()
    {
        $sentence = new Sentence();
        $this->appendOneTwoThree($sentence);
        $this->assertSame('one two three', $sentence->getSentence());
    }

    public function testThrowException()
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
        $this->expectException_(\get_class(new DeferException()));
        $this->expectExceptionMessage_('deferred');

        defer($_, function () {
            echo "throwing deferred exception\n";

            throw new DeferException('deferred');
        });

        echo "before exception\n";
    }

    public function testMultipleContexts()
    {
        $sentence = new Sentence();
        $this->appendOneTwoThreeInMultipleContexts($sentence);
        $this->assertSame('one two three', $sentence->getSentence());
    }

    public function testUnsetContext()
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

    /**
     * @dataProvider providerInvalidArgumentCallable
     *
     * @param $notCallable
     */
    public function testInvalidArgumentCallable($notCallable)
    {
        $this->expectException_(\get_class(new \InvalidArgumentException()));
        $this->expectExceptionMessage_('Function defer expects argument $callable of type callable');
        defer($_, $notCallable);
    }

    public function providerInvalidArgumentCallable()
    {
        return array(
            array(null),
            array(''),
            array(5),
            array(3.15),
        );
    }

    public function testOrder()
    {
        $min = 1;
        $max = 1000;
        $expected = \range($min, $max);

        for ($i = 0; $i < 100; ++$i) {
            $this->range($range, $min, $max);
            $this->assertSame($expected, $range);
        }
    }

    private function range(&$arr, $min, $max)
    {
        $arr = array();
        for ($i = $max; $i >= $min; --$i) {
            defer($_, function () use (&$arr, $i) {
                $arr[] = $i;
            });
        }
    }

    private function appendOneTwoThreeInMultipleContexts(Sentence $sentence)
    {
        defer($ctx1, function () use ($sentence) {
            $sentence->append('two');
        });

        defer($ctx2, function () use ($sentence) {
            $sentence->append('three');
        });

        $sentence->append('one');
    }

    private function appendOneTwoThree(Sentence $sentence)
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
    private function throwExceptionAndDefer(Sentence $sentence)
    {
        defer($_, function () use ($sentence) {
            $sentence->append('after exception');
        });

        $sentence->append('before exception');
        $sentence->append('...');

        throw new DeferException();
    }
}
