<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Collection;

interface FullnessInterface
{
    public function isEmpty(): bool;

    public function isNotEmpty(): bool;
}
