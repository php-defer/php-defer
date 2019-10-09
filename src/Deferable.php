<?php

namespace PhpDefer;

interface Deferable
{
    /**
     * Append a deferable to the deepest available child.
     *
     * @param Deferable $deferable
     */
    public function append(Deferable $deferable);
}
