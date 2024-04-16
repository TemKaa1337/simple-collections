<?php

declare(strict_types=1);

namespace SimpleCollections\Collection;

use Countable;
use IteratorAggregate;
use SimpleCollections\Model\SumCriteriaInterface;

interface CollectionInterface extends ArrayableInterface,
    Countable,
    EnumerableInterface,
    FilterableInterface,
    FullnessInterface,
    IteratorAggregate,
    MappableInterface,
    SortableInterface
{
    public function add(mixed $value, int|string|null $key = null): void;

    /**
     * @return array<int, self>
     */
    public function chunk(int $size): array;

    public function each(callable $callback): self;

    public function has(mixed $value): bool;

    public function merge(self $collection, bool $recursive = false): self;

    public function remove(mixed $value): mixed;

    public function slice(int $offset, ?int $length = null): self;

    public function sum(?SumCriteriaInterface $criteria = null): float|int;
}
