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
            $this->throwExceptionInDefer($sentence);
        } catch (DeferException $e) {
        }

        $this->assertSame('before exception ... after exception', $sentence->getSentence());
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
     * @dataProvider providerInvalidArgumentContext
     *
     * @param $context
     */
    public function testInvalidArgumentContext($context)
    {
        $this->expectException(\get_class(new \InvalidArgumentException()));
        $this->expectExceptionMessage('Function defer expects argument $context of type array or null');
        defer($context, function () {});
    }

    public function providerInvalidArgumentContext()
    {
        return array(
            array(''),
            array(5),
            array(3.15),
        );
    }

    /**
     * @dataProvider providerInvalidArgumentCallable
     *
     * @param $notCallable
     */
    public function testInvalidArgumentCallable($notCallable)
    {
        $this->expectException(\get_class(new \InvalidArgumentException()));
        $this->expectExceptionMessage('Function defer expects argument $callable of type callable');
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

    /**
     * @param Sentence $sentence
     */
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

    /**
     * @param Sentence $sentence
     */
    private function appendOneTwoThree(Sentence $sentence)
    {
        defer($_, function () use ($sentence) {
            $sentence->append('two');
        });
        defer($_, function () use ($sentence) {
            $sentence->append('three');
        });

        $sentence->append('one');
    }

    /**
     * @param Sentence $sentence
     *
     * @throws DeferException
     */
    private function throwExceptionInDefer(Sentence $sentence)
    {
        defer($_, function () use ($sentence) {
            $sentence->append('after exception');
        });

        $sentence->append('before exception');
        $sentence->append('...');

        throw new DeferException();
    }
}
