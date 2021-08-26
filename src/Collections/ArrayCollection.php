<?php declare(strict_types = 1);

namespace Collections;

class ArrayCollection
{
    private array $collection;

    public function __construct(array $array)
    {
        $this->collection = $array;
    }

    //TODO : add operators <=>
    public function where(string $field, mixed $value): self
    {
        $result = [];

        foreach ($this->collection as $element) {
            if (
                isset($element[$field])
                && $element[$field] === $value
            ) {
                $result[] = $element;
            }
        }

        $this->collection = $result;

        return $this;
    }

    public function whereIn(string $field, array $values): self
    {
        $result = [];

        foreach ($this->collection as $element) {
            if (
                isset($element[$field])
                && in_array($element[$field], $values)
            ) {
                $result[] = $element;
            }
        }

        $this->collection = $result;

        return $this;
    }

    public function isEmpty(): bool
    {
        return count($this->collection) === 0;
    }

    public function isNotEmpty(): bool
    {
        return count($this->collection) !== 0;
    }

    public function map(callable $fn): array
    {
        $result = [];

        foreach ($this->collection as $element) {
            $result[] = $fn($element);
        }

        $this->collection = $result;

        return $this->all();
    }

    public function sort(string $field, string $sortMethod = 'asc'): self
    {
        $fn = function ($a, $b) use ($field, $sortMethod) {
            if (!isset($a[$field]) || !isset($b[$field])) return -1;
            if ($a[$field] === $b[$field]) return 0;

            if ($sortMethod === 'asc') return $a[$field] < $b[$field] ? 1 : -1;
            else return $a[$field] > $b[$field] ? 1 : -1;
        };

        usort($this->collection, $fn);
                            
        return $this;
    }

    public function all() : array
    {
        return $this->collection;
    }
}