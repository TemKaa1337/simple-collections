<?php

declare(strict_types=1);

namespace SimpleCollections\Model\Unique;

use SimpleCollections\Model\UniqueCriteriaInterface;

final readonly class ByField implements UniqueCriteriaInterface
{
    public function __construct(
        public string $field,
    ) {
    }
}
