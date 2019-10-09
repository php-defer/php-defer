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
 * @covers \rdefer
 */
final class ReverseDeferTest extends TestCase
{
    public function testDefer()
    {
        $sentence = new Sentence();
        $this->appendOneTwoThree($sentence);
        $this->assertSame('one two three', $sentence->getSentence());
    }

    public function testDeferDeepNested()
    {
        $sentence = new Sentence();
        $this->appendOneTwoThreeFour($sentence);
        $this->assertSame('one two three four', $sentence->getSentence());
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

        rdefer($ctx1, function () use ($s1) {
            $s1->append('defer');
        });
        rdefer($ctx2, function () use ($s2) {
            $s2->append('defer');
        });

        $this->assertSame('', $s1->getSentence());
        $this->assertSame('', $s2->getSentence());

        unset($ctx2);
        $this->assertSame('', $s1->getSentence());
        $this->assertSame('defer', $s2->getSentence());
    }

    /**
     * @param Sentence $sentence
     */
    private function appendOneTwoThreeInMultipleContexts(Sentence $sentence)
    {
        rdefer($ctx1, function () use ($sentence) {
            $sentence->append('two');
        });

        rdefer($ctx2, function () use ($sentence) {
            $sentence->append('three');
        });

        $sentence->append('one');
    }

    /**
     * @param Sentence $sentence
     */
    private function appendOneTwoThree(Sentence $sentence)
    {
        rdefer($_, function () use ($sentence) {
            $sentence->append('three');
        });

        rdefer($_, function () use ($sentence) {
            $sentence->append('two');
        });

        $sentence->append('one');
    }

    /**
     * @param Sentence $sentence
     */
    private function appendOneTwoThreeFour(Sentence $sentence)
    {
        rdefer($_, function () use ($sentence) {
            $sentence->append('four');
        });

        rdefer($_, function () use ($sentence) {
            $sentence->append('three');
        });

        rdefer($_, function () use ($sentence) {
            $sentence->append('two');
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
        rdefer($_, function () use ($sentence) {
            $sentence->append('after exception');
        });

        $sentence->append('before exception');
        $sentence->append('...');

        throw new DeferException();
    }
}
