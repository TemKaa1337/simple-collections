<?php

declare(strict_types=1);

namespace SimpleCollections\Model\Condition;

use SimpleCollections\Enum\ComparisonOperator;
use SimpleCollections\Model\ConditionInterface;

final readonly class Compare implements ConditionInterface
{
    public function __construct(
        public string $field,
        public ComparisonOperator $operator,
        public mixed $value,
    ) {
    }
}
