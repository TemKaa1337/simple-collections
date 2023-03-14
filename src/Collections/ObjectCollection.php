<?php declare(strict_types = 1);

namespace SimpleCollections\Collections;

use SimpleCollections\Exceptions\InvalidOperatorException;
use SimpleCollections\BaseCollections\BaseCollection;

class ObjectCollection extends BaseCollection
{
    private bool $staticProps;
    
    public function __construct(array $array, bool $staticProps)
    {
        $this->collection = $array;
        $this->staticProps = $staticProps;
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

            return $this;
        }

        $isOperatorValid = $this->operatorValid($valueOrOperator);
        if (
            !$isOperatorValid
            && $value === null
        ) {
            return $this->whereExactly($fieldOrArray, $valueOrOperator);
        }

        if (!$isOperatorValid) {
            throw new InvalidOperatorException('The provided compare operator is invalid');
        }

        $result = [];
        foreach ($this->collection as $element) {
            if (
                (!$this->staticProps || property_exists($element, $fieldOrArray))
                && $this->getOperatorComparison($valueOrOperator, $element->{$fieldOrArray}, $value)
            ) {
                $result[] = $element;
            }
        }

        $this->collection = $result;
        return $this;
    }

    protected function whereExactly(string $field, mixed $value): self
    {
        $result = [];
        foreach ($this->collection as $element) {
            if (
                (!$this->staticProps || property_exists($element, $field))
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
                (!$this->staticProps || property_exists($element, $field))
                && in_array($element->{$field}, $values, strict: true)
            ) {
                $result[] = $element;
            }
        }

        $this->collection = $result;
        return $this;
    }

    public function whereNotIn(string $field, array $values): self
    {
        $result = [];
        foreach ($this->collection as $element) {
            if (
                (!$this->staticProps || property_exists($element, $field))
                && !in_array($element->{$field}, $values, strict: true)
            ) {
                $result[] = $element;
            }
        }

        $this->collection = $result;
        return $this;
    }

    public function sort(string $field, string $sortMethod = 'asc'): self
    {
        $sortFunction = function ($a, $b) use ($field, $sortMethod): int {
            if ($this->staticProps) {
                if (!property_exists($a, $field) || !property_exists($b, $field)) return -1;
            }

            if ($a->{$field} === $b->{$field}) return 0;

            if ($sortMethod === 'asc') return $a->{$field} > $b->{$field} ? 1 : -1;
            else return $a->{$field} < $b->{$field} ? 1 : -1;
        };

        usort($this->collection, $sortFunction);
        return $this;
    }
}