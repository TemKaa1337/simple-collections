<?php

declare(strict_types=1);

namespace SimpleCollections\Model\Condition;

use SimpleCollections\Model\ConditionInterface;

final readonly class Exactly implements ConditionInterface
{
    public function __construct(
        public string $field,
        public mixed $value,
    ) {
    }
}
