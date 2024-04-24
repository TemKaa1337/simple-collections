<?php

declare(strict_types=1);

namespace Temkaa\SimpleCollections\Model\Sum;

use Temkaa\SimpleCollections\Model\SumCriteriaInterface;

final readonly class ByField implements SumCriteriaInterface
{
    public function __construct(
        public string $field,
    ) {
    }
}
