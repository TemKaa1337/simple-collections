<?php

declare(strict_types=1);

namespace SimpleCollections\Collection;

use SimpleCollections\Model\ConditionInterface;
use SimpleCollections\Model\UniqueCriteriaInterface;

interface FilterableInterface
{
    public function filter(callable $callback): CollectionInterface;

    public function unique(?UniqueCriteriaInterface $criteria = null): CollectionInterface;

    public function where(ConditionInterface $condition): CollectionInterface;
}
