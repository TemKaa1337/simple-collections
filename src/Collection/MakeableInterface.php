<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Collection;

interface MakeableInterface
{
    public static function make(array $elements): CollectionInterface;
}
