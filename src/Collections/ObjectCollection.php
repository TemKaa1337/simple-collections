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
                            ($this->staticProps ? property_exists($element, $fieldOrArray) : true)
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
                ($this->staticProps ? property_exists($element, $field) : true)
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
                ($this->staticProps ? property_exists($element, $field) : true)
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
                ($this->staticProps ? property_exists($element, $field) : true)
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
        $fn = function ($a, $b) use ($field, $sortMethod) {
            if ($this->staticProps) {
                if (!property_exists($a, $field) || !property_exists($b, $field)) return -1;
            }

            if ($a->{$field} === $b->{$field}) return 0;

            if ($sortMethod === 'asc') return $a->{$field} > $b->{$field} ? 1 : -1;
            else return $a->{$field} < $b->{$field} ? 1 : -1;
        };

        usort($this->collection, $fn);
                            
        return $this;
    }
}