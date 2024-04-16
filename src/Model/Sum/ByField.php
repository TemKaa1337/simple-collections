<?php

declare(strict_types=1);

namespace SimpleCollections\Model\Sum;

use SimpleCollections\Model\SumCriteriaInterface;

final readonly class ByField implements SumCriteriaInterface
{
    public function __construct(
        public string $field,
    ) {
    }
}
