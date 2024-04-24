<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Collection;

interface MappableInterface
{
    public function map(callable $callback): CollectionInterface;
}
