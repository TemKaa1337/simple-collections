<?php declare(strict_types = 1);

namespace Temkaa\Collections;

use Temkaa\Collections\ObjectCollection;
use Temkaa\Collections\ArrayCollection;

class Collection
{
    protected array $collection;

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
}