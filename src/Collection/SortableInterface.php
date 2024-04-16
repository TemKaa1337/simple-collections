<?php

declare(strict_types=1);

namespace SimpleCollections\Collection;

use SimpleCollections\Model\SortCriteriaInterface;

interface SortableInterface
{
    public function sort(SortCriteriaInterface $criteria): CollectionInterface;
}
