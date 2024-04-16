<?php

declare(strict_types=1);

namespace SimpleCollections;

use ArrayIterator;
use SimpleCollections\Collection\CollectionInterface;
use SimpleCollections\Model\ConditionInterface;
use SimpleCollections\Model\SortCriteriaInterface;
use SimpleCollections\Model\SumCriteriaInterface;
use SimpleCollections\Model\UniqueCriteriaInterface;
use SimpleCollections\Provider\ConditionProcessorProvider;
use SimpleCollections\Provider\SortProcessorProvider;
use SimpleCollections\Provider\SumProcessorProvider;
use SimpleCollections\Provider\UniqueProcessorProvider;
use Traversable;

final class Collection implements CollectionInterface
{
    public function __construct(
        private array $elements,
    ) {
    }

    public function add(mixed $value, int|string|null $key = null): void
    {
        if ($key === null) {
            $this->elements[] = $value;
        } else {
            $this->elements[$key] = $value;
        }
    }

    /**
     * @return CollectionInterface[]
     */
    public function chunk(int $size): array
    {
        $result = [];

        $chunks = array_chunk($this->elements, $size, preserve_keys: !array_is_list($this->elements));
        foreach ($chunks as $chunk) {
            $result[] = new self($chunk);
        }

        return $result;
    }

    public function count(): int
    {
        return count($this->elements);
    }

    /**
     * @param callable(mixed $value, int|string|null $key): bool $callback
     */
    public function each(callable $callback): CollectionInterface
    {
        foreach ($this->elements as $key => $value) {
            if ($callback($value, $key) === false) {
                break;
            }
        }

        return $this;
    }

    /**
     * @param callable(mixed $value, int|string|null $key): bool $callback
     */
    public function filter(callable $callback): CollectionInterface
    {
        return new self(array_values(array_filter($this->elements, $callback, ARRAY_FILTER_USE_BOTH)));
    }

    public function first(): mixed
    {
        $firstKey = array_key_first($this->elements);

        return $firstKey === null ? null : $this->elements[$firstKey];
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->elements);
    }

    public function has(mixed $value): bool
    {
        return array_is_list($this->elements)
            ? in_array($value, $this->elements, strict: true)
            : array_key_exists($value, $this->elements);
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function isNotEmpty(): bool
    {
        return !empty($this->elements);
    }

    public function last(): mixed
    {
        $latestKey = array_key_last($this->elements);

        return $latestKey === null ? null : $this->elements[$latestKey];
    }

    /**
     * @param callable(mixed $value): mixed $callback
     */
    public function map(callable $callback): Collection
    {
        return new self(array_map($callback, $this->elements));
    }

    public function merge(CollectionInterface $collection, bool $recursive = false): CollectionInterface
    {
        $elements = $recursive
            ? array_merge_recursive($this->elements, $collection->toArray())
            : array_merge($this->elements, $collection->toArray());

        return new self($elements);
    }

    public function remove(mixed $value): mixed
    {
        if (!$this->has($value)) {
            return null;
        }

        $elements = $this->elements;

        $key = array_is_list($elements)
            ? array_search($value, $elements, strict: true)
            : $value;

        $removedValue = $this->elements[$key];

        unset($this->elements[$key]);

        if (array_is_list($this->elements)) {
            $this->elements = array_values($this->elements);
        }

        return $removedValue;
    }

    public function slice(int $offset, ?int $length = null): CollectionInterface
    {
        return new self(array_slice($this->elements, $offset, $length));
    }

    public function sort(SortCriteriaInterface $criteria): CollectionInterface
    {
        $elements = $this->elements;

        $elements = (new SortProcessorProvider($criteria))
            ->provide()
            ->process($elements, $criteria);

        return new self($elements);
    }

    public function sum(?SumCriteriaInterface $criteria = null): float|int
    {
        $elements = $this->elements;

        return (new SumProcessorProvider($criteria))
            ->provide()
            ->process($elements, $criteria);
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function unique(?UniqueCriteriaInterface $criteria = null): CollectionInterface
    {
        $elements = $this->elements;

        $elements = (new UniqueProcessorProvider($criteria))
            ->provide()
            ->process($elements, $criteria);

        return new self($elements);
    }

    public function where(ConditionInterface $condition): CollectionInterface
    {
        $elements = $this->elements;

        $elements = (new ConditionProcessorProvider($condition))
            ->provide()
            ->process($elements, $condition);

        return new self($elements);
    }
}
