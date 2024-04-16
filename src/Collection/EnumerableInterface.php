<?php

declare(strict_types=1);

namespace SimpleCollections\Collection;

interface EnumerableInterface
{
    public function first(): mixed;

    public function last(): mixed;
}
