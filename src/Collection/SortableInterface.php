<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Collection;

use Temkaa\SimpleCollections\Model\SortCriteriaInterface;

interface SortableInterface
{
    public function sort(SortCriteriaInterface $criteria): CollectionInterface;
}
