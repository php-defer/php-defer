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
 */
final class Sentence
{
    /**
     * @var string[]
     */
    private array $parts = [];

    /**
     * @param string $part
     */
    public function append($part): void
    {
        $this->parts[] = $part;
    }

    public function getSentence(): string
    {
        return \implode(' ', $this->parts);
    }
}
