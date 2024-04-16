<?php

declare(strict_types=1);

namespace SimpleCollections\Model\Sort;

use SimpleCollections\Enum\SortOrder;
use SimpleCollections\Model\SortCriteriaInterface;

final readonly class ByValues implements SortCriteriaInterface
{
    public function __construct(
        public SortOrder $order = SortOrder::Asc,
        public int $flags = SORT_REGULAR,
    ) {
    }
}
