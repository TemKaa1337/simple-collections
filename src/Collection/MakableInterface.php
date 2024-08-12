<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Collection;

interface MakableInterface
{
    public static function make(array $elements): CollectionInterface;
}
