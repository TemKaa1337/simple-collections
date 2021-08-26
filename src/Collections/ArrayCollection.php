<?php declare(strict_types = 1);

class ArrayCollection
{
    private array $collection;

    public function __construct(array $array)
    {
        $this->collection = $array;
    }

    public function where(string $field, mixed $value): self
    {
        $result = [];

        foreach ($this->collection as $element) {
            if ($element[$field] === $value) {
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
            if (in_array($element[$field], $values)) {
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
        $fn = fn ($a, $b) => $sortMethod === 'asc'
                               ? $a[$field] > $b[$field]
                               : $a[$field] < $b[$field];
                            
        return $this->sortBy($fn);
    }

    public function sortBy(callable $fn): self
    {
        usort($this->collection, $fn);

        return $this;
    }

    public function all() : array
    {
        return $this->collection;
    }
}