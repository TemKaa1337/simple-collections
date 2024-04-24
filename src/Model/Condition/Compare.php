<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Model\Condition;

use Temkaa\SimpleCollections\Enum\ComparisonOperator;
use Temkaa\SimpleCollections\Model\ConditionInterface;

final readonly class Compare implements ConditionInterface
{
    public function __construct(
        public string $field,
        public ComparisonOperator $operator,
        public mixed $value,
    ) {
    }
}
