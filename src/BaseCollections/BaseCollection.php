<?php declare(strict_types = 1);

namespace SimpleCollections\BaseCollections;

abstract class BaseCollection
{
    protected array $collection;

    protected function getOperatorComparison(
        string $operator, 
        mixed $firstOperand, 
        mixed $secondOperand
    ): bool
    {
        switch ($operator) {
            case '==':  return $firstOperand == $secondOperand;
            case '<>':  return $firstOperand != $secondOperand;
            case '<':   return $firstOperand < $secondOperand;
            case '>':   return $firstOperand > $secondOperand;
            case '<=':  return $firstOperand <= $secondOperand;
            case '>=':  return $firstOperand >= $secondOperand;
            case '===': return $firstOperand === $secondOperand;
            case '!==': return $firstOperand !== $secondOperand;
        }
    }

    protected function operatorValid(mixed $operator): bool
    {
        return in_array($operator, [
            '==', '<>', '<', '>', '<=',
            '>=', '===', '!=='
        ]);
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

    public function reject(callable $fn): self
    {
        $result = [];

        foreach ($this->collection as $element) {
            if (!$fn($element))
                $result[] = $element;
        }

        $this->collection = $result;

        return $this;
    }

    public function filter(callable $fn): self
    {
        $result = [];

        foreach ($this->collection as $element) {
            if ($fn($element))
                $result[] = $element;
        }

        $this->collection = $result;

        return $this;
    }

    public function count(): int
    {
        return count($this->collection);
    }

    public function all() : array
    {
        return $this->collection;
    }
        
    public function first(): array|object|null
    {
        return $this->collection[0] ?? null;
    }

    public function last(): array|object|null
    {
        return $this->collection[count($this->collection) - 1] ?? null;
    }

    abstract public function where(        
        string|array $fieldOrArray,
        mixed $valueOrOperator = null,
        mixed $value = null
    ): self;
    abstract protected function whereExactly(string $field, mixed $value): self;
    abstract public function whereIn(string $field, array $values): self;
    abstract public function sort(string $field, string $sortMethod = 'asc'): self;
}