<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Collection;

use Temkaa\SimpleCollections\Model\ConditionInterface;
use Temkaa\SimpleCollections\Model\UniqueCriteriaInterface;

interface FilterableInterface
{
    public function filter(callable $callback): CollectionInterface;

    public function unique(?UniqueCriteriaInterface $criteria = null): CollectionInterface;

    public function where(ConditionInterface $condition): CollectionInterface;
}
