<?php declare(strict_types = 1);

namespace SimpleCollections\Collections;

use SimpleCollections\Exceptions\InvalidOperatorException;

class ObjectCollection
{
    private array $collection;

    public function __construct(array $array)
    {
        $this->collection = $array;
    }
    
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

    public function where(
        string|array $fieldOrArray,
        mixed $valueOrOperator = null,
        mixed $value = null
    ) : self
    {
        if (is_array($fieldOrArray)) {
            foreach ($fieldOrArray as $condition) {
                $this->where(...$condition);
            }
        } else {
            if (
                !$this->operatorValid($valueOrOperator)
                && $value === null
            ) {
                return $this->whereExactly($fieldOrArray, $valueOrOperator);
            } else {
                if ($this->operatorValid($valueOrOperator)) {
                    $result = [];

                    foreach ($this->collection as $element) {
                        if (
                            isset($element->{$fieldOrArray})
                            && $this->getOperatorComparison($valueOrOperator, $element->{$fieldOrArray}, $value)
                        ) {
                            $result[] = $element;
                        }
                    }

                    $this->collection = $result;
                } else throw new InvalidOperatorException('The provided compare operator is invalid');
            }
        }

        return $this;
    }

    protected function whereExactly(string $field, mixed $value): self
    {
        $result = [];

        foreach ($this->collection as $element) {
            if (
                isset($element->{$field})
                && $element->{$field} === $value
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
                isset($element->{$field})
                && in_array($element->{$field}, $values)
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

    public function sort(string $field, string $sortMethod = 'asc'): self
    {
        $fn = function ($a, $b) use ($field, $sortMethod) {
            if (!isset($a->{$field}) || !isset($b->{$field})) return -1;
            if ($a->{$field} === $b->{$field}) return 0;

            if ($sortMethod === 'asc') return $a->{$field} < $b->{$field} ? 1 : -1;
            else return $a->{$field} > $b->{$field} ? 1 : -1;
        };

        usort($this->collection, $fn);
                            
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
}